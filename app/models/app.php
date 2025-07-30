<?php

    /**
     * @author Diego Martín
     * @copyright Hive®
     * @version 1.0
     * @lastUpdated 2025
     */

    class App extends AppModel {

        public $name_page;

        function __construct($name_page = 'default-page') {
            parent::__construct();
            $this->name_page = $name_page;
            $this->check_maintenance();
            $this->set_cart();
            $this->set_cart_items();
            $this->set_location();
            $this->login_remember();
        }

        public function getAppData() {
            // Declare here the variables that you are going to use in several different views
            $data = array();
            $data['app'] = array(
                'name_page' => $this->name_page,
                'tags' => array()
            );
            $data['head'] = array(
                'application-name' => 'Hive',
                'author' => 'Diego Martín',
                'robots' => 'index, follow',
                'canonical' => URL_ROUTE
            );
            $data['meta'] = array(
                'title' => META_TITLE,
                'description' => META_DESCRIPTION,
                'keywords' => META_KEYS
            );
            $data['og'] = array(
                'og:title' => OG_TITLE,
                'og:site_name' => OG_SITE_NAME,
                'og:description' => OG_DESCRIPTION,
                'og:type' => OG_TYPE,
                'og:url' => OG_URL,
                'og:image' => OG_IMAGE,
                'og:locale' => LANG,
                'fb:app_id' => OG_APP_ID
            );
            return $data;
        }

        private function set_cart() {
            // If you don't have the id_cart cookie, I create one
			if(!(isset($_COOKIE["id_cart"]))) {
                // I create a random value for the cart id
                $id_cart = uniqid().'-'.rand(1000, 9999);
                Utils::initCookie('id_cart', $id_cart, Utils::ONEYEAR);
                // If logged in, save the user id in the cart.
                if(isset($_SESSION['user'])) {
                    $id_user = $_SESSION['user']['id_user'];
                } else {
                    $id_user = 0;
                }
                $sql = 'INSERT INTO '.DDBB_PREFIX.'carts (id_cart, id_user) VALUES (?, ?)';
                $this->query($sql, array($_COOKIE['id_cart'], $id_user));
			}
        }

        private function set_cart_items() {
            // I check how many products you have in your cart
            $sql = 'SELECT SUM(amount) AS num_items FROM '.DDBB_PREFIX.'carts_products WHERE id_cart = ?';
            $result = $this->query($sql, array($_COOKIE["id_cart"]));
            if($result->num_rows != 0) {
                $row = $result->fetch_assoc();
                $_SESSION["cart_items"] = $row['num_items'];
            }
        }

        private function set_location() {
            if(!isset($_COOKIE['location'])) {
                $_COOKIE['location'] = array(
                    'id_continent' => LOCATION_CONTINENT,
                    'id_country' => LOCATION_COUNTRY,
                    'id_province' => LOCATION_PROVINCE
                );
            }
        }

        private function login_remember() {
			if(isset($_COOKIE["user_remember"])) {
                if(!isset($_SESSION['user'])) {
                    $sql = 'SELECT email, pass FROM '.DDBB_PREFIX.'users WHERE remember_code = ? AND id_state = 2 LIMIT 1';
                    $result = $this->query($sql, array($_COOKIE['user_remember']));
                    if($result->num_rows != 0) {
                        $row = $result->fetch_assoc();
                        $this->login($row['email'], $row['pass'], 1);
                    } else {
                        Utils::killCookie('user_remember');
                    }
                } else {
                    // If the remember code does not match it is because the user has been kicked out
                    $sql = 'SELECT id_user FROM '.DDBB_PREFIX.'users WHERE id_user = ? AND remember_code = ? LIMIT 1';
                    $result = $this->query($sql, array($_SESSION['user']['id_user'], $_COOKIE["user_remember"]));
                    if($result->num_rows == 0) {
                        $this->logout();
                        Utils::redirect('/');
                        exit;
                    }
                }
            }            
        }

        public function logout() {
            unset($_SESSION['user']);
            Utils::killCookie('user_remember');
        }

        public function security_my_account() {
            if(!isset($_SESSION['user'])) {
                Utils::redirect('access');
            }
        }

        public function validate_email($code) {
            $sql = 'SELECT id_user FROM '.DDBB_PREFIX.'users WHERE validation_code = ? LIMIT 1';
            $result = $this->query($sql, array($code));
            if($result->num_rows != 0) {
                $sql = 'UPDATE '.DDBB_PREFIX.'users SET validated_email = 1 WHERE validation_code = ? LIMIT 1';
                $this->query($sql, array($code));
                $html = LANGTXT['newsletter-validation-ok'];
            } else {
                $html = LANGTXT['newsletter-validation-ko'];
            }
            return $html;
        }

        public function update_visit_category($id_category) {
            $sql = 'UPDATE '.DDBB_PREFIX.'categories SET visits = visits + 1 WHERE id_category = ?';
            $this->query($sql, array($id_category));
        }

        public function update_visit_product($id_product) {
            $sql = 'UPDATE '.DDBB_PREFIX.'products SET visits = visits + 1 WHERE id_product = ?';
            $this->query($sql, array($id_product));
        }

        public function get_category_routes($id_category) {
            // I take the routes of the category in all its languages
            $sql = 'SELECT r.*, l.name AS language_name FROM '.DDBB_PREFIX.'categories_routes AS r
                        INNER JOIN '.DDBB_PREFIX.'ct_languages AS l ON l.id_language = r.id_language
                    WHERE id_category = ?';
            $result = $this->query($sql, array($id_category));
            if($result->num_rows != 0) {
                $routes = array();
                while($row = $result->fetch_assoc()) {
                    if(MULTILANGUAGE == true) {
                        $route = PUBLIC_PATH.'/'.$row['language_name'].$row['route'];
                    } else {
                        $route = PUBLIC_ROUTE.$row['route'];
                    }
                    $obj = array(
                        'route' => $route,
                        'language' => $row['language_name']
                    );
                    array_push($routes, $obj);
                }
                return $routes;
            } else {
                return null;
            }
        }

        public function get_category($id_category, $language_name = LANG) {
            $sql = 'SELECT c.*, l.*, v.name AS view_name FROM '.DDBB_PREFIX.'categories AS c
                        INNER JOIN '.DDBB_PREFIX.'categories_language AS l ON l.id_category = c.id_category
                        INNER JOIN '.DDBB_PREFIX.'categories_views AS v ON v.id_category_view = c.id_category_view
                        INNER JOIN '.DDBB_PREFIX.'ct_languages AS a ON a.id_language = l.id_language
                    WHERE c.id_category = ? AND a.name = ? AND c.id_state = 2 AND l.id_language = a.id_language
                    LIMIT 1';
            $result = $this->query($sql, array($id_category, strtolower($language_name)));
            if($result->num_rows != 0) {
                $row = $result->fetch_assoc();
                return $row;
            } else {
                return null;
            }
        }

        public function get_product($id_product, $id_category) {
            // I collect all the product information in the selected language
            $sql = 'SELECT p.*, pl.*, v.name AS view_name, cl.name AS category_name, c.id_category
                    FROM '.DDBB_PREFIX.'products AS p
                        INNER JOIN '.DDBB_PREFIX.'products_language AS pl ON pl.id_product = p.id_product
                        INNER JOIN '.DDBB_PREFIX.'products_views AS v ON v.id_product_view = p.id_product_view
                        INNER JOIN '.DDBB_PREFIX.'ct_languages AS a ON a.id_language = pl.id_language
                        INNER JOIN '.DDBB_PREFIX.'products_categories AS c ON c.id_product = p.id_product
                        INNER JOIN '.DDBB_PREFIX.'categories_language AS cl ON cl.id_category = c.id_category
                    WHERE p.id_product = ? AND c.id_category = ? AND a.name = ? AND p.id_state = 2
                        AND pl.id_language = a.id_language AND cl.id_language = a.id_language LIMIT 1';
            $result = $this->query($sql, array($id_product, $id_category, strtolower(LANG)));
            if($result->num_rows != 0) {
                $row = $result->fetch_assoc();
                $row['attributes'] = $this->get_product_attributes($id_product);
                $row['attributes_id'] = array();
                foreach($row['attributes'] as $value) {
                    array_push($row['attributes_id'], $value['id_attribute']);
                }
                $row['valid_ids'] = $this->get_product_valid_ids($id_product, $row['attributes_id']);
                return $row;
            } else {
                return null;
            }
        }

        public function get_product_attributes($id_product) {
            // I collect the attributes of the product in the chosen language
            $sql = 'SELECT p.*, a.*, l.*, g.id_language FROM '.DDBB_PREFIX.'products_attributes AS p
                        INNER JOIN '.DDBB_PREFIX.'attributes AS a ON a.id_attribute = p.id_attribute
                        INNER JOIN '.DDBB_PREFIX.'attributes_language AS l ON l.id_attribute = p.id_attribute
                        INNER JOIN '.DDBB_PREFIX.'ct_languages AS g ON g.id_language = l.id_language
                    WHERE id_product = ? AND g.name = ? ORDER BY p.priority';
            $result = $this->query($sql, array($id_product, strtolower(LANG)));
            if($result->num_rows != 0) {
                $attributes = array();
                while($row = $result->fetch_assoc()) {
                    // I collect the values of the attributes in the chosen language
                    $sql = 'SELECT * FROM '.DDBB_PREFIX.'attributes_value AS a
                                INNER JOIN '.DDBB_PREFIX.'attributes_value_language AS l ON l.id_attribute_value = a.id_attribute_value
                            WHERE a.id_attribute = ? AND l.id_language = ? ORDER BY a.priority';
                    $result_values = $this->query($sql, array($row['id_attribute'], $row['id_language']));
                    $row['values'] = $result_values->fetch_all(MYSQLI_ASSOC);
                    array_push($attributes, $row);
                }
                return $attributes;
            } else {
                return array();
            }
        }

        public function get_product_valid_ids($id_product, $attributes_id) {
            $attributes = array(
                'valid_values_id' => array(),
                'valid_products_related_id' => array()
            );
            // I go through all the related products to see which ones use the necessary attributes
            // and which values they are using to display
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'products_related WHERE id_product = ? AND id_state = 2';
            $result = $this->query($sql, array($id_product));
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    if(!empty($attributes_id)) {
                        $sql = 'SELECT * FROM '.DDBB_PREFIX.'products_related_attributes
                                WHERE id_product_related = ? AND id_attribute IN ('.implode(',', $attributes_id).')';
                        $result_attributes = $this->query($sql, array($row['id_product_related']));
                        // This means it has the necessary attributes
                        if($result_attributes->num_rows == count($attributes_id)) {
                            array_push($attributes['valid_products_related_id'], $row['id_product_related']);
                            while($row_attributes = $result_attributes->fetch_assoc()) {
                                if(!in_array($row_attributes['id_attribute_value'], $attributes['valid_values_id'])) {
                                    array_push($attributes['valid_values_id'], $row_attributes['id_attribute_value']);
                                }
                            }
                        }
                    } else {
                        array_push($attributes['valid_products_related_id'], $row['id_product_related']);
                    }
                }
            }
            return $attributes;
        }

        public function get_product_related_id($products_related_id) {
            if(empty($products_related_id)) {
                return null;
            }
            // I try to grab the main related product
            $sql = 'SELECT id_product_related FROM '.DDBB_PREFIX.'products_related
                    WHERE id_product_related IN ('.implode(',', $products_related_id).') AND main = 1 AND id_state = 2 LIMIT 1';
            $result = $this->query($sql);
            if($result->num_rows == 0) {
                // If the main related product is not valid I take a random valid one
                $sql = 'SELECT id_product_related FROM '.DDBB_PREFIX.'products_related
                        WHERE id_product_related IN ('.implode(',', $products_related_id).') AND main != 1 AND id_state = 2 LIMIT 1';
                $result = $this->query($sql);
                if($result->num_rows == 0) {
                    return null;
                }
            }
            $row = $result->fetch_assoc();
            return $row['id_product_related'];
        }

        public function get_product_related_values($id_product_related) {
            // I collect the values of the attributes of a related product
            $sql = 'SELECT id_attribute_value FROM '.DDBB_PREFIX.'products_related_attributes WHERE id_product_related = ?';
            $result = $this->query($sql, $id_product_related);
            if($result->num_rows != 0) {
                $values_id = array();
                while($row = $result->fetch_assoc()) {
                    array_push($values_id, $row['id_attribute_value']);
                }
                return $values_id;
            } else {
                return null;
            }
        }

        public function get_checkout_cart($id_cart) {
            $cart = $this->get_cart_array($id_cart);
            if($cart != null) {
                $html = '';
                foreach($cart['products'] as $value) {
                    // Draw html
                    $html .= '<div class="row item">';
                    $html .=    '<div class="col-4 pr-20">';
                    $html .=        '<a href="'.$value['url'].'" class="image" style="background-image: url('.$value['image'].');"></a>';
                    $html .=    '</div>';
                    $html .=    '<div class="col-8">';
                    $html .=        '<a href="'.$value['url'].'" class="name dots" title="'.$value['row']['product_name'].'">'.$value['row']['product_name'].'</a>';
                    if(!empty($value['attributes'])) {
                        $html .= '<div class="content-attributes">';
                        foreach($value['attributes'] as $valuea) {
                            $html .= '<div class="dots">'.$valuea['attribute_name'].': '.$valuea['value_name'].'</div>';
                        }
                        $html .= '</div>';
                    }
                    $html .=        '<div class="amount">Cantidad: '.$value['row']['amount'].' x <span class="price">'.$value['price_string'].'</span></div>';
                    $html .=    '</div>';
                    $html .= '</div>';    
                }
                return array(
                    'html' => $html,
                    'total' => $cart['total_string']
                );
            } else {
                return null;
            }
        }

        public function check_transaction_id($transactionId) {
            $sql = 'SELECT id_cart FROM '.DDBB_PREFIX.'carts WHERE id_transaction = ? AND id_cart = ? LIMIT 1';
            $result = $this->query($sql, array(
                $transactionId,
                $_COOKIE['id_cart']
            ));
            if($result->num_rows > 0) {
                return true;
            } else {
                return false;
            }
        }

    }
    
?> 