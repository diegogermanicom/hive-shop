<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2023
     */   

    class AdminAjax extends AdminModel {

        public $name_page;

        function __construct($name_page = 'default-page') {
            parent::__construct();
            $this->name_page = $name_page;
        }

        public function create_all_product_routes($id_product = null) {
            // I create the routes of a product or of all the products
            if($id_product == null) {
                $sql = 'DELETE FROM '.DDBB_PREFIX.'products_routes';
                $this->query($sql);
                $sql = 'SELECT * FROM '.DDBB_PREFIX.'products_categories';
                $result_categories = $this->query($sql);
            } else {
                $sql = 'DELETE FROM '.DDBB_PREFIX.'products_routes WHERE id_product = ?';
                $this->query($sql, array($id_product));
                $sql = 'SELECT * FROM '.DDBB_PREFIX.'products_categories WHERE id_product = ?';
                $result_categories = $this->query($sql, array($id_product));
            }
            while($row_category = $result_categories->fetch_assoc()) {
                $sql = 'SELECT * FROM '.DDBB_PREFIX.'products_language WHERE id_product = ?';
                $result_languages = $this->query($sql, array($row_category['id_product']));
                while($row_language = $result_languages->fetch_assoc()) {
                    $category_route = $this->get_category_route($row_category['id_category'], $row_language['id_language']);
                    $product_route = $category_route.'/'.$row_language['slug'];
                    $sql = 'INSERT INTO '.DDBB_PREFIX.'products_routes (id_product, id_category, id_language, route) VALUES (?, ?, ?, ?)';
                    $this->query($sql, array($row_language['id_product'], $row_category['id_category'], $row_language['id_language'], $product_route));
                }
            }
        }

        public function create_all_category_routes() {
            // I create the routes of all categories
            $sql = 'DELETE FROM '.DDBB_PREFIX.'categories_routes';
            $this->query($sql);
            $sql = 'SELECT * FROM categories';
            $result_categories = $this->query($sql);
            while($row_category = $result_categories->fetch_assoc()) {
                $sql = 'SELECT * FROM '.DDBB_PREFIX.'categories_language WHERE id_category = ?';
                $result_languages = $this->query($sql, array($row_category['id_category']));
                while($row_language = $result_languages->fetch_assoc()) {
                    $category_route = $this->get_category_route($row_category['id_parent'], $row_language['id_language']);
                    $category_route .= '/'.$row_language['slug'];
                    $sql = 'INSERT INTO '.DDBB_PREFIX.'categories_routes (id_category, id_language, route) VALUES (?, ?, ?)';
                    $this->query($sql, array($row_category['id_category'], $row_language['id_language'], $category_route));
                }
            }
            // I check if there are repeated routes, correct them and remake all routes
            $sql = 'SELECT route FROM '.DDBB_PREFIX.'categories_routes GROUP BY route, id_language HAVING COUNT(route) > 1';
            $result = $this->query($sql);
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $sql = 'SELECT * FROM '.DDBB_PREFIX.'categories_routes WHERE route = ?';
                    $result_route = $this->query($sql, array($row['route']));
                    while($row_route = $result_route->fetch_assoc()) {
                        $sql = 'UPDATE '.DDBB_PREFIX.'categories_language SET slug = CONCAT(slug, "-", id_category)
                                WHERE id_category = ? AND id_language = ? LIMIT 1';
                        $this->query($sql, array($row_route['id_category'], $row_route['id_language']));
                    }
                }
                $this->create_all_category_routes();
            }
        }

        public function check_category_slug($id_parent, $slug, $exempt_id_category = 0) {
            // I check if that route already exists for that parent category
            $sql = 'SELECT c.id_category FROM categories AS c
                        INNER JOIN '.DDBB_PREFIX.'categories_language AS l ON l.id_category = c.id_category
                    WHERE c.id_parent = ? AND l.slug = ? AND c.id_category != ?';
            $result = $this->query($sql, array($id_parent, $slug, $exempt_id_category));
            if($result->num_rows != 0) {
                return true;
            } else {
                return false;
            }
        }

        public function check_product_slug($categories, $slug, $exempt_id_product = 0) {
            // I check if that route already exists for the product categories
            $exist = false;
            foreach($categories as $value) {
                $sql = 'SELECT id_product FROM '.DDBB_PREFIX.'products_categories WHERE id_category = ? AND id_product != ?';
                $result = $this->query($sql, array($value, $exempt_id_product));
                if($result->num_rows != 0) {
                    $products_id = '';
                    while($row = $result->fetch_assoc()) {
                        $products_id .= $row['id_product'].',';
                    }
                    // If it is empty, it means that there is only the product that we are evaluating
                    if($products_id != '') {
                        $products_id = substr($products_id, 0, -1);
                        $sql = 'SELECT slug FROM '.DDBB_PREFIX.'products_language WHERE id_product IN ('.$products_id.') AND slug = ? LIMIT 1';
                        $result_slug = $this->query($sql, array($slug));
                        if($result_slug->num_rows != 0) {
                            $exist = true;
                        }
                    }
                }
            }
            return $exist;
        }

        public function get_category_route($id_category, $id_lang, $route = '') {
            // I get the complete route of the category
            $sql = 'SELECT c.id_parent, l.slug FROM '.DDBB_PREFIX.'categories AS c
                        INNER JOIN '.DDBB_PREFIX.'categories_language AS l ON l.id_category = c.id_category
                    WHERE c.id_category = ? AND l.id_language = ? LIMIT 1';
            $result = $this->query($sql, array($id_category, $id_lang));
            if($result->num_rows != 0) {
                $row = $result->fetch_assoc();
                if($row['id_parent'] != 1) {
                    return $this->get_category_route($row['id_parent'], $id_lang, '/'.$row['slug'].$route);
                }
                return '/'.$row['slug'].$route;
            } else {
                return '';
            }
        }

        public function check_product_main_hover_image($id_product) {
            // Check main image
            $sql = 'SELECT main_image, hover_image FROM '.DDBB_PREFIX.'products WHERE id_product = ? LIMIT 1';
            $result = $this->query($sql, array($id_product));
            if($result->num_rows != 0) {
                $row = $result->fetch_assoc();
                if($row['main_image'] == 0) {
                    // I select an image other than the hover image
                    $sql = 'SELECT id_product_image FROM '.DDBB_PREFIX.'products_images
                            WHERE id_product = ? AND id_product_image != ? LIMIT 1';
                    $result_main = $this->query($sql, array($id_product, $row['hover_image']));
                    if($result_main->num_rows != 0) {
                        $row_main = $result_main->fetch_assoc();
                        $sql = 'UPDATE '.DDBB_PREFIX.'products SET main_image = ? WHERE id_product = ? LIMIT 1';
                        $this->query($sql, array($row_main['id_product_image'], $id_product));
                        $row['main_image'] = $row_main['id_product_image'];
                    }
                }
                if($row['hover_image'] == 0) {
                    // I select an image other than the main image
                    $sql = 'SELECT id_product_image FROM '.DDBB_PREFIX.'products_images
                            WHERE id_product = ? AND id_product_image != ? LIMIT 1';
                    $result_hover = $this->query($sql, array($id_product, $row['main_image']));
                    if($result_hover->num_rows != 0) {
                        $row_hover = $result_hover->fetch_assoc();
                        $sql = 'UPDATE '.DDBB_PREFIX.'products SET hover_image = ? WHERE id_product = ? LIMIT 1';
                        $this->query($sql, array($row_hover['id_product_image'], $id_product));
                    }
                }
            }
        }

        public function save_new_product() {
            // I check that the slug with the same category does not already exist;
            foreach($_POST['properties'] as $value) {
                if($this->check_product_slug($_POST['categories'], $value['slug'])) {
                    return array(
                        'response' => 'error',
                        'mensaje' => 'The slug already exists in the selected categories.'
                    );
                }
            }
            // I save the product data
            $_POST['price'] = $this->parse_float_point($_POST['price']);
            $_POST['weight'] = $this->parse_float_point($_POST['weight']);
            $sql = 'INSERT INTO '.DDBB_PREFIX.'products (id_product_view, price, weight, alias, id_state) VALUES (?, ?, ?, ?)';
            $this->query($sql, array(
                $_POST['id_view'], $_POST['price'], $_POST['weight'], $_POST['alias'], $_POST['id_state']
            ));
            $id_product = $this->db->insert_id;
            // Saving categories
            foreach($_POST['categories'] as $value) {
                $main = ($_POST['main_category'] == $value) ? 1 : 0;
                $sql = 'INSERT INTO '.DDBB_PREFIX.'products_categories (id_product, id_category, main) VALUES (?, ?, ?)';
                $this->query($sql, array($id_product, $value, $main));
            }
            // Saving attributes
            if(!empty($_POST['attributes'])) {
                for($i = 0; $i < count($_POST['attributes']); $i++) {
                    $sql = 'INSERT INTO '.DDBB_PREFIX.'products_attributes (id_product, id_attribute, priority) VALUES (?, ?, ?)';
                    $this->query($sql, array($id_product, $_POST['attributes'][$i], ($i + 1)));
                }
            }
            // Saving properties (I need $i for $_POST['meta_data'])
            for($i = 0; $i < count($_POST['properties']); $i++) {
                if($_POST['properties'][$i]['slug'] == '') {
                    $_POST['properties'][$i]['slug'] = $_POST['alias'].'-'.$id_product;
                }
                $_POST['properties'][$i]['slug'] = $this->parse_slug($_POST['properties'][$i]['slug']);
                $sql = 'INSERT INTO '.DDBB_PREFIX.'products_language (id_product , id_language, `name`, `description`, slug, meta_title, meta_description, meta_keywords)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
                $this->query($sql, array($id_product, $_POST['properties'][$i]['id_lang'],
                    $_POST['properties'][$i]['name'], $_POST['properties'][$i]['description'], $_POST['properties'][$i]['slug'],
                    $_POST['meta_data'][$i]['meta_title'], $_POST['meta_data'][$i]['meta_description'], $_POST['meta_data'][$i]['meta_keywords']));
            }
            // Saving images if they are not empty (I need $i for main_image and hover_image)
            if(!empty($_POST['images'])) {
                for($i = 0; $i < count($_POST['images']); $i++) {
                    $name = strtolower($_POST['images'][$i]['name']);
                    $name = str_replace(' ', '-', $name);
                    // If the file already exists
                    if(file_exists(IMG_PATH.'/products/'.$name)) {
                        $name = uniqid().'-'.$name;
                    }
                    $sql = 'INSERT INTO '.DDBB_PREFIX.'images (`name`, `url`) VALUES (?, ?)';
                    $this->query($sql, array($name, '/img/products/'.$name));
                    $id_image = $this->db->insert_id;
                    $sql = 'INSERT INTO '.DDBB_PREFIX.'products_images (id_product, id_image, priority) VALUES (?, ?, ?)';
                    $this->query($sql, array($id_product, $id_image, ($i + 1)));
                    $id_product_image = $this->db->insert_id;
                    // Saving the image on the server
                    $this->base64_to_file($_POST['images'][$i]['data'], IMG_PATH.'/products/'.$name);
                    // If it is the first image, I mark it as the cover by default
                    if($i == 0) {
                        $sql = 'UPDATE '.DDBB_PREFIX.'products SET main_image = ? WHERE id_product = ? LIMIT 1';
                        $this->query($sql, array($id_product_image, $id_product));
                    }
                    // If it is the second image, I mark it as hover by default.
                    if($i == 1) {
                        $sql = 'UPDATE '.DDBB_PREFIX.'products SET hover_image = ? WHERE id_product = ? LIMIT 1';
                        $this->query($sql, array($id_product_image, $id_product));
                    }
                }
            }
            // Create all the routes for the product in all lenguages fot all categories
            $this->create_all_product_routes($id_product);
            return array('response' => 'ok');
        }

        public function get_product_images($id_product) {
            // I take the photos associated with the product
            $sql = 'SELECT p.*, i.url, i.name AS image_name,
                        CASE WHEN m.main_image IS NULL THEN 0 ELSE 1 END AS is_main,
                        CASE WHEN h.hover_image IS NULL THEN 0 ELSE 1 END AS is_hover
                    FROM '.DDBB_PREFIX.'products_images AS p
                        INNER JOIN '.DDBB_PREFIX.'images AS i ON i.id_image = p.id_image
                        LEFT JOIN products AS m ON m.main_image = p.id_product_image AND m.id_product = p.id_product
                        LEFT JOIN products AS h ON h.hover_image = p.id_product_image AND h.id_product = p.id_product
                    WHERE p.id_product = ? ORDER BY p.priority';
            $result = $this->query($sql, array($id_product));
            $html = '';
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $image_url = PUBLIC_PATH.'/img/products/thumbnails/'.$row['image_name'];
                    $html .= '<div class="item-image" style="background-image: url('.$image_url.');" id-product-image="'.$row['id_product_image'].'" id-image="'.$row['id_image'].'">';
                    if($row['is_main'] == 1) {
                        $html .= '<div class="is-main">Main image</div>';
                    }
                    if($row['is_hover'] == 1) {
                        $html .= '<div class="is-hover">Hover image</div>';
                    }
                    $html .=    '<div class="item-image-buttons">';
                    $html .=        '<div class="btn-item-image-main"><i class="fa-solid fa-house"></i> Main</div>';
                    $html .=        '<div class="btn-item-image-hover"><i class="fa-regular fa-hand-pointer"></i> Hover</div>';
                    $html .=        '<div class="btn-item-image-delete"><i class="fa-solid fa-trash-can"></i> Delete</div>';
                    $html .=    '</div>';
                    $html .= '</div>';
                }
            }
            return array(
                'response' => 'ok',
                'html' => $html
            );
        }

        public function save_product_main_image($id_product, $id_product_image) {
            $sql = 'UPDATE products SET main_image = ? WHERE id_product = ? LIMIT 1';
            $this->query($sql, array($id_product_image, $id_product));
            // It cannot be main and hover at the same time
            $sql = 'UPDATE products SET hover_image = 0 WHERE id_product = ? AND hover_image = ? LIMIT 1';
            $this->query($sql, array($id_product, $id_product_image));
            $this->check_product_main_hover_image($id_product);
            return array(
                'response' => 'ok',
                'mensaje' => 'The image has been set as main successfully!'
            );
        }

        public function save_product_hover_image($id_product, $id_product_image) {
            $sql = 'UPDATE products SET hover_image = ? WHERE id_product = ? LIMIT 1';
            $this->query($sql, array($id_product_image, $id_product));
            // It cannot be main and hover at the same time
            $sql = 'UPDATE products SET main_image = 0 WHERE id_product = ? AND main_image = ? LIMIT 1';
            $this->query($sql, array($id_product, $id_product_image));
            $this->check_product_main_hover_image($id_product);
            return array(
                'response' => 'ok',
                'mensaje' => 'The image has been set as hover successfully!'
            );
        }

        public function delete_server_image($id_image) {
            // I get the url to delete the file from the server
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'images WHERE id_image = ? LIMIT 1';
            $result = $this->query($sql, array($id_image));
            $row = $result->fetch_assoc();
            try {
                if(file_exists(IMG_PATH.'/products/'.$row['name'])) {
                    unlink(IMG_PATH.'/products/'.$row['name']);
                }
            } catch(Exception $e) {
                // Failed to delete the file
            }
            try {
                if(file_exists(IMG_PATH.'/products/thumbnails/'.$row['name'])) {
                    unlink(IMG_PATH.'/products/thumbnails/'.$row['name']);
                }
            } catch(Exception $e) {
                // Failed to delete the file
            }
            $sql = 'DELETE FROM '.DDBB_PREFIX.'images WHERE id_image = ? LIMIT 1';
            $this->query($sql, array($id_image));
            // I delete the image of the related products
            $sql = 'SELECT id_product_image, id_product  FROM '.DDBB_PREFIX.'products_images WHERE id_image = ?';
            $result = $this->query($sql, array($id_image));
            // Need to delete it before continuing
            $sql = 'DELETE FROM '.DDBB_PREFIX.'products_images WHERE id_image = ?';
            $this->query($sql, array($id_image));
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $sql = 'DELETE FROM '.DDBB_PREFIX.'products_related_images WHERE id_product_image = ?';
                    $this->query($sql, array($row['id_product_image']));
                    // If I had it as the main one, I would change it
                    $sql = 'UPDATE '.DDBB_PREFIX.'products SET main_image = 0 WHERE id_product = ? AND main_image = ? LIMIT 1';
                    $this->query($sql, array($row['id_product'], $row['id_product_image']));
                    // If I had it as the hover one, I would change it
                    $sql = 'UPDATE '.DDBB_PREFIX.'products SET hover_image = 0 WHERE id_product = ? AND hover_image = ? LIMIT 1';
                    $this->query($sql, array($row['id_product'], $row['id_product_image']));
                    $this->check_product_main_hover_image($row['id_product']);
                }
            }
            return array('response' => 'ok');
        }

        public function delete_product_image($id_product_image, $id_product) {
            $sql = 'DELETE FROM '.DDBB_PREFIX.'products_images WHERE id_product_image = ? LIMIT 1';
            $this->query($sql, array($id_product_image));
            $sql = 'DELETE FROM '.DDBB_PREFIX.'products_related_images WHERE id_product_image = ?';
            $this->query($sql, array($id_product_image));
            // If I had it as the main one, I would change it
            $sql = 'UPDATE '.DDBB_PREFIX.'products SET main_image = 0 WHERE id_product = ? AND main_image = ? LIMIT 1';
            $this->query($sql, array($id_product, $id_product_image));
            // If I had it as the hover one, I would change it
            $sql = 'UPDATE '.DDBB_PREFIX.'products SET hover_image = 0 WHERE id_product = ? AND hover_image = ? LIMIT 1';
            $this->query($sql, array($id_product, $id_product_image));
            $this->check_product_main_hover_image($id_product);
            return array('response' => 'ok');
        }

        public function get_add_images($id_product, $page = 1) {
            // I get the images that I can add to the product
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'products_images WHERE id_product = ? ORDER BY id_product_image';
            $result_product = $this->query($sql, array($id_product));
            if($result_product->num_rows != 0) {
                $ids = '';
                while($row_product = $result_product->fetch_assoc()) {
                    $ids .= $row_product['id_image'].',';
                }
                $ids = substr($ids, 0, -1);
                $sql = 'SELECT * FROM '.DDBB_PREFIX.'images WHERE id_image NOT IN('.$ids.') ORDER BY id_image LIMIT '.($page - 1).', 18';
            } else {
                $sql = 'SELECT * FROM '.DDBB_PREFIX.'images ORDER BY id_image LIMIT '.($page - 1).', 18';
            }
            $result = $this->query($sql);
            if($result->num_rows != 0) {
                $html = '';
                while($row = $result->fetch_assoc()) {
                    $url = PUBLIC_PATH.'/img/products/thumbnails/'.$row['name'];
                    $html .= '<div class="item-image" id-image="'.$row['id_image'].'" url="'.$url.'">';
                    $html .=    '<div class="image" style="background-image: url('.$url.')"></div>';
                    $html .=    '<div class="pt-5">';
                    $html .=        '<a href="'.PUBLIC_PATH.$row['url'].'" class="btn btn-black btn-sm w-100" target="_blank">View</a>';
                    $html .=    '</div>';
                    $html .= '</div>';
                }
            } else {
                $html = 'No images found';
            }
            // Start the pager
            $pager = '';
            return array(
                'response' => 'ok',
                'html' => $html,
                'pager' => $pager
            );
        }

        public function save_edit_product() {
            // I check that the slug with the same category does not already exist;
            foreach($_POST['properties'] as $value) {
                if($this->check_product_slug($_POST['categories'], $value['slug'], $_POST['id_product'])) {
                    return array(
                        'response' => 'error',
                        'mensaje' => 'The slug already exists in the selected categories.'
                    );
                }
            }
            // I save the product data
            $_POST['price'] = $this->parse_float_point($_POST['price']);
            $_POST['weight'] = $this->parse_float_point($_POST['weight']);
            $sql = 'UPDATE '.DDBB_PREFIX.'products SET id_product_view = ?, price = ?, weight = ?, alias = ?, id_state = ? WHERE id_product = ?';
            $this->query($sql, array(
                $_POST['id_view'], $_POST['price'], $_POST['weight'], $_POST['alias'], $_POST['id_state'], $_POST['id_product']
            ));
            // Saving categories
            $sql = 'DELETE FROM '.DDBB_PREFIX.'products_categories WHERE id_product = ?';
            $this->query($sql, array($_POST['id_product']));
            foreach($_POST['categories'] as $value) {
                $main = ($_POST['main_category'] == $value) ? 1 : 0;
                $sql = 'INSERT INTO '.DDBB_PREFIX.'products_categories (id_product, id_category, main) VALUES (?, ?, ?)';
                $this->query($sql, array($_POST['id_product'], $value, $main));
            }
            // Saving attributes
            $sql = 'DELETE FROM '.DDBB_PREFIX.'products_attributes WHERE id_product = ?';
            $this->query($sql, array($_POST['id_product']));
            if(!empty($_POST['attributes'])) {
                for($i = 0; $i < count($_POST['attributes']); $i++) {
                    $sql = 'INSERT INTO '.DDBB_PREFIX.'products_attributes (id_product, id_attribute, priority) VALUES (?, ?, ?)';
                    $this->query($sql, array($_POST['id_product'], $_POST['attributes'][$i], ($i + 1)));
                }
            }
            // Saving properties
            for($i = 0; $i < count($_POST['properties']); $i++) {
                if($_POST['properties'][$i]['slug'] == '') {
                    $_POST['properties'][$i]['slug'] = $_POST['alias'].'-'.$_POST['id_product'];
                }
                $_POST['properties'][$i]['slug'] = $this->parse_slug($_POST['properties'][$i]['slug']);
                $sql = 'UPDATE '.DDBB_PREFIX.'products_language SET `name` = ?, `description` = ?, slug = ?, meta_title = ?, meta_description = ?, meta_keywords = ?
                        WHERE id_product = ? AND id_language = ?';
                $this->query($sql, array($_POST['properties'][$i]['name'], $_POST['properties'][$i]['description'], $_POST['properties'][$i]['slug'],
                    $_POST['meta_data'][$i]['meta_title'], $_POST['meta_data'][$i]['meta_description'], $_POST['meta_data'][$i]['meta_keywords'],
                    $_POST['id_product'], $_POST['properties'][$i]['id_lang']));
            }
            // Saving images if they are not empty
            if(!empty($_POST['images'])) {
                for($i = 0; $i < count($_POST['images']); $i++) {
                    $priority = ($i + 1);
                    if($_POST['images'][$i]['type'] == 'explorer') {
                        $name = strtolower($_POST['images'][$i]['name']);
                        $name = str_replace(' ', '-', $name);
                        // If the file already exists
                        if(file_exists(IMG_PATH.'/products/'.$name)) {
                            $name = uniqid().'-'.$name;
                        }
                        $sql = 'INSERT INTO '.DDBB_PREFIX.'images (`name`, `url`) VALUES (?, ?)';
                        $this->query($sql, array($name, '/img/products/'.$name));
                        $id_imagen = $this->db->insert_id;
                        $sql = 'INSERT INTO '.DDBB_PREFIX.'products_images (id_product, id_image, priority) VALUES (?, ?, ?)';
                        $this->query($sql, array($_POST['id_product'], $id_imagen, $priority));
                        // Saving the image on the server
                        $this->save_product_image($_POST['images'][$i]['data'], $name);
                    } else if($_POST['images'][$i]['type'] == 'server') {
                        $sql = 'INSERT INTO '.DDBB_PREFIX.'products_images (id_product, id_image, priority) VALUES (?, ?, ?)';
                        $this->query($sql, array($_POST['id_product'], $_POST['images'][$i]['id_image'], $priority));
                    } else if($_POST['images'][$i]['type'] == 'product') {
                        $sql = 'UPDATE '.DDBB_PREFIX.'products_images SET priority = ? WHERE id_product_image = ? LIMIT 1';
                        $this->query($sql, array($priority, $_POST['images'][$i]['id_product_image']));
                    }
                }
            }
            $this->check_product_main_hover_image($_POST['id_product']);
            // I save the main related
            if($_POST['id_related_main'] != null) {
                $sql = 'UPDATE '.DDBB_PREFIX.'products_related SET main = 0 WHERE id_product = ? AND main = 1 LIMIT 1';
                $this->query($sql, array($_POST['id_product']));
                $sql = 'UPDATE '.DDBB_PREFIX.'products_related SET main = 1 WHERE id_product_related = ? LIMIT 1';
                $this->query($sql, array($_POST['id_related_main']));
            }
            // Create all the routes for the product in all lenguages fot all categories
            $this->create_all_product_routes($_POST['id_product']);
            return array(
                'response' => 'ok',
                'mensaje' => 'The product has been successfully updated!',
                'properties' => $_POST['properties']
            );
        }

        public function save_product_image($image64, $name) {
            // imagescale
            $path = IMG_PATH.'/products/'.$name;
            $this->base64_to_file($image64, $path);
            // I create a new image to resize it
            $data = explode(',', $image64);
            $data = base64_decode($data[1]);
            $image = imagecreatefromstring($data);
            $image_thumbnail = imagescale($image, 200, -1, IMG_SINC);
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            // Save new image
            if($extension == 'jpg' || $extension == 'jpeg') {
                imagejpeg($image_thumbnail, IMG_PATH.'/products/thumbnails/'.$name);
            } else if($extension == 'png') {
                imagepng($image_thumbnail, IMG_PATH.'/products/thumbnails/'.$name);                
            }
            imagedestroy($image);
            imagedestroy($image_thumbnail);
        }

        public function get_related($id_product) {
            // Drawing the list of related products
            $sql = 'SELECT p.*, s.name AS state_name, CASE WHEN ISNULL(i.num_images) THEN 0 ELSE i.num_images END AS num_images FROM '.DDBB_PREFIX.'products_related AS p
                        INNER JOIN '.DDBB_PREFIX.'ct_states AS s ON s.id_state = p.id_state
                        LEFT JOIN (SELECT COUNT(id_products_related_image) AS num_images, id_product_related FROM '.DDBB_PREFIX.'products_related_images GROUP BY id_product_related) AS i ON i.id_product_related = p.id_product_related
                    WHERE p.id_product = ? ORDER BY id_product_related';
            $result = $this->query($sql, array($id_product));
            $html = '';
            if($result->num_rows != 0) {
                $html = '<table>';
                $html .= '<thead>';
                $html .=    '<tr>';
                $html .=        '<th style="width: 150px;">Id related</th>';
                $html .=        '<th class="text-left">Attributes</th>';
                $html .=        '<th style="width: 100px;">Images</th>';
                $html .=        '<th style="width: 100px;">Stock</th>';
                $html .=        '<th style="width: 150px;">Price Change</th>';
                $html .=        '<th style="width: 100px;">State</th>';
                $html .=        '<th style="width: 100px;">Main</th>';
                $html .=        '<th style="width: 200px;"></th>';
                $html .=    '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                while($row = $result->fetch_assoc()) {
                    // I collect the selected values and alias of the related product attributes
                    $html_attr = $this->get_related_attributes_string($row['id_product_related']);
                    // Check if is main
                    $selected = ($row['main'] == 1) ? ' checked' : '';
                    $html .= '<tr>';
                    $html .=    '<td class="text-center">'.$row['id_product_related'].'</td>';
                    $html .=    '<td>'.$html_attr.'</td>';
                    $html .=    '<td class="text-center">'.$row['num_images'].'</td>';
                    $html .=    '<td class="text-center">'.$row['stock'].'</td>';
                    $html .=    '<td class="text-center">'.$row['price_change'].' €</td>';
                    $html .=    '<td class="text-center">'.$row['state_name'].'</td>';
                    $html .=    '<td class="text-center">';
                    $html .=        '<label class="radio"><input type="radio" name="input-related-main"'.$selected.' value="'.$row['id_product_related'].'"><span class="checkmark"></span></label>';
                    $html .=    '</td>';
                    $html .=    '<td class="text-center">';
                    $html .=        '<div class="btn btn-black btn-sm mr-5 btn-edit-related" id-product-related="'.$row['id_product_related'].'">Edit</div>';
                    $html .=        '<div class="btn btn-black btn-sm btn-delete-related" id-product-related="'.$row['id_product_related'].'">Delete</div>';
                    $html .=    '</td>';
                    $html .= '</tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
            } else {
                $html = 'No related products';
            }
            return array(
                'response' => 'ok',
                'html' => $html
            );
        }

        public function get_add_related($id_product) {
            // I draw the popup to add a new related product
            $sql = 'SELECT p.*, a.alias FROM '.DDBB_PREFIX.'products_attributes AS p
                        INNER JOIN '.DDBB_PREFIX.'attributes AS a ON a.id_attribute = p.id_attribute
                    WHERE p.id_product = ? ORDER BY p.priority';
            $result = $this->query($sql, array($id_product));
            $html = '<div class="row pb-20">';
            $html .=    '<div class="col-12 col-sm-4"><b>State</b></div>';
            $html .=    '<div class="col-12 col-sm-8">';
            $html .=        '<select id="select-add-related-state" class="w-100">'.$this->get_states_list().'</select>';
            $html .=    '</div>';
            $html .= '</div>';
            if($result->num_rows != 0) {
                $html .= '<div class="content-attributes">';
                while($row = $result->fetch_assoc()) {
                    $html .= '<div class="row pb-20 item-attribute" id-attribute="'.$row['id_attribute'].'">';
                    $html .=    '<div class="col-12 col-sm-4"><b>'.$row['alias'].'</b></div>';
                    $html .=    '<div class="col-12 col-sm-8">';
                    $html .=        '<select class="w-100">';
                    $sql = 'SELECT * FROM '.DDBB_PREFIX.'attributes_value WHERE id_attribute = ?';
                    $result_values = $this->query($sql, array($row['id_attribute']));
                    while($row_values = $result_values->fetch_assoc()) {
                        $html .= '<option value="'.$row_values['id_attribute_value'].'">'.$row_values['alias'].'</option>';
                    }
                    $html .=        '</select>';
                    $html .=    '</div>';
                    $html .= '</div>';
                }
                $html .= '<div>';
            }
            $html .= '<div class="row pb-10">';
            $html .=    '<div class="col-12 col-sm-4"><b>Stock</b></div>';
            $html .=    '<div class="col-12 col-sm-4"><b>Price change</b></div>';
            $html .=    '<div class="col-12 col-sm-4"><b>Weight change(Kg)</b></div>';
            $html .= '</div>';
            $html .= '<div class="row pb-20">';
            $html .=    '<div class="col-12 col-sm-4 pr-10 pr-sm-0">';
            $html .=        '<input type="number" id="input-add-related-stock" class="w-100" value="0">';
            $html .=    '</div>';
            $html .=    '<div class="col-12 col-sm-4 pr-10 pr-sm-0">';
            $html .=        '<input type="text" id="input-add-related-price-change" class="w-100" value="0">';
            $html .=    '</div>';
            $html .=    '<div class="col-12 col-sm-4">';
            $html .=        '<input type="text" id="input-add-related-weight-change" class="w-100" value="0">';
            $html .=    '</div>';
            $html .= '</div>';
            $sql = 'SELECT p.*, i.name AS image_name FROM '.DDBB_PREFIX.'products_images AS p
                        INNER JOIN '.DDBB_PREFIX.'images AS i ON i.id_image = p.id_image
                    WHERE p.id_product = ? ORDER BY p.priority';
            $result = $this->query($sql, array($id_product));
            $html .= '<div class="pb-10"><b>Images</b></div>';
            $html .= '<div class="content-images">';
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $image_url = PUBLIC_PATH.'/img/products/thumbnails/'.$row['image_name'];
                    $html .= '<div class="item-image" style="background-image: url('.$image_url.');" id-product-image="'.$row['id_product_image'].'"></div>';
                }
            }
            $html .= '</div>';
            return array(
                'response' => 'ok',
                'html' => $html
            );
        }

        public function get_edit_related($id_product_related) {
            // I draw the popup to edit a related product
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'products_related WHERE id_product_related = ? LIMIT 1';
            $result = $this->query($sql, array($id_product_related));
            $row = $result->fetch_assoc();
            $html = '<div class="row pb-20">';
            $html .=    '<div class="col-12 col-sm-4"><b>State</b></div>';
            $html .=    '<div class="col-12 col-sm-8">';
            $html .=        '<select id="select-edit-related-state" class="w-100">'.$this->get_states_list($row['id_state']).'</select>';
            $html .=    '</div>';
            $html .= '</div>';
            $html .= '<div class="row pb-10">';
            $html .=    '<div class="col-12 col-sm-4"><b>Stock</b></div>';
            $html .=    '<div class="col-12 col-sm-4"><b>Price change</b></div>';
            $html .=    '<div class="col-12 col-sm-4"><b>Weight change(Kg)</b></div>';
            $html .= '</div>';
            $html .= '<div class="row pb-20">';
            $html .=    '<div class="col-12 col-sm-4 pr-10 pr-sm-0">';
            $html .=        '<input type="number" id="input-edit-related-stock" class="w-100" value="'.$row['stock'].'">';
            $html .=    '</div>';
            $html .=    '<div class="col-12 col-sm-4 pr-10 pr-sm-0">';
            $html .=        '<input type="text" id="input-edit-related-price-change" class="w-100" value="'.$this->parse_float_point_back($row['price_change']).'">';
            $html .=    '</div>';
            $html .=    '<div class="col-12 col-sm-4">';
            $html .=        '<input type="text" id="input-edit-related-weight-change" class="w-100" value="'.$this->parse_float_point_back($row['weight_change']).'">';
            $html .=    '</div>';
            $html .= '</div>';
            $html .= '<div class="row pb-10">';
            $html .=    '<div class="col-12 col-sm-4"><b>Offer</b></div>';
            $html .=    '<div class="col-12 col-sm-4"><b>Offer start</b></div>';
            $html .=    '<div class="col-12 col-sm-4"><b>Offer end</b></div>';
            $html .= '</div>';
            $html .= '<div class="row pb-20">';
            $html .=    '<div class="col-12 col-sm-4 pr-10 pr-sm-0">';
            $html .=        '<input type="text" id="input-edit-related-offer" class="w-100" value="'.$this->parse_float_point_back($row['offer']).'">';
            $html .=    '</div>';
            $html .=    '<div class="col-12 col-sm-4 pr-10 pr-sm-0">';
            $html .=        '<input type="date" id="input-edit-related-offer-start-date" class="w-100" value="'.$row['offer_start_date'].'" min="'.date('Y-m-d').'">';
            $html .=    '</div>';
            $html .=    '<div class="col-12 col-sm-4">';
            $html .=        '<input type="date" id="input-edit-related-offer-end-date" class="w-100" value="'.$row['offer_end_date'].'" min="'.date('Y-m-d').'">';
            $html .=    '</div>';
            $html .= '</div>';
            $sql = 'SELECT p.*, i.name AS image_name,
                        CASE WHEN ISNULL(r.id_product_image) THEN "" ELSE " selected" END AS selected
                    FROM '.DDBB_PREFIX.'products_images AS p
                        INNER JOIN '.DDBB_PREFIX.'images AS i ON i.id_image = p.id_image
                        LEFT JOIN '.DDBB_PREFIX.'products_related_images AS r ON r.id_product_image = p.id_product_image AND r.id_product_related = ?
                    WHERE p.id_product = ? ORDER BY p.priority';
            $result = $this->query($sql, array($id_product_related, $row['id_product']));
            $html .= '<div class="pb-10"><b>Images</b></div>';
            $html .= '<div class="content-images">';
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $image_url = PUBLIC_PATH.'/img/products/thumbnails/'.$row['image_name'];
                    $html .= '<div class="item-image'.$row['selected'].'" style="background-image: url('.$image_url.');" id-product-image="'.$row['id_product_image'].'"></div>';
                }
            }
            $html .= '</div>';
            return array(
                'response' => 'ok',
                'html' => $html
            );
        }

        public function save_related() {
            $price_change = $this->parse_float_point($_POST['price_change']);
            $weight_change = $this->parse_float_point($_POST['weight_change']);
            $offer = $this->parse_float_point($_POST['offer']);
            if($_POST['offer_start'] == '' || $_POST['offer_end'] == '') {
                $_POST['offer_start'] = NULL;
                $_POST['offer_end'] = NULL;
            }
            $sql = 'UPDATE '.DDBB_PREFIX.'products_related SET stock = ?, price_change = ?, weight_change = ?,
                        id_state = ?, offer = ?, offer_start_date = ?, offer_end_date = ?
                    WHERE id_product_related = ? LIMIT 1';
            $this->query($sql, array(
                $_POST['stock'], $price_change, $weight_change, $_POST['id_state'],
                $offer, $_POST['offer_start'], $_POST['offer_end'], $_POST['id_product_related']
            ));
            // I save the images
            $sql = 'DELETE FROM products_related_images WHERE id_product_related = ?';
            $this->query($sql, array($_POST['id_product_related']));
            if(!empty($_POST['images'])) {
                foreach($_POST['images'] as $i => $value) {
                    $sql = 'INSERT INTO products_related_images (id_product_related, id_product_image) VALUES (?, ?)';
                    $this->query($sql, array($_POST['id_product_related'], $value));
                }
            }
            return array(
                'response' => 'ok',
                'mensaje' => 'The related product has been successfully updated!'
            );
        }

        public function add_related() {
            $sql = 'SELECT id_product_related FROM '.DDBB_PREFIX.'products_related WHERE id_product = ?';
            $result = $this->query($sql, array($_POST['id_product']));
            // I check if the related product already exists
            if($result->num_rows != 0) {
                if(!empty($_POST['attributes'])) {
                    // I check if those attributes with those values already exists
                    while($row = $result->fetch_assoc()) {
                        $sql_where = '';
                        foreach($_POST['attributes'] as $i => $value) {
                            $sql_where .= 'id_attribute_value = '.$value['id_value'].' OR ';
                        }
                        $sql_where = substr($sql_where, 0, -4);
                        $sql = 'SELECT id_product_related FROM '.DDBB_PREFIX.'products_related_attributes
                                WHERE id_product_related = ? AND ('.$sql_where.')';
                        $result_exist = $this->query($sql, array($row['id_product_related']));
                        if($result_exist->num_rows == count($_POST['attributes'])) {
                            return array(
                                'response' => 'error',
                                'mensaje' => 'The related product already exists.'
                            );        
                        }
                    }
                } else {
                    // Already have a product with no attributes
                    return array(
                        'response' => 'error',
                        'mensaje' => 'The related product without attributes already exists.'
                    );
                }
            }
            // I check if it has any product related or not to mark it as main by default
            if($result->num_rows == 0) {
                $main = 1;
                $table_html = true;
                $selected = ' checked';
            } else {
                $main = 0;
                $table_html = false;
                $selected = '';
            }
            $_POST['price_change'] = $this->parse_float_point($_POST['price_change']);
            $_POST['weight_change'] = $this->parse_float_point($_POST['weight_change']);
            $sql = 'INSERT INTO '.DDBB_PREFIX.'products_related (id_product, stock, price_change, weight_change, main, id_state)
                    VALUES (?, ?, ?, ?, ?, ?)';
            $this->query($sql, array(
                $_POST['id_product'], $_POST['stock'], $_POST['price_change'], $_POST['weight_change'], $main, $_POST['id_state']
            ));
            $id_related = $this->db->insert_id;
            // I save the attributes
            if(!empty($_POST['attributes'])) {
                foreach($_POST['attributes'] as $i => $value) {
                    $sql = 'INSERT INTO '.DDBB_PREFIX.'products_related_attributes (id_product_related, id_attribute, id_attribute_value) VALUES (?, ?, ?)';
                    $this->query($sql, array($id_related, $value['id_attribute'], $value['id_value']));
                }
            }
            // I save the images
            if(!empty($_POST['images'])) {
                foreach($_POST['images'] as $i => $value) {
                    $sql = 'INSERT INTO '.DDBB_PREFIX.'products_related_images (id_product_related, id_product_image) VALUES (?, ?)';
                    $this->query($sql, array($id_related, $value));
                }
                $images = count($_POST['images']);
            } else {
                $images = 0;
            }
            // I collect the selected values and alias of the related product attributes
            $html_attr = $this->get_related_attributes_string($id_related);
            $html = '';
            if($table_html == true) {
                $html .= '<table>';
                $html .= '<thead>';
                $html .=    '<tr>';
                $html .=        '<th style="width: 150px;">Id related</th>';
                $html .=        '<th class="text-left">Attributes</th>';
                $html .=        '<th style="width: 100px;">Images</th>';
                $html .=        '<th style="width: 100px;">Stock</th>';
                $html .=        '<th style="width: 150px;">Price Change</th>';
                $html .=        '<th style="width: 100px;">State</th>';
                $html .=        '<th style="width: 100px;">Main</th>';
                $html .=        '<th style="width: 200px;"></th>';
                $html .=    '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
            }
            $html .= '<tr>';
            $html .=    '<td class="text-center">'.$id_related.'</td>';
            $html .=    '<td>'.$html_attr.'</td>';
            $html .=    '<td class="text-center">'.$images.'</td>';
            $html .=    '<td class="text-center">'.$_POST['stock'].'</td>';
            $html .=    '<td class="text-center">'.$_POST['price_change'].' €</td>';
            $html .=    '<td class="text-center">'.$this->get_state($_POST['id_state']).'</td>';
            $html .=    '<td class="text-center">';
            $html .=        '<label class="radio"><input type="radio" name="input-related-main"'.$selected.' value="'.$id_related.'"><span class="checkmark"></span></label>';
            $html .=    '</td>';
            $html .=    '<td class="text-center">';
            $html .=        '<div class="btn btn-black btn-sm mr-5 btn-edit-related" id-product-related="'.$id_related.'">Edit</div>';
            $html .=        '<div class="btn btn-black btn-sm btn-delete-related" id-product-related="'.$id_related.'">Delete</div>';
            $html .=    '</td>';
            $html .= '</tr>';
            if($table_html == true) {
                $html .= '</tbody>';
                $html .= '</table>';
            }
            return array(
                'response' => 'ok',
                'html' => $html,
                'mensaje' => 'The related product has been successfully added!'
            );
        }

        public function delete_related($id_product_related) {
            // I check if it is the main one
            $sql = 'SELECT id_product FROM '.DDBB_PREFIX.'products_related WHERE id_product_related = ? AND main = 1 LIMIT 1';
            $result = $this->query($sql, array($id_product_related));
            if($result->num_rows != 0) {
                $row = $result->fetch_assoc();
                $sql = 'UPDATE '.DDBB_PREFIX.'products_related SET main = 1 WHERE id_product = ? AND id_product_related != ? ORDER BY id_product_related LIMIT 1';
                $this->query($sql, array($row['id_product'], $id_product_related));
            }
            // I remove the related product
            $sql = 'DELETE FROM '.DDBB_PREFIX.'products_related WHERE id_product_related = ? LIMIT 1';
            $this->query($sql, array($id_product_related));
            // I select the id of the main related
            $sql = 'SELECT id_product_related FROM '.DDBB_PREFIX.'products_related WHERE main = 1 LIMIT 1';
            $result = $this->query($sql);
            if($result->num_rows != 0) {
                $row = $result->fetch_assoc();
                $id_main = $row['id_product_related'];
            } else {
                $id_main = null;
            }
            $sql = 'DELETE FROM '.DDBB_PREFIX.'products_related_attributes WHERE id_product_related = ?';
            $this->query($sql, array($id_product_related));
            $sql = 'DELETE FROM '.DDBB_PREFIX.'products_related_images WHERE id_product_related = ?';
            $this->query($sql, array($id_product_related));            
            return array(
                'response' => 'ok',
                'id_main' => $id_main
            );
        }

        public function delete_product($id_product) {
            $sql = 'DELETE FROM '.DDBB_PREFIX.'products WHERE id_product = ? LIMIT 1';
            $this->query($sql, array($id_product));
            $sql = 'DELETE FROM '.DDBB_PREFIX.'products_attributes WHERE id_product = ?';
            $this->query($sql, array($id_product));
            $sql = 'DELETE FROM '.DDBB_PREFIX.'products_categories WHERE id_product = ?';
            $this->query($sql, array($id_product));
            $sql = 'DELETE FROM '.DDBB_PREFIX.'products_language WHERE id_product = ?';
            $this->query($sql, array($id_product));
            // I check if the product images are only used by him to remove them from the server
            $sql = 'SELECT id_product_image, id_image FROM '.DDBB_PREFIX.'products_images WHERE id_product = ?';
            $result = $this->query($sql, array($id_product));
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $sql = 'SELECT id_image FROM '.DDBB_PREFIX.'products_images WHERE id_image = ? AND id_product != ? LIMIT 1';
                    $result_images = $this->query($sql, array($row['id_image'], $id_product));
                    if($result_images->num_rows == 0) {
                        $this->delete_server_image($row['id_image']);
                    } else {
                        $sql = 'DELETE FROM '.DDBB_PREFIX.'products_images WHERE id_image = ? AND id_product = ? LIMIT 1';
                        $this->query($sql, array($row['id_image'], $id_product));
                    }
                }
            }
            // I delete the attributes and images of the related products
            $sql = 'SELECT id_product_related FROM '.DDBB_PREFIX.'products_related WHERE id_product = ?';
            $result = $this->query($sql, array($id_product));
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $sql = 'DELETE FROM '.DDBB_PREFIX.'products_related_attributes WHERE id_product_related = ?';
                    $this->query($sql, array($row['id_product_related']));
                    $sql = 'DELETE FROM '.DDBB_PREFIX.'products_related_images WHERE id_product_related = ?';
                    $this->query($sql, array($row['id_product_related']));
                }
            }
            $sql = 'DELETE FROM '.DDBB_PREFIX.'products_related WHERE id_product = ?';
            $this->query($sql, array($id_product));
            // I delete the routes of the product
            $sql = 'DELETE FROM '.DDBB_PREFIX.'products_routes WHERE id_product = ?';
            $this->query($sql, array($id_product));
            // Delete all category custom routes
            $sql = 'DELETE FROM '.DDBB_PREFIX.'products_custom_routes WHERE id_product = ?';
            $this->query($sql, array($id_product));
            return array('response' => 'ok');
        }

        public function delete_product_custom_route($id_product_custom_route) {
            $sql = 'DELETE FROM '.DDBB_PREFIX.'products_custom_routes WHERE id_product_custom_route = ?';
            $this->query($sql, array($id_product_custom_route));
            return array('response' => 'ok');
        }

        public function get_product_categories_list($id_product, $id_category = null) {
            $sql = 'SELECT p.*, c.alias AS category_alias FROM '.DDBB_PREFIX.'products_categories AS p
                        INNER JOIN '.DDBB_PREFIX.'categories AS c ON c.id_category = p.id_category
                    WHERE p.id_product = ?';
            $result = $this->query($sql, array($id_product));
            $html = '';
            while($row = $result->fetch_assoc()) {
                $html .= '<option value="'.$row['id_category'].'">'.$row['id_category'].' - '.$row['category_alias'].'</option>';
            }
            return array(
                'response' => 'ok',
                'html' => $html
            );
        }

        public function save_new_product_custom_route() {
            foreach($_POST['routes'] as $value) {
                if($value['route'] != '') {
                    // If it does not have the / at the beginning I add it
                    if(substr($value['route'], 0, 1) != '/') {
                        $value['route'] = '/'.$value['route'];
                    }
                    $category_route = $this->get_category_route($_POST['id_category'], $value['id_language']);
                    $product_route = $category_route.$value['route'];
                    $sql = 'INSERT INTO '.DDBB_PREFIX.'products_custom_routes (id_product, id_category, id_language, route) VALUES (?, ?, ?, ?)';
                    $this->query($sql, array($_POST['id_product'], $_POST['id_category'], $value['id_language'], $product_route));
                }
            }
            return array('response' => 'ok');
        }

        public function save_new_category() {
            // I check that the slug does not already exist at the same level;
            foreach($_POST['properties'] as $value) {
                if($this->check_category_slug($_POST['id_parent'], $value['slug'])) {
                    return array(
                        'response' => 'error',
                        'mensaje' => 'The slug already exists in the selected category level.'
                    );
                }
            }
            $sql = 'INSERT INTO '.DDBB_PREFIX.'categories (id_parent, id_category_view, alias, id_state) VALUES (?, ?, ?, ?)';
            $this->query($sql, array($_POST['id_parent'], $_POST['id_view'], $_POST['alias'], $_POST['id_state']));
            $id_category = $this->db->insert_id;
            // Saving properties
            for($i = 0; $i < count($_POST['properties']); $i++) {
                if($_POST['properties'][$i]['slug'] == '') {
                    $_POST['properties'][$i]['slug'] = $_POST['alias'].'-'.$id_category;
                }
                $_POST['properties'][$i]['slug'] = $this->parse_slug($_POST['properties'][$i]['slug']);
                $sql = 'INSERT INTO '.DDBB_PREFIX.'categories_language (id_category , id_language, `name`, `description`, slug, meta_title, meta_description, meta_keywords)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
                $this->query($sql, array($id_category, $_POST['properties'][$i]['id_lang'],
                    $_POST['properties'][$i]['name'], $_POST['properties'][$i]['description'], $_POST['properties'][$i]['slug'],
                    $_POST['meta_data'][$i]['meta_title'], $_POST['meta_data'][$i]['meta_description'], $_POST['meta_data'][$i]['meta_keywords']));
            }
            // Save the route of the category for each language
            foreach($_POST['properties'] as $value) {
                $category_route = $this->get_category_route($_POST['id_parent'], $value['id_lang']);
                $category_route .= '/'.$value['slug'];
                $sql = 'INSERT INTO '.DDBB_PREFIX.'categories_routes (id_category, id_language, route) VALUES (?, ?, ?)';
                $this->query($sql, array($id_category, $value['id_lang'], $category_route));
            }
            return array('response' => 'ok');
        }

        public function save_edit_category() {
            // I check that the slug does not already exist at the same level;
            foreach($_POST['properties'] as $value) {
                if($this->check_category_slug($_POST['id_parent'], $value['slug'], $_POST['id_category'])) {
                    return array(
                        'response' => 'error',
                        'mensaje' => 'The slug already exists in the selected category level.'
                    );
                }
            }
            $sql = 'UPDATE '.DDBB_PREFIX.'categories SET id_parent = ?, id_category_view = ?, alias = ?, id_state = ? WHERE id_category = ?';
            $this->query($sql, array($_POST['id_parent'], $_POST['id_view'], $_POST['alias'], $_POST['id_state'], $_POST['id_category']));
            // Saving properties
            for($i = 0; $i < count($_POST['properties']); $i++) {
                if($_POST['properties'][$i]['slug'] == '') {
                    $_POST['properties'][$i]['slug'] = $_POST['alias'].'-'.$_POST['id_category'];
                }
                $_POST['properties'][$i]['slug'] = $this->parse_slug($_POST['properties'][$i]['slug']);
                $sql = 'UPDATE '.DDBB_PREFIX.'categories_language SET `name` = ?, `description` = ?, slug = ?, meta_title = ?, meta_description = ?, meta_keywords = ?
                        WHERE id_category = ? AND id_language = ?';
                $this->query($sql, array($_POST['properties'][$i]['name'], $_POST['properties'][$i]['description'], $_POST['properties'][$i]['slug'],
                    $_POST['meta_data'][$i]['meta_title'], $_POST['meta_data'][$i]['meta_description'], $_POST['meta_data'][$i]['meta_keywords'],
                    $_POST['id_category'], $_POST['properties'][$i]['id_lang']));
            }
            // I recreate all category routes
            $this->create_all_category_routes();
            // I recreate all product routes
            $this->create_all_product_routes();
            return array(
                'response' => 'ok',
                'mensaje' => 'The product has been successfully updated!'
            );
        }

        public function delete_category($id_category) {
            $sql = 'DELETE FROM '.DDBB_PREFIX.'categories WHERE id_category = ? LIMIT 1';
            $this->query($sql, array($id_category));
            $sql = 'DELETE FROM '.DDBB_PREFIX.'categories_language WHERE id_category = ?';
            $this->query($sql, array($id_category));
            // If they have that category as their father, I put the main one as their father
            $sql = 'UPDATE '.DDBB_PREFIX.'categories SET id_parent = 1 WHERE id_parent = ?';
            $this->query($sql, array($id_category));
            // I review all the products that have that category as the main one to change it
            $sql = 'SELECT id_product FROM '.DDBB_PREFIX.'products_categories WHERE id_category = ? AND main = 1';
            $result = $this->query($sql, array($id_category));
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $sql = 'UPDATE '.DDBB_PREFIX.'products_categories SET main = 1 WHERE id_product = ? AND id_category != ? LIMIT 1';
                    $this->query($sql, array($row['id_product'], $id_category));
                }
            }
            // I put the products that only have that category in the Main category
            $sql = 'SELECT id_product FROM '.DDBB_PREFIX.'products_categories WHERE id_category = ?';
            $result = $this->query($sql, array($id_category));
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $sql = 'SELECT id_product FROM '.DDBB_PREFIX.'products_categories WHERE id_product = ? LIMIT 2';
                    $result_num = $this->query($sql, array($row['id_product']));
                    // It means that it only has that category
                    if($result_num->num_rows == 1) {
                        $sql = 'INSERT INTO '.DDBB_PREFIX.'products_categories (id_product, id_category, main) VALUES (?, 1, 1)';
                        $this->query($sql, array($row['id_product']));
                    }
                }
            }
            // Delete all category custom routes
            $sql = 'DELETE FROM '.DDBB_PREFIX.'categories_custom_routes WHERE id_category = ?';
            $this->query($sql, array($id_category));
            // Delete the category from products
            $sql = 'DELETE FROM '.DDBB_PREFIX.'products_categories WHERE id_category = ?';
            $this->query($sql, array($id_category));
            // I recreate all category routes
            $this->create_all_category_routes();
            // I recreate all product routes
            $this->create_all_product_routes();
            return array('response' => 'ok');
        }

        public function delete_category_custom_route($id_category_custom_route) {
            $sql = 'DELETE FROM '.DDBB_PREFIX.'categories_custom_routes WHERE id_category_custom_route = ?';
            $this->query($sql, array($id_category_custom_route));
            return array('response' => 'ok');
        }

        public function save_new_category_custom_route() {
            foreach($_POST['routes'] as $value) {
                if($value['route'] != '') {
                    // If it does not have the / at the beginning I add it
                    if(substr($value['route'], 0, 1) != '/') {
                        $value['route'] = '/'.$value['route'];
                    }
                    $category_route = $this->get_category_route($_POST['id_category'], $value['id_language']);
                    $product_route = $category_route.$value['route'];
                    $sql = 'INSERT INTO '.DDBB_PREFIX.'categories_custom_routes (id_category, id_language, route) VALUES (?, ?, ?)';
                    $this->query($sql, array($_POST['id_category'], $value['id_language'], $product_route));
                }
            }
            return array('response' => 'ok');            
        }

        public function save_new_attribute() {
            $sql = 'INSERT INTO '.DDBB_PREFIX.'attributes (id_attribute_type, id_attribute_html, alias) VALUES (?, ?, ?)';
            $this->query($sql, array($_POST['type'], $_POST['view'], $_POST['alias']));
            $id_attribute = $this->db->insert_id;
            foreach($_POST['properties'] as $value) {
                $sql = 'INSERT INTO '.DDBB_PREFIX.'attributes_language (id_attribute, id_language, `name`, `description`) VALUES (?, ?, ?, ?)';
                $this->query($sql, array($id_attribute, $value['id_lang'], $value['name'], $value['description']));
            }
            if(!empty($_POST['values'])) {
                foreach($_POST['values'] as $value) {
                    $this->save_new_attribute_value($id_attribute, $_POST['type'], $value);
                }
            }
            return array('response' => 'ok');
        }

        public function save_edit_attribute() {
            $sql = 'UPDATE '.DDBB_PREFIX.'attributes SET alias = ?, id_attribute_type = ?, id_attribute_html = ? WHERE id_attribute = ? LIMIT 1';
            $this->query($sql, array($_POST['alias'], $_POST['type'], $_POST['view'], $_POST['id_attribute']));
            // I save the attribute properties
            foreach($_POST['properties'] as $value) {
                $sql = 'UPDATE '.DDBB_PREFIX.'attributes_language SET name = ?, description = ? WHERE id_attribute = ? AND id_language = ? LIMIT 1';
                $this->query($sql, array($value['name'], $value['description'], $_POST['id_attribute'], $value['id_lang']));
            }
            if(!empty($_POST['values'])) {
                $value_ids = [];
                foreach($_POST['values'] as $value) {
                    // The value 0 indicates that it is a new value
                    if($value['id_attribute_value'] == 0) {
                        $id_attribute_value = $this->save_new_attribute_value($_POST['id_attribute'], $_POST['type'], $value);
                        array_push($value_ids, $id_attribute_value);
                    } else {
                        $sql = 'UPDATE '.DDBB_PREFIX.'attributes_value SET priority = ?, alias = ? WHERE id_attribute_value = ? LIMIT 1';
                        $this->query($sql, array($value['priority'], $value['alias'], $value['id_attribute_value']));
                        array_push($value_ids, $value['id_attribute_value']);
                    }
                }
                // If it's an image I delete it
                $sql = 'SELECT * FROM '.DDBB_PREFIX.'attributes_value WHERE id_attribute_value NOT IN ('.implode(',', $value_ids).') AND id_attribute = ?';
                $result = $this->query($sql, array($_POST['id_attribute']));
                if($result->num_rows != 0) {
                    while($row = $result->fetch_assoc()) {
                        if($_POST['type'] == 3) {
                            try {
                                if(file_exists(SERVER_PATH.$row['value'])) {
                                    unlink(SERVER_PATH.$row['value']);
                                }
                            } catch(Exception $e) {
                                // Failed to delete the file
                            }            
                        }
                        // I delete its properties
                        $sql = 'DELETE FROM '.DDBB_PREFIX.'attributes_value_language WHERE id_attribute_value = ?';
                        $this->query($sql, array($row['id_attribute_value']));
                    }
                }
                // I delete the values that it no longer uses
                $sql = 'DELETE FROM '.DDBB_PREFIX.'attributes_value WHERE id_attribute_value NOT IN ('.implode(',', $value_ids).') AND id_attribute = ?';
                $this->query($sql, array($_POST['id_attribute']));
                // I delete the related products attribute
                $sql = 'DELETE FROM '.DDBB_PREFIX.'products_related_attributes WHERE id_attribute_value NOT IN ('.implode(',', $value_ids).') AND id_attribute = ?';
                $this->query($sql, array($_POST['id_attribute']));
            }
            return array(
                'response' => 'ok',
                'mensaje' => 'The product has been successfully updated!'
            );
        }

        public function save_new_attribute_value($id_attribute, $type, $value) {
            $sql = 'INSERT INTO '.DDBB_PREFIX.'attributes_value (id_attribute, priority, alias) VALUES (?, ?, ?)';
            $this->query($sql, array($id_attribute, $value['priority'], $value['alias']));
            $id_attribute_value = $this->db->insert_id;
            // If it is an image
            if($type == 3) {
                // I collect the file extension and create the name
                $extension = explode('.', $value['image_name']);
                $name = 'attr-'.$id_attribute.'-'.$id_attribute_value.'.'.end($extension);
                // Saving the image on the server
                $data = explode(',', $value['value']);
                $data = base64_decode($data[1]);
                $image = imagecreatefromstring($data);
                $image_thumbnail = imagescale($image, 100, -1, IMG_SINC);
                // Save new image
                if(end($extension) == 'jpg' || end($extension) == 'jpeg') {
                    imagejpeg($image_thumbnail, IMG_PATH.'/attributes/'.$name);
                } else if(end($extension) == 'png') {
                    imagepng($image_thumbnail, IMG_PATH.'/attributes/'.$name);                
                }
                imagedestroy($image_thumbnail);
                $value['value'] = '/img/attributes/'.$name;
            }
            // Now I save the value
            $sql = 'UPDATE '.DDBB_PREFIX.'attributes_value SET value = ? WHERE id_attribute_value = ? LIMIT 1';
            $this->query($sql, array($value['value'], $id_attribute_value));
            // I create the values of each language empty
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'ct_languages WHERE id_state = 2';
            $result = $this->query($sql);
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $sql = 'INSERT INTO '.DDBB_PREFIX.'attributes_value_language (id_attribute_value, id_language, `name`) VALUES (?, ?, ?)';
                    $this->query($sql, array($id_attribute_value, $row['id_language'], $value['alias']));
                }
            }
            return $id_attribute_value;
        }

        public function get_attribute_values($id_attribute, $type) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'attributes_value WHERE id_attribute = ? ORDER BY priority';
            $result = $this->query($sql, array($id_attribute));
            $html = '';
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $html .= '<div class="list-item" value="'.$row['alias'].'" id-attribute-value="'.$row['id_attribute_value'].'">'.$row['alias'];
                    if($type == 2) {
                        $html .= ' <div class="value-color" style="background-color: '.$row['value'].';">'.$row['value'].'</div>';
                    } elseif($type == 3) {
                        $html .= '<div class="new-value-list-image" style="background-image: url('.PUBLIC_PATH.$row['value'].');"></div>';
                    }
                    $html .=    '<div class="btn-delete-value"><i class="fa-solid fa-trash-can"></i></div>';
                    $html .=    '<div class="btn-edit-value"><i class="fa-solid fa-pencil"></i></div>';
                    $html .= '</div>';
                }
                return array(
                    'response' => 'ok',
                    'html' => $html
                );
            } else {
                return array(
                    'response' => 'error',
                    'mensaje' => 'No values found for this attribute type.'
                );
            }
        }

        public function get_attribute_value_properties($id_attribute_value) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'attributes_value WHERE id_attribute_value = ? LIMIT 1';
            $result = $this->query($sql, array($id_attribute_value));
            $row = $result->fetch_assoc();
            $sql = 'SELECT v.*, l.alias AS alias FROM '.DDBB_PREFIX.'attributes_value_language AS v
                        INNER JOIN '.DDBB_PREFIX.'ct_languages AS l ON l.id_language = v.id_language
                    WHERE v.id_attribute_value = ?';
            $result_properties = $this->query($sql, array($id_attribute_value));
            if($result_properties->num_rows != 0) {
                $html = '<div class="custom-tab pb-20" id="value-properties">';
                $html .=    '<div class="menu">';
                $i = 1;
                while($row_properties = $result_properties->fetch_assoc()) {
                    if($i == 1) {
                        $class = ' class="active"';
                    } else {
                        $class = '';
                    }
                    $html .= '<div id-tab="'.$i.'" id-lang="'.$row_properties['id_language'].'"'.$class.'>'.$row_properties['alias'].'</div>';
                    $i++;
                }
                $html .=    '</div>';
                $html .=    '<div class="content">';
                $result_properties->data_seek(0);
                $i = 1;
                while($row_properties = $result_properties->fetch_assoc()) {
                    if($i == 1) {
                        $class = ' class="active"';
                    } else {
                        $class = '';
                    }
                    $html .= '<div id-tab="'.$i.'"'.$class.'>';
                    $html .=    '<div class="pb-10"><b>Value name</b></div>';
                    $html .=    '<div class="pb-10">';
                    $html .=        '<input type="text" class="w-100 w-100 input-value-name" value="'.$row_properties['name'].'">';
                    $html .=    '</div>';
                    $html .=    '<div class="pb-10"><b>Description</b></div>';
                    $html .=    '<div class="pb-10">';
                    $html .=        '<textarea class="w-100 textarea-value-description" style="height: 100px">'.$row_properties['description'].'</textarea>';
                    $html .=    '</div>';
                    $html .= '</div>';
                    $i++;
                }
                $html .=    '</div>';
                $html .= '</div>';
                return array(
                    'response' => 'ok',
                    'html' => $html,
                    'alias' => $row['alias']
                );
            } else {
                return array(
                    'response' => 'error',
                    'mensaje' => 'No properties found for this value.'
                );
            }
        }

        public function save_attribute_value_properties() {
            $sql = 'UPDATE '.DDBB_PREFIX.'attributes_value SET alias = ? WHERE id_attribute_value = ? LIMIT 1';
            $this->query($sql, array($_POST['alias'], $_POST['id_attribute_value']));
            foreach($_POST['properties'] as $value) {
                $sql = 'UPDATE '.DDBB_PREFIX.'attributes_value_language SET `name` = ?, `description` = ?
                        WHERE id_attribute_value = ? AND id_language  = ? LIMIT 1';
                $this->query($sql, array($value['name'], $value['description'], $_POST['id_attribute_value'], $value['id_lang']));
            }
            return array(
                'response' => 'ok',
                'mensaje' => 'The properties has been successfully updated!'
            );
        }

        public function delete_attribute($id_attribute) {
            // I need to know what type of attribute it is for in case I have to delete images
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'attributes WHERE id_attribute = ? LIMIT 1';
            $result = $this->query($sql, array($id_attribute));
            $row = $result->fetch_assoc();
            $sql = 'DELETE FROM '.DDBB_PREFIX.'attributes WHERE id_attribute = ? LIMIT 1';
            $this->query($sql, array($id_attribute));
            $sql = 'DELETE FROM '.DDBB_PREFIX.'attributes_language WHERE id_attribute = ?';
            $this->query($sql, array($id_attribute));
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'attributes_value WHERE id_attribute = ?';
            $result_values = $this->query($sql, array($id_attribute));
            if($result_values->num_rows != 0) {
                while($row_value = $result_values->fetch_assoc()) {
                    $sql = 'DELETE FROM '.DDBB_PREFIX.'attributes_value_language WHERE id_attribute_value = ?';
                    $this->query($sql, array($row_value['id_attribute_value']));
                    // I delete the related image from the server
                    if($row['id_attribute_type'] == 3) {
                        try {
                            if(file_exists(SERVER_PATH.$row_value['value'])) {
                                unlink(SERVER_PATH.$row_value['value']);
                            }
                        } catch(Exception $e) {
                            // Failed to delete the file
                        }            
                    }
                }
            }
            $sql = 'DELETE FROM '.DDBB_PREFIX.'attributes_value WHERE id_attribute = ?';
            $this->query($sql, array($id_attribute));
            // I delete the attribute of the products
            $sql = 'DELETE FROM '.DDBB_PREFIX.'products_attributes WHERE id_attribute = ? LIMIT 1';
            $this->query($sql, array($id_attribute));
            return array('response' => 'ok');
        }

        public function save_new_code() {
            $amount = $this->parse_float_point($_POST['amount']);
            $minimum = $this->parse_float_point($_POST['minimum']);
            $sql = 'INSERT INTO '.DDBB_PREFIX.'codes
                        (`name`, code, `type`, amount, available, registered, exclude_sales, minimum, per_user, compatible, free_shipping, `start_date`, end_date, id_state)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
            $this->query($sql, array(
                $_POST['name'], $_POST['code'], $_POST['type'], $amount, $_POST['available'], $_POST['registered'], $_POST['exclude'],
                $minimum, $_POST['per_user'], $_POST['compatible'], $_POST['free_shipping'], $_POST['start_date'], $_POST['end_date'], $_POST['id_state']
            ));
            return array('response' => 'ok');
        }

        public function save_edit_code() {
            $amount = $this->parse_float_point($_POST['amount']);
            $minimum = $this->parse_float_point($_POST['minimum']);
            $sql = 'UPDATE '.DDBB_PREFIX.'codes SET `name` = ?, code = ?, `type` = ?, amount = ?, available = ?, registered = ?,
                        exclude_sales = ?, minimum = ?, per_user = ?, compatible = ?, free_shipping = ?, `start_date` = ?, end_date = ?, id_state = ?
                    WHERE id_code = ?';
            $this->query($sql, array(
                $_POST['name'], $_POST['code'], $_POST['type'], $amount, $_POST['available'], $_POST['registered'], $_POST['exclude'],
                $minimum, $_POST['per_user'], $_POST['compatible'], $_POST['free_shipping'], $_POST['start_date'],
                $_POST['end_date'], $_POST['id_state'], $_POST['id_code']
            ));
            return array(
                'response' => 'ok',
                'mensaje' => 'The code has been successfully updated!'
            );
        }

        public function delete_code($id_code) {
            $sql = 'DELETE FROM '.DDBB_PREFIX.'codes WHERE id_code = ? LIMIT 1';
            $this->query($sql, array($id_code));
            return array('response' => 'ok');
        }

        public function add_code_rule() {
            $sql = 'SELECT id_code_rule FROM '.DDBB_PREFIX.'codes_rules WHERE id_code = ?';
            $result = $this->query($sql, array($_POST['id_code']));
            $sql = 'INSERT INTO '.DDBB_PREFIX.'codes_rules (id_code, id_code_rule_type, id_code_rule_add_type) VALUES (?, ?, ?)';
            $this->query($sql, array($_POST['id_code'], $_POST['id_rule_type'], $_POST['id_rule_add_type']));
            $id_code_rule = $this->db->insert_id;
            foreach($_POST['elements'] as $value) {
                $sql = 'INSERT INTO '.DDBB_PREFIX.'codes_rules_elements (id_code_rule, id_element) VALUES (?, ?)';
                $this->query($sql, array($id_code_rule, $value));
            }
            $sql = 'SELECT `name` FROM '.DDBB_PREFIX.'ct_codes_rules_type WHERE id_code_rule_type  = ?';
            $result_type = $this->query($sql, array($_POST['id_rule_type']));
            $row_type = $result_type->fetch_assoc();
            $sql = 'SELECT `name` FROM '.DDBB_PREFIX.'ct_codes_rules_add_type WHERE id_code_rule_add_type = ?';
            $result_add_type = $this->query($sql, array($_POST['id_rule_add_type']));
            $row_add_type = $result_add_type->fetch_assoc();
            $html = '';
            if($result->num_rows == 0) {
                $html .= '<table>';
                $html .= '<thead>';
                $html .=    '<tr>';
                $html .=        '<th style="width: 150px;">Id rule</th>';
                $html .=        '<th class="text-left" style="width: 200px;">Rule type</th>';
                $html .=        '<th class="text-left">Add type</th>';
                $html .=        '<th style="width: 100px;">Elements</th>';
                $html .=        '<th style="width: 200px;"></th>';
                $html .=    '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
            }
            $html .= '<tr>';
            $html .=    '<td class="text-center">'.$id_code_rule.'</td>';
            $html .=    '<td>'.$row_type['name'].'</td>';
            $html .=    '<td>'.$row_add_type['name'].'</td>';
            $html .=    '<td class="text-center">'.count($_POST['elements']).'</td>';
            $html .=    '<td class="text-center">';
            $html .=        '<div class="btn btn-black btn-sm mr-5 btn-edit-code-rule" id-code-rule="'.$id_code_rule.'">Edit</div>';
            $html .=        '<div class="btn btn-black btn-sm btn-delete-code-rule" id-code-rule="'.$id_code_rule.'">Delete</div>';
            $html .=    '</td>';
            $html .= '</tr>';
            if($result->num_rows == 0) {
                $html .= '</tbody>';
                $html .= '</table>';
            }
            return array(
                'response' => 'ok',
                'html' => $html
            );
        }

        public function save_code_rule() {
            $sql = 'UPDATE '.DDBB_PREFIX.'codes_rules SET id_code_rule_type = ?, id_code_rule_add_type = ?
                    WHERE id_code_rule = ?';
            $this->query($sql, array($_POST['id_rule_type'], $_POST['id_rule_add_type'], $_POST['id_code_rule']));
            // Empty all items
            $sql = 'DELETE FROM '.DDBB_PREFIX.'codes_rules_elements WHERE id_code_rule = ?';
            $this->query($sql, array($_POST['id_code_rule']));
            // I reinsert all the new ones
            foreach($_POST['elements'] as $value) {
                $sql = 'INSERT INTO '.DDBB_PREFIX.'codes_rules_elements (id_code_rule, id_element) VALUES (?, ?)';
                $this->query($sql, array($_POST['id_code_rule'], $value));
            }
            return array(
                'response' => 'ok',
                'mensaje' => 'The rule has been successfully updated!'
            );
        }

        public function delete_code_rule($id_code_rule) {
            $sql = 'DELETE FROM '.DDBB_PREFIX.'codes_rules WHERE id_code_rule = ?';
            $this->query($sql, array($id_code_rule));
            $sql = 'DELETE FROM '.DDBB_PREFIX.'codes_rules_elements WHERE id_code_rule = ?';
            $this->query($sql, array($id_code_rule));
            return array('response' => 'ok');
        }

        public function get_code_rule($id_code_rule) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'codes_rules WHERE id_code_rule = ?';
            $result = $this->query($sql, array($id_code_rule));
            if($result->num_rows != 0) {
                $row = $result->fetch_assoc();
                $selected = array();
                $html_selected = '';
                if($row['id_code_rule_type'] == 1) {
                    $sql = 'SELECT e.*, p.alias FROM codes_rules_elements AS e
                                INNER JOIN products AS p ON p.id_product = e.id_element
                            WHERE e.id_code_rule = ?';
                } else if($row['id_code_rule_type'] == 2){
                    $sql = 'SELECT e.*, c.alias FROM codes_rules_elements AS e
                                INNER JOIN categories AS c ON c.id_category = e.id_element
                            WHERE e.id_code_rule = ?';
                }
                $result_elements = $this->query($sql, array($row['id_code_rule']));
                while($row_element = $result_elements->fetch_assoc()) {
                    array_push($selected, $row_element['id_element']);
                    $html_selected .= '<div class="list-item" value="'.$row_element['id_element'].'">'.$row_element['alias'].'</div>';
                }
                $result_elements = $this->get_code_rule_elements_list($row['id_code_rule_type'], $selected);
                return array(
                    'response' => 'ok',
                    'rule' => $row,
                    'html_elements' => $result_elements['html'],
                    'html_selected' => $html_selected
                );
            } else {
                return array('response' => 'error');
            }
        }

        public function get_code_rules($id_code) {
            // Drawing the list of related products
            $sql = 'SELECT r.*, t.name AS rule_type, a.name AS add_type FROM '.DDBB_PREFIX.'codes_rules AS r
                        INNER JOIN '.DDBB_PREFIX.'ct_codes_rules_type AS t ON t.id_code_rule_type = r.id_code_rule_type
                        INNER JOIN '.DDBB_PREFIX.'ct_codes_rules_add_type AS a ON a.id_code_rule_add_type = r.id_code_rule_add_type
                    WHERE r.id_code = ?';
            $result = $this->query($sql, array($id_code));
            $html = '';
            if($result->num_rows != 0) {
                $html = '<table>';
                $html .= '<thead>';
                $html .=    '<tr>';
                $html .=        '<th style="width: 150px;">Id rule</th>';
                $html .=        '<th class="text-left" style="width: 200px;">Rule type</th>';
                $html .=        '<th class="text-left">Add type</th>';
                $html .=        '<th style="width: 100px;">Elements</th>';
                $html .=        '<th style="width: 200px;"></th>';
                $html .=    '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                while($row = $result->fetch_assoc()) {
                    $sql = 'SELECT id_code_rule_element FROM '.DDBB_PREFIX.'codes_rules_elements WHERE id_code_rule = ?';
                    $result_elements = $this->query($sql, array($row['id_code_rule']));
                    $html .= '<tr>';
                    $html .=    '<td class="text-center">'.$row['id_code_rule'].'</td>';
                    $html .=    '<td>'.$row['rule_type'].'</td>';
                    $html .=    '<td>'.$row['add_type'].'</td>';
                    $html .=    '<td class="text-center">'.$result_elements->num_rows.'</td>';
                    $html .=    '<td class="text-center">';
                    $html .=        '<div class="btn btn-black btn-sm mr-5 btn-edit-code-rule" id-code-rule="'.$row['id_code_rule'].'">Edit</div>';
                    $html .=        '<div class="btn btn-black btn-sm btn-delete-code-rule" id-code-rule="'.$row['id_code_rule'].'">Delete</div>';
                    $html .=    '</td>';
                    $html .= '</tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
            } else {
                $html = 'No code rules';
            }
            return array(
                'response' => 'ok',
                'html' => $html
            );
        }

        public function get_code_rule_elements_list($id_rule_type, $selected = array()) {
            if($id_rule_type == 1) {
                $sql = 'SELECT alias, id_product AS id_element FROM '.DDBB_PREFIX.'products';
            } else if($id_rule_type == 2) {
                $sql = 'SELECT alias, id_category AS id_element FROM '.DDBB_PREFIX.'categories';
            }
            $result = $this->query($sql);
            $html = '';
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $active = '';
                    foreach($selected as $value) {
                        if($row['id_element'] == $value) {
                            $active = ' active';
                        }
                    }
                    $html .= '<div class="list-item'.$active.'" value="'.$row['id_element'].'">'.$row['alias'].'</div>';
                }
            }
            return array(
                'response' => 'ok',
                'html' => $html
            );
        }

        public function save_new_shipment() {
            // I check that this alias no longer exists.
            $sql = 'SELECT id_shipping_method FROM shipping_methods WHERE alias = ? LIMIT 1';
            $result = $this->query($sql, $_POST['alias']);
            if($result->num_rows == 0) {
                $sql = 'INSERT INTO shipping_methods (alias, min_order_value, max_order_value, min_order_weight, max_order_weight, id_state)
                    VALUES (?, ?, ?, ?, ?, ?)';
                $this->query($sql, array(
                    $_POST['alias'], $_POST['min_value'], $_POST['max_value'],
                    $_POST['min_weight'], $_POST['max_weight'], $_POST['id_state']
                ));
                return array('response' => 'ok');
            } else {
                return array(
                    'response' => 'error',
                    'message' => 'That alias already exists. Try another one.'
                );
            }
        }

        public function save_edit_shipment() {
            // I check that this alias no longer exists.
            $sql = 'SELECT id_shipping_method FROM shipping_methods WHERE alias = ? AND id_shipping_method != ? LIMIT 1';
            $result = $this->query($sql, array($_POST['alias'], $_POST['id_shipping_method']));
            if($result->num_rows == 0) {
                $sql = 'UPDATE shipping_methods SET alias = ?, min_order_value = ?, max_order_value = ?,
                            min_order_weight = ?, max_order_weight = ?, id_state = ?
                        WHERE id_shipping_method = ? LIMIT 1';
                $this->query($sql, array(
                    $_POST['alias'], $_POST['min_value'], $_POST['max_value'],
                    $_POST['min_weight'], $_POST['max_weight'], $_POST['id_state'],
                    $_POST['id_shipping_method']
                ));
                return array(
                    'response' => 'ok',
                    'message' => 'The shipping method has been successfully updated!'
                );
            } else {
                return array(
                    'response' => 'error',
                    'message' => 'That alias already exists. Try another one.'
                );
            }
        }

        public function save_new_shipping_zone() {
            // I check that this alias no longer exists.
            $sql = 'SELECT id_shipping_zone  FROM shipping_zones WHERE name = ? LIMIT 1';
            $result = $this->query($sql, $_POST['name']);
            if($result->num_rows == 0) {
                $sql = 'INSERT INTO shipping_zones (name, id_state) VALUES (?, ?)';
                $this->query($sql, array($_POST['name'], $_POST['id_state']));
                return array('response' => 'ok');
            } else {
                return array(
                    'response' => 'error',
                    'message' => 'That alias already exists. Try another one.'
                );
            }            
        }

        public function save_new_payment() {
            // I check that this alias no longer exists.
            $sql = 'SELECT id_payment_method FROM payment_methods WHERE alias = ? LIMIT 1';
            $result = $this->query($sql, $_POST['alias']);
            if($result->num_rows == 0) {
                $sql = 'INSERT INTO payment_methods (alias, min_order_value, max_order_value, id_state)
                    VALUES (?, ?, ?, ?)';
                $this->query($sql, array(
                    $_POST['alias'], $_POST['min_value'], $_POST['max_value'], $_POST['id_state']
                ));
                return array('response' => 'ok');
            } else {
                return array(
                    'response' => 'error',
                    'message' => 'That alias already exists. Try another one.'
                );
            }
        }

        public function save_edit_payment() {
            // I check that this alias no longer exists.
            $sql = 'SELECT id_payment_method FROM payment_methods WHERE alias = ? AND id_payment_method != ? LIMIT 1';
            $result = $this->query($sql, array($_POST['alias'], $_POST['id_payment_method']));
            if($result->num_rows == 0) {
                $sql = 'UPDATE payment_methods SET alias = ?, min_order_value = ?, max_order_value = ?, id_state = ?
                        WHERE id_payment_method = ? LIMIT 1';
                $this->query($sql, array(
                    $_POST['alias'], $_POST['min_value'], $_POST['max_value'],
                    $_POST['id_state'], $_POST['id_payment_method']
                ));
                return array(
                    'response' => 'ok',
                    'message' => 'The payment method has been successfully updated!'
                );
            } else {
                return array(
                    'response' => 'error',
                    'message' => 'That alias already exists. Try another one.'
                );
            }
        }

        public function save_edit_user() {
            // We check that this email does not already exist in the database
            $sql = 'SELECT id_user FROM '.DDBB_PREFIX.'users WHERE email = ? AND id_user != ? LIMIT 1';
            $result = $this->query($sql, array($_POST['email'], $_POST['id_user']));
            if($result->num_rows == 0) {
                $sql = 'UPDATE '.DDBB_PREFIX.'users SET name = ?, lastname = ?, email = ?, id_state = ? WHERE id_user = ? LIMIT 1';
                $result = $this->query($sql, array($_POST['name'], $_POST['lastname'], $_POST['email'], $_POST['id_state'], $_POST['id_user']));
                // I Save the main address
                $sql = 'UPDATE '.DDBB_PREFIX.'users_addresses SET main_address = 0 WHERE id_user = ?';
                $this->query($sql, array($_POST['id_user']));
                $sql = 'UPDATE '.DDBB_PREFIX.'users_addresses SET main_address = 1 WHERE id_user_address = ? AND id_user = ?';
                $this->query($sql, array($_POST['id_address_main'], $_POST['id_user']));
                return array(
                    'response' => 'ok',
                    'mensaje' => 'The user has been successfully updated!'
                );
            } else {
                return array(
                    'response' => 'error',
                    'mensaje' => 'The email you are trying to save is already being used by another user.'
                );
            }
        }

        public function get_user_addresses($id_user) {
            $sql = 'SELECT a.*, c.'.strtolower(LANG).' AS country_name FROM '.DDBB_PREFIX.'users_addresses AS a
                        INNER JOIN '.DDBB_PREFIX.'ct_countries AS c ON c.id_country = a.id_country
                    WHERE a.id_user = ?';
            $result = $this->query($sql, array($id_user));
            if($result->num_rows > 0) {
                $html = '<table>';
                $html .= '<thead>';
                $html .=    '<tr>';
                $html .=        '<th width="150">Id address</th>';
                $html .=        '<th class="text-left">Address</th>';
                $html .=        '<th class="text-left">Location</th>';
                $html .=        '<th class="text-left" style="width: 120px;">Postal code</th>';
                $html .=        '<th class="text-left" style="width: 120px;">Country</th>';
                $html .=        '<th style="width: 100px;">Main</th>';
                $html .=        '<th style="width: 200px;"></th>';
                $html .=    '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                while($row = $result->fetch_assoc()) {
                    $selected = ($row['main_address'] == 1) ? ' checked' : '';
                    $html .= '<tr>';
                    $html .=    '<td class="text-center">'.$row['id_user_address'].'</div>';
                    $html .=    '<td>'.$row['address'].'</td>';
                    $html .=    '<td>'.$row['location'].'</td>';
                    $html .=    '<td>'.$row['postal_code'].'</td>';
                    $html .=    '<td>'.$row['country_name'].'</td>';
                    $html .=    '<td class="text-center">';
                    $html .=        '<label class="radio"><input type="radio" name="input-address-main"'.$selected.' value="'.$row['id_user_address'].'"><span class="checkmark"></span></label>';
                    $html .=    '</td>';
                    $html .=    '<td class="text-center">';
                    $html .=        '<div class="btn btn-black btn-sm mr-5 btn-edit-user-address" id-user-address="'.$row['id_user_address'].'">Edit</div>';
                    $html .=        '<div class="btn btn-black btn-sm btn-delete-user-address" id-user-address="'.$row['id_user_address'].'">Delete</div>';
                    $html .=    '</td>';
                    $html .= '</tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
            } else {
                $html = 'No addresses';
            }
            return array(
                'response' => 'ok',
                'html' => $html
            );
        }

        public function get_user_address($id_user_address) {
            // I pick up a single address
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'users_addresses WHERE id_user_address = ? LIMIT 1';
            $result = $this->query($sql, array($id_user_address));
            $row = $result->fetch_assoc();
            $countries_html = $this->get_countries_list($row['id_continent'], $row['id_country']);
            $provinces_html = $this->get_provinces_list($row['id_country'], $row['id_province']);
            return array(
                'response' => 'ok',
                'address' => $row,
                'countries_html' => $countries_html['html'],
                'provinces_html' => $provinces_html['html']
            );
        }

        public function get_countries_list($id_continent, $id_country = null) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'ct_countries WHERE id_continent = ? AND id_state = 2';
            $result = $this->query($sql, array($id_continent));
            $html = '';
            while($row = $result->fetch_assoc()) {
                if($row['id_country'] == $id_country) {
                    $selected = ' selected';
                } else {
                    $selected = '';
                }
                $html .= '<option value="'.$row['id_country'].'"'.$selected.'>'.$row[strtolower(LANG)].'</option>';
            }
            return array(
                'response' => 'ok',
                'html' => $html
            );
        }

        public function get_provinces_list($id_country, $id_province = null) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'ct_provinces WHERE id_country = ? AND id_state = 2';
            $result = $this->query($sql, array($id_country));
            $html = '';
            while($row = $result->fetch_assoc()) {
                if($row['id_province'] == $id_province) {
                    $selected = ' selected';
                } else {
                    $selected = '';
                }
                $html .= '<option value="'.$row['id_province'].'"'.$selected.'>'.$row[strtolower(LANG)].'</option>';
            }
            return array(
                'response' => 'ok',
                'html' => $html
            );
        }

        public function save_edit_user_address() {
            $sql = 'UPDATE '.DDBB_PREFIX.'users_addresses SET id_continent = ?, id_country = ?, id_province = ?,
                        `address` = ?, location = ?, postal_code = ?, telephone = ?
                    WHERE id_user_address = ? LIMIT 1';
            $this->query($sql, array($_POST['id_continent'], $_POST['id_country'], $_POST['id_province'], $_POST['address'],
                $_POST['location'], $_POST['postal_code'], $_POST['telephone'], $_POST['id_user_address']
            ));
            return array(
                'response' => 'ok',
                'mensaje' => 'The user address has been successfully saved!'
            );
        }

        public function delete_user_address($id_user_address) {
            $sql = 'DELETE FROM '.DDBB_PREFIX.'users_addresses WHERE id_user_address = ? LIMIT 1';
            $this->query($sql, array($id_user_address));
            return array('response' => 'ok');
        }

        public function close_user_sessions($id_user) {
            $sql = 'UPDATE '.DDBB_PREFIX.'users SET remember_code = ? WHERE id_user = ? LIMIT 1';
            $this->query($sql, array(uniqid(), $id_user));
            return array(
                'response' => 'ok',
                'mensaje' => 'The user sessions has been successfully closed!'
            );
        }

        public function send_validation_email($id_user) {
            $sql = 'SELECT email, validation_code FROM '.DDBB_PREFIX.'users WHERE id_user = ? LIMIT 1';
            $result = $this->query($sql, array($id_user));
            $row = $result->fetch_assoc();
            // We build the validation url for the email
            $link = URL.'/validate-email?code='.$row['validation_code'];
            $title = 'Validate your account from this email';
            $body = '<a href="'.$link.'">'.$link.'</a>';
            $this->send_email($row['email'], $title, $body);
            return array(
                'response' => 'ok',
                'mensaje' => 'The validation email has been sent correctly!',
                'link' => $link
            );
        }

        public function delete_user($id_user) {
            $sql = 'DELETE FROM '.DDBB_PREFIX.'users WHERE id_user = ? LIMIT 1';
            $this->query($sql, array($id_user));
            return array('response' => 'ok');
        }

        public function save_new_admin_user() {
            // I check if the email is already in use
            $sql = 'SELECT id_admin FROM '.DDBB_PREFIX.'users_admin WHERE email = ? LIMIT 1';
            $result = $this->query($sql, array($_POST['email']));
            if($result->num_rows == 0) {
                $sql = 'INSERT INTO '.DDBB_PREFIX.'users_admin (name, lastname, email, pass, id_admin_type) VALUES (?, ?, ?, ?, ?)';
                $result = $this->query($sql, array($_POST['name'], $_POST['lastname'], $_POST['email'], md5($_POST['pass1']), $_POST['id_admin_type']));
                return array('response' => 'ok');
            } else {
                return array(
                    'response' => 'error',
                    'mensaje' => 'The indicated email is already in use by another user'
                );
            }
        }

        public function save_edit_admin_user() {
            $sql = 'UPDATE '.DDBB_PREFIX.'users_admin SET name = ?, lastname = ?, email = ?, id_admin_type = ?, id_state = ? WHERE id_admin = ? LIMIT 1';
            $this->query($sql, array($_POST['name'], $_POST['lastname'], $_POST['email'], $_POST['id_admin_type'], $_POST['id_state'], $_POST['id_admin']));
            if($_POST['pass1'] != '' && strlen($_POST['pass1']) >= 8) {
                $sql = 'UPDATE '.DDBB_PREFIX.'users_admin SET pass = ? WHERE id_admin = ? LIMIT 1';
                $this->query($sql, array(md5($_POST['pass1']), $_POST['id_admin']));
            }
            return array(
                'response' => 'ok',
                'mensaje' => 'The admin user has been update correctly!',
            );
        }

        public function close_admin_user_sessions($id_admin) {
            $sql = 'UPDATE '.DDBB_PREFIX.'users_admin SET remember_code = ? WHERE id_admin = ? LIMIT 1';
            $this->query($sql, array(uniqid(), $id_admin));
            return array(
                'response' => 'ok',
                'mensaje' => 'The admin user sessions has been successfully closed!'
            );
        }

        public function delete_admin_user($id_admin) {
            $sql = 'DELETE FROM '.DDBB_PREFIX.'users_admin WHERE id_admin = ? LIMIT 1';
            $this->query($sql, array($id_admin));
            return array('response' => 'ok');
        }

    }
    
?>