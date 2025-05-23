<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2023
     */   

    class Admin extends AdminModel {

        public $name_page;

        function __construct($name_page = 'default-page') {
            parent::__construct();
            $this->name_page = $name_page;
            $this->login_remember();
        }

        public function getAdminData() {
            // Declare here the variables that you are going to use in several different views
            $data = array();
            $data['admin'] = array(
                'name_page' => $this->name_page,
                'tags' => array()
            );
            $data['menu'] = array(
                'home' => array('admin-home-page'),
                'products' => array('admin-products-page', 'admin-new-product-page', 'admin-edit-product-page'),
                'categories' => array('admin-categories-page', 'admin-new-category-page', 'admin-edit-category-page'),
                'attributes' => array('admin-attributes-page', 'admin-new-attribute-page', 'admin-edit-attribute-page'),
                'images' => array('admin-images-page'),
                'codes' => array('admin-codes-page', 'admin-new-code-page', 'admin-edit-code-page'),
                'carts' => array('admin-carts-page'),
                'orders' => array('admin-orders-page'),
                'shipments' => array('admin-shipments-page', 'admin-new-shipping-method-page', 'admin-edit-shipping-method-page'),
                'payments' => array('admin-payments-page', 'admin-new-payments-method-page'),
                'languages' => array('admin-languages-page'),
                'stats' => array('admin-stats-page'),
                'users' => array('admin-users-page', 'admin-edit-users-page'),
                'admin_users' => array('admin-users-admin-page', 'admin-new-admin-user-page', 'admin-edit-user-admin-page'),
                'ftp_upload' => array('ftp-upload-page')
            );
            $data['meta'] = array(
                'title' => META_TITLE
            );
            return $data;
        }

        public function login_remember() {
			if(isset($_COOKIE["admin_remember"])) {
                if(!isset($_SESSION['admin'])) {
                    $sql = 'SELECT email, pass FROM '.DDBB_PREFIX.'users_admin WHERE remember_code = ? AND id_state = 2 LIMIT 1';
                    $result = $this->query($sql, array($_COOKIE['admin_remember']));
                    if($result->num_rows != 0) {
                        $row = $result->fetch_assoc();
                        $this->login($row['email'], $row['pass']);
                    } else {
                        setcookie('admin_remember', '', time() -3600, PUBLIC_PATH.'/');
                    }
                } else {
                    // If the remember code does not match it is because the user has been kicked out
                    $sql = 'SELECT id_admin FROM '.DDBB_PREFIX.'users_admin WHERE id_admin = ? AND remember_code = ? LIMIT 1';
                    $result = $this->query($sql, array($_SESSION['admin']['id_admin'], $_COOKIE["admin_remember"]));
                    if($result->num_rows == 0) {
                        $this->logout();
                        header('Location: '.ADMIN_PATH.'/');
                        exit;
                    }
                }
            }            
        }
        
        public function logout() {
            unset($_SESSION['admin']);
            setcookie('admin_remember', '', time() -3600, PUBLIC_PATH.'/');
        }

        public function get_products($page = 1, $per_page = 20) {
            $sql = 'SELECT p.*, a.alias AS category_name, s.name AS state_name,
                        CASE WHEN ISNULL(g.id_image) THEN "" ELSE g.name END AS image_name,
                        CASE WHEN ISNULL(g.id_image) THEN "" ELSE g.url END AS image_url,
                        CASE WHEN ISNULL(n.num_products) THEN 0 ELSE n.num_products END AS num_products,
                        CASE WHEN ISNULL(n.total_stock) THEN 0 ELSE n.total_stock END AS total_stock
                    FROM '.DDBB_PREFIX.'products AS p
                        INNER JOIN '.DDBB_PREFIX.'products_categories AS c ON p.id_product = c.id_product AND c.main = 1
                        INNER JOIN '.DDBB_PREFIX.'categories AS a ON a.id_category = c.id_category
                        INNER JOIN '.DDBB_PREFIX.'ct_states AS s ON s.id_state = p.id_state
                        LEFT JOIN '.DDBB_PREFIX.'products_images AS i ON i.id_product_image = p.main_image
                        LEFT JOIN '.DDBB_PREFIX.'images AS g ON g.id_image = i.id_image
                        LEFT JOIN (
                            SELECT COUNT(id_product) AS num_products, SUM(stock) AS total_stock, id_product FROM '.DDBB_PREFIX.'products_related GROUP BY id_product
                        ) AS n ON n.id_product = p.id_product
                    ORDER BY p.priority, -p.id_product';
            $result = $this->query($sql);
            if($result->num_rows > 0) {
                $pager = $this->pager($result, $page, $per_page);
                // Painted table head
                $html = '<table>';
                $html .= '<thead>';
                $html .=    '<tr>';
                $html .=        '<th style="width: 80px;">Id</th>';
                $html .=        '<th style="width: 80px;">Image</th>';
                $html .=        '<th class="text-left">Alias</th>';
                $html .=        '<th class="text-left">Category</th>';
                $html .=        '<th class="text-left" style="width: 100px;">Price</th>';
                $html .=        '<th style="width: 100px;">Related</th>';
                $html .=        '<th style="width: 100px;">Stock</th>';
                $html .=        '<th style="width: 100px;">Priority</th>';
                $html .=        '<th style="width: 100px;">State</th>';
                $html .=        '<th style="width: 200px;"></th>';
                $html .=    '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                foreach($pager['result'] as $row) {
                    $state = ($row['id_state'] == 1) ? $row['state_name'] : '<span class="hive-color">'.$row['state_name'].'</span>';
                    $image = '';
                    if($row['image_name'] != '') {
                        $url_image = PUBLIC_PATH.'/img/products/thumbnails/'.$row['image_name'];
                        $image = '<a href="'.PUBLIC_PATH.$row['image_url'].'" target="_blank">';
                        $image .=   '<img src="'.$url_image.'" style="height: 30px;">';
                        $image .= '</a>';
                    }
                    $html .= '<tr>';
                    $html .=    '<td class="text-center">'.$row['id_product'].'</td>';
                    $html .=    '<td class="text-center">'.$image.'</td>';
                    $html .=    '<td>'.$row['alias'].'</td>';
                    $html .=    '<td>'.$row['category_name'].'</td>';
                    $html .=    '<td>'.$row['price'].' €</td>';
                    $html .=    '<td class="text-center">'.$row['num_products'].'</td>';
                    $html .=    '<td class="text-center">'.$row['total_stock'].'</td>';
                    $html .=    '<td class="text-center">'.$row['priority'].'</td>';
                    $html .=    '<td class="text-center">'.$state.'</td>';
                    $html .=    '<td class="text-center">';
                    $html .=        '<a href="edit-product?id_product='.$row['id_product'].'" class="btn btn-black btn-sm mr-5">Edit</a>';
                    $html .=        '<div class="btn btn-black btn-sm btn-delete-product" id-product="'.$row['id_product'].'">Delete</div>';
                    $html .=    '</td>';
                    $html .= '</tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
            } else {
                $html = 'No products.';
            }
            return array(
                'html' => $html,
                'pager' => (isset($pager)) ? $pager['pager'] : ''
            );
        }

        public function get_product($id_product) {
            $sql = 'SELECT p.*, r.route FROM '.DDBB_PREFIX.'products AS p
                        INNER JOIN '.DDBB_PREFIX.'products_categories AS c ON c.id_product = p.id_product
                        INNER JOIN '.DDBB_PREFIX.'products_routes AS r ON r.id_product = c.id_product AND r.id_category = c.id_category
                        INNER JOIN '.DDBB_PREFIX.'ct_languages AS l ON l.id_language = r.id_language
                    WHERE p.id_product = ? AND l.name = ? AND c.main = 1 LIMIT 1';
            $result = $this->query($sql, array($id_product, strtolower(LANG)));
            if($result->num_rows != 0) {
                $row = $result->fetch_assoc();
                $row['price'] = $this->parse_float_point_back($row['price']);
                $row['weight'] = $this->parse_float_point_back($row['weight']);
                return $row;
            } else {
                return 'error';
            }
        }

        public function get_products_list($id_product = null) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'products';
            $result = $this->query($sql);
            if($result->num_rows != 0) {
                $html = '';
                while($row = $result->fetch_assoc()) {
                    if($row['id_product'] == $id_product) {
                        $selected = ' selected';
                    } else {
                        $selected = '';
                    }
                    $html .= '<option value="'.$row['id_product'].'"'.$selected.'>'.$row['id_product'].' - '.$row['alias'].'</option>';
                }
            } else {
                $html = '';
            }
            return $html;
        }

        public function get_categories($id_parent, $page = 1, $per_page = 20) {
            if($id_parent == null) {
                $sql = 'SELECT c1.*, c2.alias AS parent, l.num_languages FROM '.DDBB_PREFIX.'categories AS c1
                            LEFT JOIN '.DDBB_PREFIX.'categories AS c2 ON c1.id_parent = c2.id_category
                            LEFT JOIN (
                                SELECT COUNT(id_language) AS num_languages, id_category FROM '.DDBB_PREFIX.'categories_language GROUP BY id_category
                            ) AS l ON c1.id_category = l.id_category
                        WHERE c1.id_category != 1
                        ORDER BY c1.id_category';
                $result = $this->query($sql);
            } else {
                $sql = 'SELECT c1.*, c2.alias AS parent, l.num_languages FROM '.DDBB_PREFIX.'categories AS c1
                            LEFT JOIN '.DDBB_PREFIX.'categories AS c2 ON c1.id_parent = c2.id_category
                            LEFT JOIN (
                                SELECT COUNT(id_language) AS num_languages, id_category FROM '.DDBB_PREFIX.'categories_language GROUP BY id_category
                            ) AS l ON c1.id_category = l.id_category
                        WHERE c1.id_parent = ?
                        ORDER BY c1.id_category';
                $result = $this->query($sql, array($id_parent));
            }
            if($result->num_rows > 0) {
                $pager = $this->pager($result, $page, $per_page);
                // Painted table head
                $html = '<table>';
                $html .= '<thead>';
                $html .=    '<tr>';
                $html .=        '<th style="width: 150px;">Id category</th>';
                $html .=        '<th class="text-left">Name</th>';
                $html .=        '<th class="text-left">Parent category</th>';
                $html .=        '<th style="width: 100px;">Products</th>';
                $html .=        '<th style="width: 100px;">Languages</th>';
                $html .=        '<th style="width: 100px;">Visits</th>';
                $html .=        '<th style="width: 100px;">State</th>';
                $html .=        '<th style="width: 200px;"></th>';
                $html .=    '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                foreach($pager['result'] as $row) {
                    $sql = 'SELECT id_category FROM '.DDBB_PREFIX.'products_categories WHERE id_category = ?';
                    $result_products = $this->query($sql, array($row['id_category']));
                    if($row['id_parent'] == 0) {
                        $parent_html = '';
                    } else {
                        $parent_html = '<a href="'.ADMIN_PATH.'/categories?id_parent='.$row['id_parent'].'" class="text-underline">'.$row['parent'].'</a>';
                    }
                    $state = ($row['id_state'] == 2) ? '<span class="hive-color">Active</span>' : 'Disabled';
                    $html .= '<tr>';
                    $html .=    '<td class="text-center">'.$row['id_category'].'</td>';
                    $html .=    '<td>'.$row['alias'].'</td>';
                    $html .=    '<td>'.$parent_html.'</td>';
                    $html .=    '<td class="text-center">'.$result_products->num_rows.'</td>';
                    $html .=    '<td class="text-center">'.$row['num_languages'].'</td>';
                    $html .=    '<td class="text-center">'.$row['visits'].'</td>';
                    $html .=    '<td class="text-center">'.$state.'</td>';
                    $html .=    '<td class="text-center">';
                    $html .=        '<a href="edit-category?id_category='.$row['id_category'].'"class="btn btn-black btn-sm mr-5">Edit</a>';
                    $html .=        '<div class="btn btn-black btn-sm btn-delete-category" id-category="'.$row['id_category'].'">Delete</div>';
                    $html .=    '</td>';
                    $html .= '</tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
            } else {
                $html = 'No categories.';
            }
            return array(
                'html' => $html,
                'pager' => (isset($pager)) ? $pager['pager'] : ''
            );
        }

        public function get_category($id_category) {
            $sql = 'SELECT c.*, r.route FROM categories AS c
                        INNER JOIN categories_routes AS r ON r.id_category = c.id_category
                        INNER JOIN ct_languages AS l ON l.id_language = r.id_language
                    WHERE c.id_category = ? AND l.name = ? LIMIT 1';
            $result = $this->query($sql, array($id_category, strtolower(LANG)));
            if($result->num_rows != 0) {
                $row = $result->fetch_assoc();
                return $row;
            } else {
                return 'error';
            }
        }

        public function get_attributes($page = 1, $per_page = 20) {
            $sql = 'SELECT a.*, t.name AS type_name, h.name AS html_name, v.num_values, l.num_languages FROM '.DDBB_PREFIX.'attributes AS a
                        INNER JOIN '.DDBB_PREFIX.'ct_attributes_type AS t ON a.id_attribute_type = t.id_attribute_type
                        INNER JOIN '.DDBB_PREFIX.'ct_attributes_html AS h ON a.id_attribute_html = h.id_attribute_html
                        LEFT JOIN (
                            SELECT COUNT(id_attribute) AS num_values, id_attribute '.DDBB_PREFIX.'FROM attributes_value GROUP BY id_attribute
                        ) AS v ON a.id_attribute = v.id_attribute
                        LEFT JOIN (
                            SELECT COUNT(id_language) AS num_languages, id_attribute '.DDBB_PREFIX.'FROM attributes_language GROUP BY id_attribute
                        ) AS l ON a.id_attribute = l.id_attribute
                    ORDER BY a.id_attribute';
            $result = $this->query($sql);
            if($result->num_rows > 0) {
                $pager = $this->pager($result, $page, $per_page);
                // Painted table head
                $html = '<table>';
                $html .= '<thead>';
                $html .=    '<tr>';
                $html .=        '<th style="width: 150px;">Id attribute</th>';
                $html .=        '<th class="text-left">Name</th>';
                $html .=        '<th class="text-left" style="width: 120px;">Type</th>';
                $html .=        '<th class="text-left" style="width: 120px;">Html type</th>';
                $html .=        '<th style="width: 100px;">Values</th>';
                $html .=        '<th style="width: 100px;">Languages</th>';
                $html .=        '<th style="width: 200px;"></th>';
                $html .=    '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                foreach($pager['result'] as $row) {
                    $html .= '<tr>';
                    $html .=    '<td class="text-center">'.$row['id_attribute'].'</td>';
                    $html .=    '<td>'.$row['alias'].'</td>';
                    $html .=    '<td>'.$row['type_name'].'</td>';
                    $html .=    '<td>'.$row['html_name'].'</td>';
                    $html .=    '<td class="text-center">'.$row['num_values'].'</td>';
                    $html .=    '<td class="text-center">'.$row['num_languages'].'</td>';
                    $html .=    '<td class="text-center">';
                    $html .=        '<a href="edit-attribute?id_attribute='.$row['id_attribute'].'"class="btn btn-black btn-sm mr-5">Edit</a>';
                    $html .=        '<div class="btn btn-black btn-sm btn-delete-attribute" id-attribute="'.$row['id_attribute'].'">Delete</div>';
                    $html .=    '</td>';
                    $html .= '</tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
            } else {
                $html = 'No attributes.';
            }
            return array(
                'html' => $html,
                'pager' => (isset($pager)) ? $pager['pager'] : ''
            );
        }

        public function get_attribute($id_attribute) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'attributes WHERE id_attribute = ? LIMIT 1';
            $result = $this->query($sql, array($id_attribute));
            if($result->num_rows != 0) {
                $row = $result->fetch_assoc();
                return $row;
            } else {
                return 'error';
            }
        }

        public function get_images_table($page = 1, $per_page = 20) {
            $sql = 'SELECT i.*, CASE WHEN ISNULL(p.num_products) THEN 0 ELSE p.num_products END AS num_products FROM '.DDBB_PREFIX.'images AS i
                        LEFT JOIN (
                            SELECT COUNT(id_product) AS num_products, id_image FROM '.DDBB_PREFIX.'products_images GROUP BY id_image
                        ) AS p ON p.id_image = i.id_image
                    ORDER BY -i.id_image';
            $result = $this->query($sql);
            if($result->num_rows > 0) {
                $pager = $this->pager($result, $page, $per_page);
                // Painted table head
                $html = '<table>';
                $html .= '<thead>';
                $html .=    '<tr>';
                $html .=        '<th style="width: 120px;">Id image</th>';
                $html .=        '<th style="width: 100px;">Preview</th>';
                $html .=        '<th class="text-left">Url</th>';
                $html .=        '<th style="width: 100px;">Products</th>';
                $html .=        '<th style="width: 150px;"></th>';
                $html .=    '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                foreach($pager['result'] as $row) {
                    $html .= '<tr>';
                    $html .=    '<td class="text-center">'.$row['id_image'].'</td>';
                    $html .=    '<td class="px-30">';
                    $html .=        '<a href="'.PUBLIC_PATH.$row['url'].'" target="_blank">';
                    $html .=            '<img src="'.PUBLIC_PATH.'/img/products/thumbnails/'.$row['name'].'" style="width: 100%;">';
                    $html .=        '</a>';
                    $html .=    '</td>';
                    $html .=    '<td>'.$row['url'].'</td>';
                    $html .=    '<td class="text-center">'.$row['num_products'].'</td>';
                    $html .=    '<td class="text-center">';
                    $html .=        '<div class="btn btn-black btn-sm btn-delete-image" id-image="'.$row['id_image'].'">Delete</div>';
                    $html .=    '</td>';
                    $html .= '</tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
            } else {
                $html = 'No attributes.';
            }
            return array(
                'html' => $html,
                'pager' => (isset($pager)) ? $pager['pager'] : ''
            );
        }

        public function get_codes($page = 1, $per_page = 20) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'codes ORDER BY -id_code';
            $result = $this->query($sql);
            if($result->num_rows > 0) {
                $pager = $this->pager($result, $page, $per_page);
                // Painted table head
                $html = '<table>';
                $html .= '<thead>';
                $html .=    '<tr>';
                $html .=        '<th style="width: 150px;">Id Code</th>';
                $html .=        '<th class="text-left">Name</th>';
                $html .=        '<th class="text-left">Code</th>';
                $html .=        '<th style="width: 100px;">Type</th>';
                $html .=        '<th style="width: 100px;">Amount</th>';
                $html .=        '<th style="width: 120px;">Available</th>';
                $html .=        '<th style="width: 120px;">Status</th>';
                $html .=        '<th style="width: 200px;"></th>';
                $html .=    '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                foreach($pager['result'] as $row) {
                    $active = ($row['id_state'] == 2) ? '<span class="hive-color">Active</span>' : 'Disabled';
                    $html .= '<tr>';
                    $html .=    '<td class="text-center">'.$row['id_code'].'</td>';
                    $html .=    '<td>'.$row['name'].'</td>';
                    $html .=    '<td>'.$row['code'].'</td>';
                    $html .=    '<td class="text-center">'.$row['type'].'</td>';
                    $html .=    '<td class="text-center">'.$row['amount'].'</td>';
                    $html .=    '<td class="text-center">'.$row['available'].'</td>';
                    $html .=    '<td class="text-center">'.$active.'</td>';
                    $html .=    '<td class="text-center">';
                    $html .=        '<a href="edit-code?id_code='.$row['id_code'].'"class="btn btn-black btn-sm mr-5">Edit</a>';
                    $html .=        '<div class="btn btn-black btn-sm btn-delete-code" id-code="'.$row['id_code'].'">Delete</div>';
                    $html .=    '</td>';
                    $html .= '</tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
            } else {
                $html = 'No codes.';
            }
            return array(
                'html' => $html,
                'pager' => (isset($pager)) ? $pager['pager'] : ''
            );
        }

        public function get_code($id_code) {
            $sql = 'SELECT * FROM codes WHERE id_code = ? LIMIT 1';
            $result = $this->query($sql, array($id_code));
            if($result->num_rows != 0) {
                $row = $result->fetch_assoc();
                $row['amount'] = $this->parse_float_point_back($row['amount']);
                $row['minimum'] = $this->parse_float_point_back($row['minimum']);
                return $row;
            } else {
                return 'error';
            }
        }

        public function get_languages($page = 1, $per_page = 20) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'ct_languages';
            $result = $this->query($sql);
            if($result->num_rows > 0) {
                $pager = $this->pager($result, $page, $per_page);
                // Painted table head
                $html = '<table>';
                $html .= '<thead>';
                $html .=    '<tr>';
                $html .=        '<th style="width: 150px;">Id language</th>';
                $html .=        '<th style="width: 150px;">Name</th>';
                $html .=        '<th class="text-left" style="width: 100px;">Alias</th>';
                $html .=        '<th>Status</th>';
                $html .=        '<th style="width: 120px;"></th>';
                $html .=    '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                foreach($pager['result'] as $row) {
                    $active = ($row['id_state'] == 2) ? 'Active' : 'Disabled';
                    $html .= '<tr>';
                    $html .=    '<td class="text-center">'.$row['id_language'].'</td>';
                    $html .=    '<td class="text-center">'.$row['name'].'</td>';
                    $html .=    '<td>'.$row['alias'].'</td>';
                    $html .=    '<td class="text-center">'.$active.'</td>';
                    $html .=    '<td class="text-center">';
                    $html .=        '<a href="edit-language?id_language='.$row['id_language'].'"class="btn btn-black btn-sm">Edit</a>';
                    $html .=    '</td>';
                    $html .= '</tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
            } else {
                $html = 'No languages.';
            }
            return array(
                'html' => $html,
                'pager' => (isset($pager)) ? $pager['pager'] : ''
            );
        }

        public function get_language($id_language) {
            $sql = '';
            return 1;
        }

        public function get_users($page = 1, $per_page = 20) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'users ORDER BY -id_user';
            $result = $this->query($sql);
            if($result->num_rows > 0) {
                $pager = $this->pager($result, $page, $per_page);
                // Painted table head
                $html = '<table>';
                $html .= '<thead>';
                $html .=    '<tr>';
                $html .=        '<th style="width: 150px;">Id user</th>';
                $html .=        '<th class="text-left">Name</th>';
                $html .=        '<th class="text-left">Last Name</th>';
                $html .=        '<th class="text-left">Email</th>';
                $html .=        '<th style="width: 150px;">Last Access</th>';
                $html .=        '<th style="width: 100px;">Validated</th>';
                $html .=        '<th style="width: 200px;"></th>';
                $html .=    '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                foreach($pager['result'] as $row) {
                    $validated = ($row['validated_email'] == 0) ? 'No' : '<span class="hive-color">Yes</span>';
                    $html .= '<tr>';
                    $html .=    '<td class="text-center">'.$row['id_user'].'</td>';
                    $html .=    '<td>'.$row['name'].'</td>';
                    $html .=    '<td>'.$row['lastname'].'</td>';
                    $html .=    '<td>'.$row['email'].'</td>';
                    $html .=    '<td class="text-center">'.explode(' ', $row['last_access'])[0].'</td>';
                    $html .=    '<td class="text-center">'.$validated.'</td>';
                    $html .=    '<td class="text-center">';
                    $html .=        '<a href="edit-user?id_user='.$row['id_user'].'"class="btn btn-black btn-sm mr-5">Edit</a>';
                    $html .=        '<div class="btn btn-black btn-sm btn-delete-user" id-user="'.$row['id_user'].'">Delete</div>';
                    $html .=    '</td>';
                    $html .= '</tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
            } else {
                $html = 'No users.';
            }
            return array(
                'html' => $html,
                'pager' => (isset($pager)) ? $pager['pager'] : ''
            );
        }

        public function get_user($id_user) {
            $sql = 'SELECT * FROM users WHERE id_user = ? LIMIT 1';
            $result = $this->query($sql, array($id_user));
            if($result->num_rows != 0) {
                $row = $result->fetch_assoc();
                return $row;
            } else {
                return 'error';
            }
        }

        public function get_users_admin($page = 1, $per_page = 20) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'users_admin ORDER BY -id_admin';
            $result = $this->query($sql);
            if($result->num_rows > 0) {
                $pager = $this->pager($result, $page, $per_page);
                // Painted table head
                $html = '<table>';
                $html .= '<thead>';
                $html .=    '<tr>';
                $html .=        '<th style="width: 150px;">Id admin</th>';
                $html .=        '<th class="text-left">Name</th>';
                $html .=        '<th class="text-left">Email</th>';
                $html .=        '<th style="width: 100px;">Type</th>';
                $html .=        '<th style="width: 150px;">Last Access</th>';
                $html .=        '<th style="width: 100px;">Status</th>';
                $html .=        '<th style="width: 200px;"></th>';
                $html .=    '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                foreach($pager['result'] as $row) {
                    $active = ($row['id_state'] == 2) ? 'Active' : 'Disabled';
                    $html .= '<tr>';
                    $html .=    '<td class="text-center">'.$row['id_admin'].'</td>';
                    $html .=    '<td>'.$row['name'].'</td>';
                    $html .=    '<td>'.$row['email'].'</td>';
                    $html .=    '<td class="text-center">'.$row['id_admin_type'].'</td>';
                    $html .=    '<td class="text-center">'.explode(' ', $row['last_access'])[0].'</td>';
                    $html .=    '<td class="text-center">'.$active.'</td>';
                    $html .=    '<td class="text-center">';
                    $html .=        '<a href="edit-user-admin?id_admin='.$row['id_admin'].'"class="btn btn-black btn-sm mr-5">Edit</a>';
                    $html .=        '<div class="btn btn-black btn-sm btn-delete-admin" id-admin="'.$row['id_admin'].'">Delete</div>';
                    $html .=    '</td>';
                    $html .= '</tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
            } else {
                $html = 'No admin users.';
            }
            return array(
                'html' => $html,
                'pager' => (isset($pager)) ? $pager['pager'] : ''
            );
        }

        public function get_product_views_list($id_product_view = 1) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'products_views ORDER BY id_product_view';
            $result = $this->query($sql);
            $html = '';
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $selected = '';
                    // If it is the view you have selected
                    if($id_product_view == $row['id_product_view']) {
                        $selected = ' selected';
                    }
                    $html .= '<option value="'.$row['id_product_view'].'"'.$selected.'>'.$row['name'].'</option>';
                }
            }
            return $html;
        }

        public function get_categories_list($id_categoria = null) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'categories ORDER BY id_category';
            $result = $this->query($sql);
            $html = '';
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $selected = '';
                    // If it is the view you have selected
                    if($id_categoria == $row['id_category']) {
                        $selected = ' selected';
                    }
                    $html .= '<option value="'.$row['id_category'].'"'.$selected.'>'.$row['id_category'].' - '.$row['alias'].'</option>';
                }
            }
            return $html;
        }

        public function get_category_views_list($id_category_view = 1) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'categories_views ORDER BY id_category_view';
            $result = $this->query($sql);
            $html = '';
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $selected = '';
                    // If it is the view you have selected
                    if($id_category_view == $row['id_category_view']) {
                        $selected = ' selected';
                    }
                    $html .= '<option value="'.$row['id_category_view'].'"'.$selected.'>'.$row['name'].'</option>';
                }
            }
            return $html;
        }

        public function get_categories_custom_list($id_product = null) {
            // If I pass a product I collect its categories
            $categories = [];
            if($id_product != null) {
                $sql = 'SELECT * FROM '.DDBB_PREFIX.'products_categories WHERE id_product = '.$id_product;
                $result = $this->query($sql);
                while($row = $result->fetch_assoc()) {
                    array_push($categories, array('id_category' => $row['id_category'], 'main' => $row['main']));
                }
            }
            // Now I paint all the categories
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'categories WHERE id_parent = 0 AND id_state = 2';
            $result = $this->query($sql);
            $html = '';
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $class = '';
                    foreach($categories as $i => $value) {
                        if($value['id_category'] == $row['id_category']) {
                            $class = ' active';
                            if($value['main'] == 1) {
                                $class .= ' main';
                            }
                        }
                    }
                    $html .= '<div class="list-item'.$class.'" value="'.$row['id_category'].'">'.$row['alias'].'</div>';
                    // I check if he has children
                    $html .= $this->get_catergory_childs($row['id_category'], 1, $categories);
                }
            }
            return $html;
        }

        public function get_catergory_childs($id_category, $level, $categories = []) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'categories WHERE id_parent = ? AND id_state = 2';
            $result = $this->query($sql, array($id_category));
            $html = '';
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $alias = '';
                    for($i = 0; $i < $level; $i++) {
                        $alias .= '&nbsp;&nbsp;';
                    }
                    $alias .= '<i class="fa-solid fa-caret-right"></i>&nbsp;'.$row['alias'];
                    $class = '';
                    foreach($categories as $i => $value) {
                        if($value['id_category'] == $row['id_category']) {
                            $class = ' active';
                            if($value['main'] == 1) {
                                $class .= ' main';
                            }
                        }
                    }
                    $html .= '<div class="list-item'.$class.'" value="'.$row['id_category'].'">'.$alias.'</div>';
                    // I check if he has children
                    $html .= $this->get_catergory_childs($row['id_category'], ($level + 1), $categories);
                }    
            }
            return $html;
        }

        public function get_attributes_list($id_product = null) {
            // If I pass a product I collect its attributes
            $attributes = [];
            if($id_product != null) {
                $sql = 'SELECT * FROM '.DDBB_PREFIX.'products_attributes WHERE id_product = ?';
                $result = $this->query($sql, array($id_product));
                while($row = $result->fetch_assoc()) {
                    array_push($attributes, $row['id_attribute']);
                }
            }
            // Now I paint all the attributes
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'attributes';
            $result = $this->query($sql);
            $html = '';
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $selected = '';
                    if(in_array($row['id_attribute'], $attributes)) {
                        $selected = ' active';
                    }
                    $html .= '<div class="list-item'.$selected.'" value="'.$row['id_attribute'].'">'.$row['alias'].'</div>';
                }
            }
            return $html;
        }

        public function get_attributes_list_product($id_product) {
            $sql = 'SELECT p.*, a.alias FROM '.DDBB_PREFIX.'products_attributes AS p
                        INNER JOIN '.DDBB_PREFIX.'attributes AS a ON a.id_attribute = p.id_attribute
                    WHERE id_product = ? ORDER BY priority';
            $result = $this->query($sql, array($id_product));
            $html = '';
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $html .= '<div class="list-item no-hover" value="'.$row['id_attribute'].'">'.$row['alias'].'</div>';
                }
            }
            return $html;
        }

        public function get_code_types_list($id_type = 1) {
            $sql = 'SELECT * FROM ct_codes_type';
            $result = $this->query($sql);
            $html = '';
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $selected = '';
                    // If it is the type you have selected
                    if($id_type == $row['id_ct_codes_type']) {
                        $selected = ' selected';
                    }
                    $html .= '<option value="'.$row['id_ct_codes_type'].'"'.$selected.'>'.$row['name'].'</option>';
                }
            }
            return $html;
        }

        public function get_languages_array() {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'ct_languages WHERE id_state = 2 ORDER BY id_language';
            $result = $this->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function get_languages_product($id_product) {
            $sql = 'SELECT p.*, l.alias AS alias FROM '.DDBB_PREFIX.'products_language AS p
                        INNER JOIN '.DDBB_PREFIX.'ct_languages AS l ON l.id_language = p.id_language
                    WHERE p.id_product = ? ORDER BY id_language';
            $result = $this->query($sql, array($id_product));
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function get_languages_category($id_category) {
            $sql = 'SELECT c.*, l.alias AS alias FROM '.DDBB_PREFIX.'categories_language AS c
                        INNER JOIN '.DDBB_PREFIX.'ct_languages AS l ON l.id_language = c.id_language
                    WHERE c.id_category = ? ORDER BY id_language';
            $result = $this->query($sql, array($id_category));
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function get_attribute_types_list($id_attribute_type = null) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'ct_attributes_type ORDER BY id_attribute_type';
            $result = $this->query($sql);
            $html = '';
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $selected = '';
                    // If it is the type you have selected
                    if($id_attribute_type == $row['id_attribute_type']) {
                        $selected = ' selected';
                    }
                    $html .= '<option value="'.$row['id_attribute_type'].'"'.$selected.'>'.$row['name'].'</option>';
                }
            }
            return $html;
        }

        public function get_attribute_html_list($id_attribute_html = null) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'ct_attributes_html ORDER BY id_attribute_html';
            $result = $this->query($sql);
            $html = '';
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $selected = '';
                    // If it is the type you have selected
                    if($id_attribute_html == $row['id_attribute_html']) {
                        $selected = ' selected';
                    }
                    $html .= '<option value="'.$row['id_attribute_html'].'"'.$selected.'>'.$row['name'].'</option>';
                }
            }
            return $html;
        }

        public function get_languages_attribute($id_attribute) {
            $sql = 'SELECT a.*, l.alias AS alias FROM '.DDBB_PREFIX.'attributes_language AS a
                        INNER JOIN '.DDBB_PREFIX.'ct_languages AS l ON l.id_language = a.id_language
                    WHERE a.id_attribute = ? ORDER BY id_language';
            $result = $this->query($sql, array($id_attribute));
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function get_admin_type_list($id_admin_type = null) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'users_admin_type ORDER BY id_admin_type';
            $result = $this->query($sql);
            $html = '';
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $selected = '';
                    // If it is the type you have selected
                    if($id_admin_type == $row['id_admin_type']) {
                        $selected = ' selected';
                    }
                    $html .= '<option value="'.$row['id_admin_type'].'"'.$selected.'>'.$row['name'].'</option>';
                }
            }
            return $html;
        }

        public function get_admin_user($id_admin) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'users_admin WHERE id_admin = ? LIMIT 1';
            $result = $this->query($sql, array($id_admin));
            if($result->num_rows != 0) {
                $row = $result->fetch_assoc();
                return $row;
            } else {
                return 'error';
            }
        }

        public function get_products_custom_routes($page = 1, $per_page = 20) {
            $sql = 'SELECT r.*, l.name AS language_name FROM '.DDBB_PREFIX.'products_custom_routes AS r
                        INNER JOIN '.DDBB_PREFIX.'ct_languages AS l ON l.id_language = r.id_language
                    ORDER BY -r.id_product_custom_route';
            $result = $this->query($sql);
            if($result->num_rows != 0) {
                $pager = $this->pager($result, $page, $per_page);
                // Painted table head
                $html = '<table>';
                $html .= '<thead>';
                $html .=    '<tr>';
                $html .=        '<th style="width: 120px;">Id route</th>';
                $html .=        '<th style="width: 120px;">Id product</th>';
                $html .=        '<th style="width: 120px;">Id category</th>';
                $html .=        '<th style="width: 120px;">Language</th>';
                $html .=        '<th class="text-left">Route</th>';
                $html .=        '<th style="width: 200px;"></th>';
                $html .=    '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                foreach($pager['result'] as $row) {
                    $link = PUBLIC_PATH.'/'.$row['language_name'].$row['route'];
                    $html .= '<tr>';
                    $html .=    '<td class="text-center">'.$row['id_product_custom_route'].'</td>';
                    $html .=    '<td class="text-center">'.$row['id_product'].'</td>';
                    $html .=    '<td class="text-center">'.$row['id_category'].'</td>';
                    $html .=    '<td class="text-center">'.$row['language_name'].'</td>';
                    $html .=    '<td>'.$row['route'].'</td>';
                    $html .=    '<td>';
                    $html .=        '<a href="'.$link.'" class="btn btn-black btn-sm mr-5" target="_blank">View</a>';
                    $html .=        '<div class="btn btn-black btn-sm btn-delete-product-custom-route" id-product-custom-route="'.$row['id_product_custom_route'].'">Delete</div>';
                    $html .=    '</td>';
                    $html .= '</tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
            } else {
                $html = 'No products custom routes.';
            }
            return array(
                'html' => $html,
                'pager' => (isset($pager)) ? $pager['pager'] : ''
            );
        }

        public function get_categories_custom_routes($page = 1, $per_page = 20) {
            $sql = 'SELECT r.*, l.name AS language_name FROM '.DDBB_PREFIX.'categories_custom_routes AS r
                        INNER JOIN '.DDBB_PREFIX.'ct_languages AS l ON l.id_language = r.id_language
                    ORDER BY -r.id_category_custom_route';
            $result = $this->query($sql);
            if($result->num_rows != 0) {
                $pager = $this->pager($result, $page, $per_page);
                // Painted table head
                $html = '<table>';
                $html .= '<thead>';
                $html .=    '<tr>';
                $html .=        '<th style="width: 120px;">Id route</th>';
                $html .=        '<th style="width: 120px;">Id category</th>';
                $html .=        '<th style="width: 120px;">Language</th>';
                $html .=        '<th class="text-left">Route</th>';
                $html .=        '<th style="width: 200px;"></th>';
                $html .=    '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                foreach($pager['result'] as $row) {
                    $link = PUBLIC_PATH.'/'.$row['language_name'].$row['route'];
                    $html .= '<tr>';
                    $html .=    '<td class="text-center">'.$row['id_category_custom_route'].'</td>';
                    $html .=    '<td class="text-center">'.$row['id_category'].'</td>';
                    $html .=    '<td class="text-center">'.$row['language_name'].'</td>';
                    $html .=    '<td>'.$row['route'].'</td>';
                    $html .=    '<td>';
                    $html .=        '<a href="'.$link.'" class="btn btn-black btn-sm mr-5" target="_blank">View</a>';
                    $html .=        '<div class="btn btn-black btn-sm btn-delete-category-custom-route" id-category-custom-route="'.$row['id_category_custom_route'].'">Delete</div>';
                    $html .=    '</td>';
                    $html .= '</tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
            } else {
                $html = 'No categories custom routes.';
            }
            return array(
                'html' => $html,
                'pager' => (isset($pager)) ? $pager['pager'] : ''
            );
        }

        public function get_stats() {
            $stats = array();
            // Products most view
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'products ORDER BY -visits LIMIT 10';
            $result = $this->query($sql);
            $html = '<table>';
            $html .= '<thead>';
            $html .=    '<tr>';
            $html .=        '<th style="width: 80px;">Id</th>';
            $html .=        '<th class="text-left">Alias</th>';
            $html .=        '<th style="width: 80px;">Visits</th>';
            $html .=        '<th style="width: 80px;"></th>';
            $html .=    '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            for($i = 0; $i < 10; $i++) {
                $html .= '<tr>';
                if($row = $result->fetch_assoc()) {
                    $html .= '<td class="text-center">'.$row['id_product'].'</td>';
                    $html .= '<td>'.$row['alias'].'</td>';
                    $html .= '<td class="text-center">'.$row['visits'].'</td>';
                    $html .= '<td class="text-center">';
                    $html .=    '<a href="'.ADMIN_PATH.'/edit-product?id_product='.$row['id_product'].'" class="btn btn-black btn-sm">Edit</a>';
                    $html .= '</td>';
                } else {
                    $html .= '<td>&nbsp;</td>';
                    $html .= '<td>&nbsp;</td>';
                    $html .= '<td>&nbsp;</td>';
                    $html .= '<td>&nbsp;</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';
            $stats['products_most_view'] = $html;
            return $stats;
        }

        public function carts($page = 1, $per_page = 20) {
            $sql = 'SELECT c.*,
                        CASE WHEN c.id_user = 0 THEN "-" ELSE u.email END AS email_user
                    FROM '.DDBB_PREFIX.'carts AS c
                        LEFT JOIN '.DDBB_PREFIX.'users AS u ON u.id_user = c.id_user
                    GROUP BY c.id_cart ORDER BY -c.id';
            $result = $this->query($sql);
            if($result->num_rows != 0) {
                $pager = $this->pager($result, $page, $per_page);
                // Painted table head
                $html = '<table>';
                $html .= '<thead>';
                $html .=    '<tr>';
                $html .=        '<th style="width: 160px;">Id cart</th>';
                $html .=        '<th style="width: 100px;">Products</th>';
                $html .=        '<th style="width: 150px;">Amount</th>';
                $html .=        '<th class="text-left" style="width: 250px;">Insert date</th>';
                $html .=        '<th class="text-left">User</th>';
                $html .=        '<th style="width: 120px;"></th>';
                $html .=    '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                foreach($pager['result'] as $row) {
                    $sql = 'SELECT c.*, p.price, r.price_change FROM '.DDBB_PREFIX.'carts_products AS c
                                INNER JOIN '.DDBB_PREFIX.'products AS p ON p.id_product = c.id_product
                                INNER JOIN '.DDBB_PREFIX.'products_related AS r ON r.id_product_related = c.id_product_related
                            WHERE c.id_cart = ?';
                    $result_cart = $this->query($sql, array($row['id_cart']));
                    $total_products = 0;
                    $total_price = 0;
                    while($row_cart = $result_cart->fetch_assoc()) {
                        $total_products += $row_cart['amount'];
                        $total_price += ($row_cart['price'] + $row_cart['price_change']) * $row_cart['amount'];
                    }
                    $html .= '<tr>';
                    $html .=    '<td class="text-center">'.$row['id_cart'].'</td>';
                    $html .=    '<td class="text-center">'.$total_products.'</td>';
                    $html .=    '<td class="text-center">'.number_format(floatval($total_price), 2, ',', '.').' €</td>';
                    $html .=    '<td>'.$row['insert_date'].'</td>';
                    $html .=    '<td>'.$row['email_user'].'</td>';
                    $html .=    '<td class="text-center">';
                    $html .=        '<a href="view-cart?id_cart='.$row['id_cart'].'" class="btn btn-black btn-sm">View</a>';
                    $html .=    '</td>';
                    $html .= '</tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
            } else {
                $html = 'No carts.';
            }
            return array(
                'html' => $html,
                'pager' => (isset($pager)) ? $pager['pager'] : ''
            );
        }

        public function get_cart($id_cart) {
            // I paint the list of products in the cart
            $sql = 'SELECT c.*, p.price, p.alias AS alias_product, i.url AS url_image
                    FROM '.DDBB_PREFIX.'carts_products AS c
                        INNER JOIN '.DDBB_PREFIX.'products AS p ON p.id_product = c.id_product
                        INNER JOIN '.DDBB_PREFIX.'products_images AS ip ON ip.id_product_image = p.main_image
                        INNER JOIN '.DDBB_PREFIX.'images AS i ON i.id_image = ip.id_image
                    WHERE c.id_cart = ?';
            $result = $this->query($sql, array($id_cart));
            if($result->num_rows != 0) {
                $html = '';
                while($row = $result->fetch_assoc()) {
                    $html .= '<div class="row item">';
                    $html .=    '<div class="col-4">';
                    $html .=        '<a href="#" class="image" style="background-image: url('.PUBLIC_PATH.$row['url_image'].');"></a>';
                    $html .=    '</div>';
                    $html .=    '<div class="col-4">';
                    $html .=    '</div>';
                    $html .= '</div>';
                }
            } else {
                $html = 'There are no products in the cart.';
            }
            return array(
                'html_products' => $html
            );
        }

        public function get_orders($page = 1, $per_page = 20) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'orders ORDER BY -id_order';
            $result = $this->query($sql);
            if($result->num_rows != 0) {
                $pager = $this->pager($result, $page, $per_page);
                // Painted table head
                $html = '<table>';
                $html .= '<thead>';
                $html .=    '<tr>';
                $html .=        '<th style="width: 160px;">Id order</th>';
                $html .=        '<th style="width: 100px;">Products</th>';
                $html .=        '<th style="width: 150px;">Amount</th>';
                $html .=        '<th class="text-left" style="width: 250px;">Insert date</th>';
                $html .=        '<th class="text-left">User</th>';
                $html .=        '<th style="width: 120px;"></th>';
                $html .=    '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                foreach($pager['result'] as $row) {
                    $html .= '<tr>';
                    $html .=    '<td class="text-center">'.$row['id_order'].'</td>';
                    $html .= '</tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
            } else {
                $html = 'No orders.';
            }
            return array(
                'html' => $html,
                'pager' => (isset($pager)) ? $pager['pager'] : ''
            );
        }

        public function get_order($id_order) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'orders WHERE id_order = ? LIMIT 1';
            $result = $this->query($sql, array($id_order));
            if($result->num_rows != 0) {
                return 1;
            } else {
                return 'error';
            }
        }

        public function get_shipping_methods($page = 1, $per_page = 20) {
            $sql = 'SELECT m.*, s.name AS state_name FROM '.DDBB_PREFIX.'shipping_methods AS m
                        INNER JOIN '.DDBB_PREFIX.'ct_states AS s ON s.id_state = m.id_state
                    ORDER BY -m.id_shipping_method';
            $result = $this->query($sql);
            if($result->num_rows != 0) {
                $pager = $this->pager($result, $page, $per_page);
                // Painted table head
                $html = '<table>';
                $html .= '<thead>';
                $html .=    '<tr>';
                $html .=        '<th style="width: 160px;">Id</th>';
                $html .=        '<th class="text-left">Alias</th>';
                $html .=        '<th style="width: 120px;">Min price</th>';
                $html .=        '<th style="width: 120px;">Max price</th>';
                $html .=        '<th style="width: 120px;">Min weight</th>';
                $html .=        '<th style="width: 120px;">Max weight</th>';
                $html .=        '<th style="width: 150px;">State</th>';
                $html .=        '<th style="width: 120px;"></th>';
                $html .=    '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                foreach($pager['result'] as $row) {
                    $html .= '<tr>';
                    $html .=    '<td class="text-center">'.$row['id_shipping_method'].'</td>';
                    $html .=    '<td>'.$row['alias'].'</td>';
                    $html .=    '<td class="text-center">'.$row['min_order_value'].'</td>';
                    $html .=    '<td class="text-center">'.$row['max_order_value'].'</td>';
                    $html .=    '<td class="text-center">'.$row['min_order_weight'].'</td>';
                    $html .=    '<td class="text-center">'.$row['max_order_weight'].'</td>';
                    $html .=    '<td class="text-center">'.$row['state_name'].'</td>';
                    $html .=    '<td class="text-center">';
                    $html .=        '<a href="edit-shipping-method?id_shipping_method='.$row['id_shipping_method'].'" class="btn btn-black btn-sm">Edit</a>';
                    $html .=    '</td>';
                    $html .= '</tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
            } else {
                $html = 'No shipping methods.';
            }
            return array(
                'html' => $html,
                'pager' => (isset($pager)) ? $pager['pager'] : ''
            );
        }

        public function get_shipping_method($id_shipping_method) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'shipping_methods WHERE id_shipping_method = ? LIMIT 1';
            $result = $this->query($sql, $id_shipping_method);
            if($result->num_rows != 0) {
                $row = $result->fetch_assoc();
                return $row;
            } else {
                return 'error';
            }
        }

        public function get_languages_shipment($id_shipping_method) {
            $sql = 'SELECT s.*, l.alias AS alias FROM '.DDBB_PREFIX.'shipping_methods_language AS s
                        INNER JOIN '.DDBB_PREFIX.'ct_languages AS l ON l.id_language = s.id_language
                    WHERE s.id_shipping_method = ? ORDER BY id_language';
            $result = $this->query($sql, $id_shipping_method);
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function get_shipping_zones($page = 1, $per_page = 20) {
            $sql = 'SELECT m.*, s.name AS state_name FROM '.DDBB_PREFIX.'shipping_zones AS m
                        INNER JOIN '.DDBB_PREFIX.'ct_states AS s ON s.id_state = m.id_state
                    ORDER BY -m.id_shipping_zone';
            $result = $this->query($sql);
            if($result->num_rows != 0) {
                $pager = $this->pager($result, $page, $per_page);
                // Painted table head
                $html = '<table>';
                $html .= '<thead>';
                $html .=    '<tr>';
                $html .=        '<th style="width: 160px;">Id</th>';
                $html .=        '<th class="text-left">Name</th>';
                $html .=        '<th style="width: 150px;">Countries</th>';
                $html .=        '<th style="width: 150px;">State</th>';
                $html .=        '<th style="width: 120px;"></th>';
                $html .=    '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                foreach($pager['result'] as $row) {
                    // I collect the number of countries that this area covers
                    $sql = 'SELECT id_shipping_zone FROM shipping_zone_countries WHERE id_shipping_zone = ?';
                    $result_countries = $this->query($sql, $row['id_shipping_zone']);
                    $html .= '<tr>';
                    $html .=    '<td class="text-center">'.$row['id_shipping_zone'].'</td>';
                    $html .=    '<td>'.$row['name'].'</td>';
                    $html .=    '<td class="text-center">'.$result_countries->num_rows.'</td>';
                    $html .=    '<td class="text-center">'.$row['state_name'].'</td>';
                    $html .=    '<td class="text-center">';
                    $html .=        '<a href="edit-shipping-zone?id_shipping_zone='.$row['id_shipping_zone'].'" class="btn btn-black btn-sm">Edit</a>';
                    $html .=    '</td>';
                    $html .= '</tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
            } else {
                $html = 'No shipping zones.';
            }
            return array(
                'html' => $html,
                'pager' => (isset($pager)) ? $pager['pager'] : ''
            );
        }

        public function get_shipping_zone($id_shipping_zone) {
            $sql = 'SELECT * FROM shipping_zones WHERE id_shipping_zone = ? LIMIT 1';
            $result = $this->query($sql, $id_shipping_zone);
            if($result->num_rows != 0) {
                $row = $result->fetch_assoc();
                return $row;
            } else {
                return 'error';
            }
        }

        public function get_shipping_zone_continents($id_shipping_zone) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'shipping_zone_continents WHERE id_shipping_zone = ?';
            $result = $this->query($sql, $id_shipping_zone);
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'ct_continents WHERE id_state = 2 ORDER BY id_continent';
            $result_continents = $this->query($sql);
            if($result_continents->num_rows != 0) {
                $html = '<table>';
                $html .= '<tbody>';
                while($row_continent = $result_continents->fetch_assoc()) {
                    $checked = '';
                    while($row = $result->fetch_assoc()) {
                        if($row_continent['id_continent'] == $row['id_continent']) {
                            $checked = ' checked';
                        }
                    }
                    $result->data_seek(0);
                    $html .= '<tr>';
                    $html .=    '<td>';
                    $html .=        '<label class="checkbox">';
                    $html .=            '<input type="checkbox" value="'.$row_continent['id_continent'].'"'.$checked.'>';
                    $html .=            '<span class="checkmark"></span>'.$row_continent['en'];
                    $html .=        '</label>';
                    $html .=    '</td>';
                    $html .= '</tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
            } else {
                $html = 'No continents found.';
            }
            return $html;
        }

        public function get_payment_methods($page = 1, $per_page = 20) {
            $sql = 'SELECT m.*, s.name AS state_name FROM '.DDBB_PREFIX.'payment_methods AS m
                        INNER JOIN '.DDBB_PREFIX.'ct_states AS s ON s.id_state = m.id_state
                    ORDER BY -m.id_payment_method';
            $result = $this->query($sql);
            if($result->num_rows != 0) {
                $pager = $this->pager($result, $page, $per_page);
                // Painted table head
                $html = '<table>';
                $html .= '<thead>';
                $html .=    '<tr>';
                $html .=        '<th style="width: 160px;">Id</th>';
                $html .=        '<th class="text-left">Alias</th>';
                $html .=        '<th style="width: 120px;">Min price</th>';
                $html .=        '<th style="width: 120px;">Max price</th>';
                $html .=        '<th style="width: 150px;">State</th>';
                $html .=        '<th style="width: 120px;"></th>';
                $html .=    '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                foreach($pager['result'] as $row) {
                    $html .= '<tr>';
                    $html .=    '<td class="text-center">'.$row['id_payment_method'].'</td>';
                    $html .=    '<td>'.$row['alias'].'</td>';
                    $html .=    '<td class="text-center">'.$row['min_order_value'].'</td>';
                    $html .=    '<td class="text-center">'.$row['max_order_value'].'</td>';
                    $html .=    '<td class="text-center">'.$row['state_name'].'</td>';
                    $html .=    '<td class="text-center">';
                    $html .=        '<a href="edit-payment-method?id_payment_method='.$row['id_payment_method'].'" class="btn btn-black btn-sm">Edit</a>';
                    $html .=    '</td>';
                    $html .= '</tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
            } else {
                $html = 'No payment methods.';
            }
            return array(
                'html' => $html,
                'pager' => (isset($pager)) ? $pager['pager'] : ''
            );
        }
        
        public function get_payment_method($id_payment_method) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'payment_methods WHERE id_payment_method = ? LIMIT 1';
            $result = $this->query($sql, $id_payment_method);
            if($result->num_rows != 0) {
                $row = $result->fetch_assoc();
                return $row;
            } else {
                return 'error';
            }
        }

        public function get_languages_payment($id_payment_method) {
            $sql = 'SELECT p.*, l.alias AS alias FROM '.DDBB_PREFIX.'payment_methods_language AS p
                        INNER JOIN '.DDBB_PREFIX.'ct_languages AS l ON l.id_language = p.id_language
                    WHERE p.id_payment_method = ? ORDER BY id_language';
            $result = $this->query($sql, $id_payment_method);
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function get_payment_zones($page = 1, $per_page = 20) {
            $sql = 'SELECT z.*, s.name AS state_name FROM '.DDBB_PREFIX.'payment_zones AS z
                        INNER JOIN '.DDBB_PREFIX.'ct_states AS s ON s.id_state = z.id_state
                    ORDER BY -z.id_payment_zone';
            $result = $this->query($sql);
            if($result->num_rows != 0) {
                $pager = $this->pager($result, $page, $per_page);
                // Painted table head
                $html = '<table>';
                $html .= '<thead>';
                $html .=    '<tr>';
                $html .=        '<th style="width: 160px;">Id</th>';
                $html .=        '<th class="text-left">Name</th>';
                $html .=        '<th style="width: 120px;">Countries</th>';
                $html .=        '<th style="width: 150px;">State</th>';
                $html .=        '<th style="width: 120px;"></th>';
                $html .=    '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                foreach($pager['result'] as $row) {
                    // I collect the number of countries that this area covers
                    $sql = 'SELECT id_payment_zone FROM payment_zone_countries WHERE id_payment_zone = ?';
                    $result_countries = $this->query($sql, $row['id_payment_zone']);
                    $html .= '<tr>';
                    $html .=    '<td class="text-center">'.$row['id_payment_zone'].'</td>';
                    $html .=    '<td>'.$row['name'].'</td>';
                    $html .=    '<td class="text-center">'.$result_countries->num_rows.'</td>';
                    $html .=    '<td class="text-center">'.$row['state_name'].'</td>';
                    $html .=    '<td class="text-center">';
                    $html .=        '<a href="edit-payment-zone?id_payment_zone='.$row['id_payment_zone'].'" class="btn btn-black btn-sm">Edit</a>';
                    $html .=    '</td>';
                    $html .= '</tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
            } else {
                $html = 'No payment methods.';
            }
            return array(
                'html' => $html,
                'pager' => (isset($pager)) ? $pager['pager'] : ''
            );
        }

        public function get_payment_zone($id_payment_zone) {
            $sql = 'SELECT * FROM payment_zones WHERE id_payment_zone = ? LIMIT 1';
            $result = $this->query($sql, $id_payment_zone);
            if($result->num_rows != 0) {
                $row = $result->fetch_assoc();
                return $row;
            } else {
                return 'error';
            }
        }

    }

?>