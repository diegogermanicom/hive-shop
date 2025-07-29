<?php

    /**
     * @author Diego Martín
     * @copyright Hive®
     * @version 1.0
     * @lastUpdated 2025
     */

    class AdminProductAjax extends AdminModel {

        public $name_page;

        function __construct($name_page = 'default-page') {
            parent::__construct();
            $this->name_page = $name_page;
        }
    
        private function check_product_slug($categories, $slug, $exempt_id_product = 0) {
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

        private function get_related_attributes_string($id_product_related) {
            $sql = 'SELECT r.*, a.alias AS alias_attribute, v.alias AS alias_value FROM '.DDBB_PREFIX.'products_related_attributes AS r
                        INNER JOIN '.DDBB_PREFIX.'attributes AS a ON a.id_attribute  = r.id_attribute
                        INNER JOIN '.DDBB_PREFIX.'attributes_value AS v ON v.id_attribute_value = r.id_attribute_value
                    WHERE r.id_product_related = ?';
            $result = $this->query($sql, $id_product_related);
            $html = '';
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $html .= $row['alias_attribute'].' '.$row['alias_value'].' - ';
                }
                $html = substr($html, 0, -3);
            } else {
                $html .= 'No attributes';
            }
            return $html;
        }

        private function check_product_main_hover_image($id_product) {
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
                        'message' => 'The slug already exists in the selected categories.'
                    );
                }
            }
            // I save the product data
            $_POST['price'] = $this->parse_float_point($_POST['price']);
            $_POST['weight'] = $this->parse_float_point($_POST['weight']);
            if($_POST['id_state'] == 2) {
                $publicDate = 'NOW()';
            } else {
                $publicDate = 'NULL';
            }
            $sql = 'INSERT INTO '.DDBB_PREFIX.'products (id_product_view, price, weight, id_tax_type, alias, id_state, public_date)
                    VALUES (?, ?, ?, ?, ?, ?, '.$publicDate.')';
            $this->query($sql, array(
                $_POST['id_view'],
                $_POST['price'],
                $_POST['weight'],
                $_POST['id_tax_type'],
                $_POST['alias'],
                $_POST['id_state']
            ));
            $id_product = $this->db->insert_id;
            // Saving categories
            foreach($_POST['categories'] as $value) {
                $main = ($_POST['main_category'] == $value) ? 1 : 0;
                $sql = 'INSERT INTO '.DDBB_PREFIX.'products_categories (id_product, id_category, main) VALUES (?, ?, ?)';
                $this->query($sql, array(
                    $id_product,
                    $value,
                    $main
                ));
            }
            // Saving attributes
            if(!empty($_POST['attributes'])) {
                for($i = 0; $i < count($_POST['attributes']); $i++) {
                    $sql = 'INSERT INTO '.DDBB_PREFIX.'products_attributes (id_product, id_attribute, priority) VALUES (?, ?, ?)';
                    $this->query($sql, array(
                        $id_product,
                        $_POST['attributes'][$i],
                        ($i + 1)
                    ));
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
                $this->query($sql, array(
                    $id_product,
                    $_POST['properties'][$i]['id_lang'],
                    $_POST['properties'][$i]['name'],
                    $_POST['properties'][$i]['description'],
                    $_POST['properties'][$i]['slug'],
                    $_POST['meta_data'][$i]['meta_title'],
                    $_POST['meta_data'][$i]['meta_description'],
                    $_POST['meta_data'][$i]['meta_keywords']
                ));
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
                    $this->query($sql, array(
                        $id_product,
                        $id_image,
                        ($i + 1)
                    ));
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

        public function delete_product_server_image($id_image) {
            // I get the url to delete the file from the server
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'images WHERE id_image = ? LIMIT 1';
            $result = $this->query($sql, array($id_image));
            $row = $result->fetch_assoc();
            $path = IMG_PATH.'/products/'.$row['name'];
            try {
                if(file_exists($path)) {
                    unlink($path);
                }
            } catch(Exception $e) {
                // Failed to delete the file
                Utils::errorLog('Error deleting image from server: '.$path);
            }
            $pathThumb = IMG_PATH.'/products/thumbnails/'.$row['name'];
            try {
                if(file_exists($pathThumb)) {
                    unlink($pathThumb);
                }
            } catch(Exception $e) {
                // Failed to delete the file
                Utils::errorLog('Error deleting image from server: '.$pathThumb);
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

        public function save_product_main_image($id_product, $id_product_image) {
            $sql = 'UPDATE products SET main_image = ? WHERE id_product = ? LIMIT 1';
            $this->query($sql, array($id_product_image, $id_product));
            // It cannot be main and hover at the same time
            $sql = 'UPDATE products SET hover_image = 0 WHERE id_product = ? AND hover_image = ? LIMIT 1';
            $this->query($sql, array($id_product, $id_product_image));
            $this->check_product_main_hover_image($id_product);
            return array(
                'response' => 'ok',
                'message' => 'The image has been set as main successfully!'
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
                'message' => 'The image has been set as hover successfully!'
            );
        }

        private function save_product_image($image64, $name) {
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
            $sql = 'SELECT p.*, s.name AS state_name, CASE WHEN ISNULL(i.num_images) THEN 0 ELSE i.num_images END AS num_images
                    FROM '.DDBB_PREFIX.'products_related AS p
                        INNER JOIN '.DDBB_PREFIX.'ct_states AS s ON s.id_state = p.id_state
                        LEFT JOIN (
                            SELECT COUNT(id_products_related_image) AS num_images, id_product_related
                            FROM '.DDBB_PREFIX.'products_related_images GROUP BY id_product_related
                        ) AS i ON i.id_product_related = p.id_product_related
                    WHERE p.id_product = ? ORDER BY p.id_product_related';
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
                                'message' => 'The related product already exists.'
                            );        
                        }
                    }
                } else {
                    // Already have a product with no attributes
                    return array(
                        'response' => 'error',
                        'message' => 'The related product without attributes already exists.'
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
                $_POST['id_product'],
                $_POST['stock'],
                $_POST['price_change'],
                $_POST['weight_change'],
                $main,
                $_POST['id_state']
            ));
            $id_related = $this->db->insert_id;
            // I save the attributes
            if(!empty($_POST['attributes'])) {
                foreach($_POST['attributes'] as $i => $value) {
                    $sql = 'INSERT INTO '.DDBB_PREFIX.'products_related_attributes (id_product_related, id_attribute, id_attribute_value) VALUES (?, ?, ?)';
                    $this->query($sql, array(
                        $id_related,
                        $value['id_attribute'],
                        $value['id_value']
                    ));
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
                'message' => 'The related product has been successfully added!'
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
                $_POST['stock'],
                $price_change,
                $weight_change,
                $_POST['id_state'],
                $offer, $_POST['offer_start'],
                $_POST['offer_end'],
                $_POST['id_product_related']
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
                'message' => 'The related product has been successfully updated!'
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

        public function save_edit_product() {
            // I check that the slug with the same category does not already exist;
            foreach($_POST['properties'] as $value) {
                if($this->check_product_slug($_POST['categories'], $value['slug'], $_POST['id_product'])) {
                    return array(
                        'response' => 'error',
                        'message' => 'The slug already exists in the selected categories.'
                    );
                }
            }
            // I save the product data
            $_POST['price'] = $this->parse_float_point($_POST['price']);
            $_POST['weight'] = $this->parse_float_point($_POST['weight']);
            if($_POST['id_state'] == 2) {
                $publicDate = 'NOW()';
            } else {
                $publicDate = 'NULL';
            }
            $sql = 'UPDATE '.DDBB_PREFIX.'products SET id_product_view = ?, price = ?, weight = ?, id_tax_type = ? ,alias = ?, id_state = ?,
                    public_date = '.$publicDate.' WHERE id_product = ?';
            $this->query($sql, array(
                $_POST['id_view'],
                $_POST['price'],
                $_POST['weight'],
                $_POST['id_tax_type'],
                $_POST['alias'],
                $_POST['id_state'],
                $_POST['id_product']
            ));
            // Saving categories
            $sql = 'DELETE FROM '.DDBB_PREFIX.'products_categories WHERE id_product = ?';
            $this->query($sql, array($_POST['id_product']));
            foreach($_POST['categories'] as $value) {
                $main = ($_POST['main_category'] == $value) ? 1 : 0;
                $sql = 'INSERT INTO '.DDBB_PREFIX.'products_categories (id_product, id_category, main) VALUES (?, ?, ?)';
                $this->query($sql, array(
                    $_POST['id_product'],
                    $value,
                    $main
                ));
            }
            // Saving attributes
            $sql = 'DELETE FROM '.DDBB_PREFIX.'products_attributes WHERE id_product = ?';
            $this->query($sql, array($_POST['id_product']));
            if(!empty($_POST['attributes'])) {
                for($i = 0; $i < count($_POST['attributes']); $i++) {
                    $sql = 'INSERT INTO '.DDBB_PREFIX.'products_attributes (id_product, id_attribute, priority) VALUES (?, ?, ?)';
                    $this->query($sql, array(
                        $_POST['id_product'],
                        $_POST['attributes'][$i],
                        ($i + 1)
                    ));
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
                $this->query($sql, array(
                    $_POST['properties'][$i]['name'],
                    $_POST['properties'][$i]['description'],
                    $_POST['properties'][$i]['slug'],
                    $_POST['meta_data'][$i]['meta_title'],
                    $_POST['meta_data'][$i]['meta_description'],
                    $_POST['meta_data'][$i]['meta_keywords'],
                    $_POST['id_product'],
                    $_POST['properties'][$i]['id_lang']));
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
                        $this->query($sql, array(
                            $_POST['id_product'],
                            $_POST['images'][$i]['id_image'],
                            $priority));
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
                'message' => 'The product has been successfully updated!',
                'properties' => $_POST['properties']
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
                        $this->delete_product_server_image($row['id_image']);
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
                    $this->query($sql, array(
                        $_POST['id_product'],
                        $_POST['id_category'],
                        $value['id_language'],
                        $product_route
                    ));
                }
            }
            return array('response' => 'ok');
        }

        public function delete_product_custom_route($id_product_custom_route) {
            $sql = 'DELETE FROM '.DDBB_PREFIX.'products_custom_routes WHERE id_product_custom_route = ?';
            $this->query($sql, array($id_product_custom_route));
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
            $result = $this->query($sql, $id_product);
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

    }

?>