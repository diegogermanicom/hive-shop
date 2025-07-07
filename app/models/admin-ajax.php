<?php

    /**
     * @author Diego Martín
     * @copyright Hive®
     * @version 1.0
     * @lastUpdated 2025
     */

    class AdminAjax extends AdminModel {

        public $name_page;

        function __construct($name_page = 'default-page') {
            parent::__construct();
            $this->name_page = $name_page;
        }

        public function create_new_sitemap() {
            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            foreach(LANGUAGES as $lang) {
                $xml .= '<sitemap>';
                $xml .=     '<loc>'.URL.'/sitemap-'.$lang.'.xml</loc>';
                $xml .=     '<lastmod>'.date('Y-m-d').'</lastmod>';
                $xml .= '</sitemap>';
                $xmlLang = '<?xml version="1.0" encoding="UTF-8"?>';
                $xmlLang .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
                foreach(ROUTES as $alias) {
                    foreach($alias as $route) {
                        if($route['language'] == $lang) {
                            $xmlLang .= '<url>';
                            $xmlLang .=     '<loc>'.URL.$route['route'].'</loc>';
                            $xmlLang .=     '<lastmod>'.date('Y-m-d').'</lastmod>';
                            $xmlLang .=     '<changefreq>monthly</changefreq>';
                            $xmlLang .=     '<priority>1</priority>';
                            $xmlLang .= '</url>';    
                        }
                    }
                }
                $xmlLang .= '</urlset>';
                $file = 'sitemap-'.$lang.'.xml';
                $result = file_put_contents(SERVER_PATH.'/'.$file, $xmlLang);
                if($result === false) {
                    return array(
                        'response' => 'error',
                        'title' => 'Error!',
                        'message' => 'An error occurred while saving the file '.$file.'.'
                    );        
                }
            }
            $xml .= '</sitemapindex>';
            $result = file_put_contents(SERVER_PATH.'/sitemap-index.xml', $xml);
            if($result === false) {
                return array(
                    'response' => 'error',
                    'title' => 'Error!',
                    'message' => 'An error occurred while saving the file <b>sitemap-index.xml</b>.'
                );        
            }
            return array(
                'response' => 'ok',
                'title' => 'Error!',
                'message' => 'The sitemap files have been created successfully.'
            );
        }

        private function create_all_category_routes() {
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
                    $this->query($sql, array(
                        $row_category['id_category'],
                        $row_language['id_language'],
                        $category_route
                    ));
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

        private function check_category_slug($id_parent, $slug, $exempt_id_category = 0) {
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

        public function save_new_category() {
            // I check that the slug does not already exist at the same level;
            foreach($_POST['properties'] as $value) {
                if($this->check_category_slug($_POST['id_parent'], $value['slug'])) {
                    return array(
                        'response' => 'error',
                        'message' => 'The slug already exists in the selected category level.'
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
                $this->query($sql, array(
                    $id_category,
                    $_POST['properties'][$i]['id_lang'],
                    $_POST['properties'][$i]['name'],
                    $_POST['properties'][$i]['description'],
                    $_POST['properties'][$i]['slug'],
                    $_POST['meta_data'][$i]['meta_title'],
                    $_POST['meta_data'][$i]['meta_description'],
                    $_POST['meta_data'][$i]['meta_keywords']
                ));
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
                        'message' => 'The slug already exists in the selected category level.'
                    );
                }
            }
            $sql = 'UPDATE '.DDBB_PREFIX.'categories SET id_parent = ?, id_category_view = ?, alias = ?, id_state = ? WHERE id_category = ?';
            $this->query($sql, array(
                $_POST['id_parent'],
                $_POST['id_view'],
                $_POST['alias'],
                $_POST['id_state'],
                $_POST['id_category']
            ));
            // Saving properties
            for($i = 0; $i < count($_POST['properties']); $i++) {
                if($_POST['properties'][$i]['slug'] == '') {
                    $_POST['properties'][$i]['slug'] = $_POST['alias'].'-'.$_POST['id_category'];
                }
                $_POST['properties'][$i]['slug'] = $this->parse_slug($_POST['properties'][$i]['slug']);
                $sql = 'UPDATE '.DDBB_PREFIX.'categories_language SET `name` = ?, `description` = ?, slug = ?, meta_title = ?, meta_description = ?, meta_keywords = ?
                        WHERE id_category = ? AND id_language = ?';
                $this->query($sql, array(
                    $_POST['properties'][$i]['name'],
                    $_POST['properties'][$i]['description'],
                    $_POST['properties'][$i]['slug'],
                    $_POST['meta_data'][$i]['meta_title'],
                    $_POST['meta_data'][$i]['meta_description'],
                    $_POST['meta_data'][$i]['meta_keywords'],
                    $_POST['id_category'],
                    $_POST['properties'][$i]['id_lang']
                ));
            }
            // I recreate all category routes
            $this->create_all_category_routes();
            // I recreate all product routes
            $this->create_all_product_routes();
            return array(
                'response' => 'ok',
                'message' => 'The product has been successfully updated!'
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
                    $this->query($sql, array(
                        $_POST['id_category'],
                        $value['id_language'],
                        $product_route
                    ));
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
                $this->query($sql, array(
                    $id_attribute,
                    $value['id_lang'],
                    $value['name'],
                    $value['description']
                ));
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
            $this->query($sql, array(
                $_POST['alias'],
                $_POST['type'],
                $_POST['view'],
                $_POST['id_attribute']
            ));
            // I save the attribute properties
            foreach($_POST['properties'] as $value) {
                $sql = 'UPDATE '.DDBB_PREFIX.'attributes_language SET name = ?, description = ? WHERE id_attribute = ? AND id_language = ? LIMIT 1';
                $this->query($sql, array(
                    $value['name'],
                    $value['description'],
                    $_POST['id_attribute'],
                    $value['id_lang']
                ));
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
                            $path = SERVER_PATH.$row['value'];
                            try {
                                if(file_exists($path)) {
                                    unlink($path);
                                }
                            } catch(Exception $e) {
                                // Failed to delete the file
                                Utils::errorLog('Error deleting image from server: '.$path);
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
                'message' => 'The product has been successfully updated!'
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
                    'message' => 'No values found for this attribute type.'
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
                    'message' => 'No properties found for this value.'
                );
            }
        }

        public function save_attribute_value_properties() {
            $sql = 'UPDATE '.DDBB_PREFIX.'attributes_value SET alias = ? WHERE id_attribute_value = ? LIMIT 1';
            $this->query($sql, array($_POST['alias'], $_POST['id_attribute_value']));
            foreach($_POST['properties'] as $value) {
                $sql = 'UPDATE '.DDBB_PREFIX.'attributes_value_language SET `name` = ?, `description` = ?
                        WHERE id_attribute_value = ? AND id_language  = ? LIMIT 1';
                $this->query($sql, array(
                    $value['name'],
                    $value['description'],
                    $_POST['id_attribute_value'],
                    $value['id_lang']
                ));
            }
            return array(
                'response' => 'ok',
                'message' => 'The properties has been successfully updated!'
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
                        $path = SERVER_PATH.$row_value['value'];
                        try {
                            if(file_exists($path)) {
                                unlink($path);
                            }
                        } catch(Exception $e) {
                            // Failed to delete the file
                            Utils::errorLog('Error deleting image from server: '.$path);
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
                $_POST['name'],
                $_POST['code'],
                $_POST['type'],
                $amount,
                $_POST['available'],
                $_POST['registered'],
                $_POST['exclude'],
                $minimum, $_POST['per_user'],
                $_POST['compatible'],
                $_POST['free_shipping'],
                $_POST['start_date'],
                $_POST['end_date'],
                $_POST['id_state']
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
                $_POST['name'],
                $_POST['code'],
                $_POST['type'],
                $amount,
                $_POST['available'],
                $_POST['registered'],
                $_POST['exclude'],
                $minimum, $_POST['per_user'],
                $_POST['compatible'],
                $_POST['free_shipping'],
                $_POST['start_date'],
                $_POST['end_date'],
                $_POST['id_state'],
                $_POST['id_code']
            ));
            return array(
                'response' => 'ok',
                'message' => 'The code has been successfully updated!'
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
            $this->query($sql, array(
                $_POST['id_code'],
                $_POST['id_rule_type'],
                $_POST['id_rule_add_type']
            ));
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
                'message' => 'The rule has been successfully updated!'
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

        public function create_order_from_cart($id_cart) {
            $sql = '';
            return array('response' => 'ok');                
        }

        public function save_edit_user() {
            // We check that this email does not already exist in the database
            $sql = 'SELECT id_user FROM '.DDBB_PREFIX.'users WHERE email = ? AND id_user != ? LIMIT 1';
            $result = $this->query($sql, array($_POST['email'], $_POST['id_user']));
            if($result->num_rows == 0) {
                $sql = 'UPDATE '.DDBB_PREFIX.'users SET name = ?, lastname = ?, email = ?, id_state = ? WHERE id_user = ? LIMIT 1';
                $result = $this->query($sql, array(
                    $_POST['name'],
                    $_POST['lastname'],
                    $_POST['email'],
                    $_POST['id_state'],
                    $_POST['id_user']
                ));
                // I Save the main address
                $sql = 'UPDATE '.DDBB_PREFIX.'users_addresses SET main_address = 0 WHERE id_user = ?';
                $this->query($sql, array($_POST['id_user']));
                $sql = 'UPDATE '.DDBB_PREFIX.'users_addresses SET main_address = 1 WHERE id_user_address = ? AND id_user = ?';
                $this->query($sql, array($_POST['id_address_main'], $_POST['id_user']));
                return array(
                    'response' => 'ok',
                    'message' => 'The user has been successfully updated!'
                );
            } else {
                return array(
                    'response' => 'error',
                    'message' => 'The email you are trying to save is already being used by another user.'
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
            $this->query($sql, array(
                $_POST['id_continent'],
                $_POST['id_country'],
                $_POST['id_province'],
                $_POST['address'],
                $_POST['location'],
                $_POST['postal_code'],
                $_POST['telephone'],
                $_POST['id_user_address']
            ));
            return array(
                'response' => 'ok',
                'message' => 'The user address has been successfully saved!'
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
                'message' => 'The user sessions has been successfully closed!'
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
                'message' => 'The validation email has been sent correctly!',
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
                $result = $this->query($sql, array(
                    $_POST['name'],
                    $_POST['lastname'],
                    $_POST['email'],
                    md5($_POST['pass1']),
                    $_POST['id_admin_type']
                ));
                return array('response' => 'ok');
            } else {
                return array(
                    'response' => 'error',
                    'message' => 'The indicated email is already in use by another user'
                );
            }
        }

        public function save_edit_admin_user() {
            $sql = 'UPDATE '.DDBB_PREFIX.'users_admin SET name = ?, lastname = ?, email = ?, id_admin_type = ?, id_state = ? WHERE id_admin = ? LIMIT 1';
            $this->query($sql, array(
                $_POST['name'],
                $_POST['lastname'],
                $_POST['email'],
                $_POST['id_admin_type'],
                $_POST['id_state'],
                $_POST['id_admin']
            ));
            if($_POST['pass1'] != '' && strlen($_POST['pass1']) >= 8) {
                $sql = 'UPDATE '.DDBB_PREFIX.'users_admin SET pass = ? WHERE id_admin = ? LIMIT 1';
                $this->query($sql, array(md5($_POST['pass1']), $_POST['id_admin']));
            }
            return array(
                'response' => 'ok',
                'message' => 'The admin user has been update correctly!',
            );
        }

        public function close_admin_user_sessions($id_admin) {
            $sql = 'UPDATE '.DDBB_PREFIX.'users_admin SET remember_code = ? WHERE id_admin = ? LIMIT 1';
            $this->query($sql, array(uniqid(), $id_admin));
            return array(
                'response' => 'ok',
                'message' => 'The admin user sessions has been successfully closed!'
            );
        }

        public function delete_admin_user($id_admin) {
            $sql = 'DELETE FROM '.DDBB_PREFIX.'users_admin WHERE id_admin = ? LIMIT 1';
            $this->query($sql, array($id_admin));
            return array('response' => 'ok');
        }

    }
    
?>