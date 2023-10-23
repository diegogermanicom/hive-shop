<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2023
     */   

    class AppAjax extends AppModel {

        public $name_page;

        function __construct($name_page = 'default-page') {
            parent::__construct();
            $this->name_page = $name_page;
        }

        public function save_newsletter($email) {
            $sql = 'SELECT id_newsletter FROM '.DDBB_PREFIX.'newsletters WHERE email = ? LIMIT 1';
            $result = $this->query($sql, array($email));
            if($result->num_rows == 0) {
                $validation_code = uniqid();
                $sql = 'INSERT INTO '.DDBB_PREFIX.'newsletters (email, validation_code) VALUES (?, ?)';
                $result = $this->query($sql, array($email, $validation_code));
            } else {
                $sql = 'UPDATE '.DDBB_PREFIX.'newsletters SET status = 1 WHERE email = ? LIMIT 1';
                $result = $this->query($sql, array($email));
            }
            return array(
                'response' => 'ok',
                'title' => LANGTXT['newsletter-ok-title'],
                'description' => LANGTXT['newsletter-ok-description']
            );
        }

        public function register($email, $name, $lastname, $pass, $newsletter) {
            $sql = 'SELECT id_user FROM '.DDBB_PREFIX.'users WHERE email = ? LIMIT 1';
            $result = $this->query($sql, array($email));
            if($result->num_rows == 0) {
                $sql = 'INSERT INTO '.DDBB_PREFIX.'users (email, `name`, lastname, pass, validation_code, ip_register) VALUES (?, ?, ?, ?, ?, ?)';
                $this->query($sql, array($email, $name, $lastname, md5($pass), uniqid(), $this->get_ip()));
                // If you sign up for the newsletter
                if($newsletter == 1) {
                    $validation_code = uniqid();
                    $sql = 'INSERT INTO '.DDBB_PREFIX.'newsletters (email, validation_code) VALUES (?, ?)';
                    $result = $this->query($sql, array($email, $validation_code));
                }
                if(isset($_POST['checkout']) && $_POST['checkout'] == 1) {
                    if(LANG == 'en') {
                        $url = PUBLIC_ROUTE.'/checkout?register';
                    } else if(LANG == 'es') {
                        $url = PUBLIC_ROUTE.'/tramitar-pedido?register';
                    }
                } else {
                    $url = PUBLIC_ROUTE.'/?register';
                }
                return array(
                    'response' => 'ok',
                    'url' => $url
                );
            } else {
                return array(
                    'response' => 'error',
                    'mensaje' => LANGTXT['error-register']
                );    
            }
        }

        public function choose_language($language) {
            setcookie('lang', $language, time() + (24 * 60 * 60 * 365), PUBLIC_PATH.'/'); // 1 año
            $_COOKIE['lang'] = $language;
            return array('response' => 'ok');
        }

        public function get_product_related() {
            $sql = 'SELECT r.*, p.price AS price FROM '.DDBB_PREFIX.'products_related AS r
                        INNER JOIN '.DDBB_PREFIX.'products AS p ON p.id_product = r.id_product
                    WHERE p.id_product = ? AND p.id_state = 2 AND r.id_state = 2';
            $result = $this->query($sql, array($_POST['id_product']));
            if($result->num_rows != 0) {
                $related = null;
                // I collect the id of the images that are currently being used to know if they need to be refreshed
                $sql = 'SELECT id_product_image FROM '.DDBB_PREFIX.'products_related_images WHERE id_product_related = ?';
                $result_img = $this->query($sql, array($_POST['id_current_product_relared']));
                $image_ids1 = array();
                while($row_img = $result_img->fetch_assoc()) {
                    array_push($image_ids1, $row_img['id_product_image']);
                }
                while($row = $result->fetch_assoc()) {
                    // I search for the related product that matches those attribute values
                    $sql = 'SELECT id_attribute_value FROM '.DDBB_PREFIX.'products_related_attributes
                            WHERE id_attribute_value IN ('.implode(',', $_POST['id_attribute_values']).') AND id_product_related = ?';
                    $result_attr = $this->query($sql, array($row['id_product_related']));
                    if($result_attr->num_rows == count($_POST['id_attribute_values'])) {
                        $related = $row;
                        // I adjust the price if there is a variant or offer
                        $related['price'] += $related['price_change'];
                        $related['price'] = number_format(floatval($related['price']), 2, ',', '.');
                        $related['price'] .= ' €';
                        // I check if they use the same images so as not to reload them
                        $sql = 'SELECT id_product_image FROM products_related_images WHERE id_product_related = ?';
                        $result_img = $this->query($sql, array($related['id_product_related']));
                        $image_ids2 = array();
                        while($row_img = $result_img->fetch_assoc()) {
                            array_push($image_ids2, $row_img['id_product_image']);
                        }
                        $diff = array_diff($image_ids1, $image_ids2);
                        if(count($image_ids1) == count($image_ids2) && empty($diff)) {
                            $related['images'] = null;
                        } else {
                            $related['images'] = $this->get_product_related_images($related['id_product_related']);
                        }
                        break;
                    }
                }
                if($related != null) {
                    return array(
                        'response' => 'ok',
                        'product_related' => $related,
                    );        
                }
            }
            return array(
                'response' => 'error',
                'mensaje' => 'There is no product with those attributes'                    
            );
        }

        public function get_product_related_images($id_product_related) {
            // I collect the images of the related products
            $sql = 'SELECT i.url FROM '.DDBB_PREFIX.'products_related_images AS r
                        INNER JOIN '.DDBB_PREFIX.'products_images AS p ON p.id_product_image = r.id_product_image
                        INNER JOIN '.DDBB_PREFIX.'images AS i ON i.id_image = p.id_image
                    WHERE r.id_product_related = ? ORDER BY p.priority';
            $result = $this->query($sql, array($id_product_related));
            if($result->num_rows != 0) {
                $html = '';
                $delay = 1;
                while($row = $result->fetch_assoc()) {
                    $html .= '<div class="col-12 col-lg-6">';
                    $html .=    '<div class="item animate animate-opacity animate-d-'.$delay.'">';
                    $html .=        '<img src="'.PUBLIC_PATH.$row['url'].'">';
                    $html .=    '</div>';
                    $html .= '</div>';
                    $delay++;
                }
            } else {
                // If you don't have photos
                $html = '';
            }
            return array(
                'desktop' => $html,
                'mobile' => ''
            );
        }

        public function add_cart() {
            // If you already have the product in the cart, I will add 1 to the amount
            $sql = 'SELECT id_cart FROM '.DDBB_PREFIX.'carts_products WHERE id_cart = ? AND id_product = ? AND id_product_related = ? LIMIT 1';
            $result = $this->query($sql, array($_COOKIE['id_cart'], $_POST['id_product'], $_POST['id_product_related']));
            if($result->num_rows == 0) {
                $sql = 'INSERT INTO '.DDBB_PREFIX.'carts_products (id_cart, id_product, id_product_related, id_category, amount)
                        VALUES (?, ?, ?, ?, 1)';
                $this->query($sql, array($_COOKIE['id_cart'], $_POST['id_product'], $_POST['id_product_related'], $_POST['id_category']));
            } else {
                $sql = 'UPDATE '.DDBB_PREFIX.'carts_products SET amount = amount + 1 WHERE id_cart = ? AND id_product = ? AND id_product_related = ? LIMIT 1';
                $this->query($sql, array($_COOKIE['id_cart'], $_POST['id_product'], $_POST['id_product_related']));
            }
            return array('response' => 'ok');
        }

        public function notify_stock() {
            // If you are already registered, I add it directly, otherwise I open a popup to ask for your contact information
            $popup = true;
            if(isset($_SESSION['user'])) {
                // I check if you already have the notice active
                $sql = 'SELECT id_stock_notice FROM '.DDBB_PREFIX.'stock_notices
                        WHERE id_user = ? AND id_product = ? AND id_product_related = ? LIMIT 1';
                $result = $this->query($sql, array($_SESSION['user']['id_user'], $_POST['id_product'], $_POST['id_product_related']));
                if($result->num_rows == 0) {
                    $sql = 'INSERT INTO '.DDBB_PREFIX.'stock_notices (id_product, id_product_related, `name`, email, id_user)
                            VALUES (?, ?, ?, ?, ?)';
                    $this->query($sql, array(
                        $_POST['id_product'], $_POST['id_product_related'], $_SESSION['user']['name'],
                        $_SESSION['user']['email'], $_SESSION['user']['id_user']
                    ));
                }
                $popup = false;
            }
            return array(
                'response' => 'ok',
                'popup' => $popup
            );
        }

        public function send_notify_stock() {
            // I check if you already have the notice active
            $sql = 'SELECT id_stock_notice FROM '.DDBB_PREFIX.'stock_notices
                    WHERE email = ? AND id_product = ? AND id_product_related = ? LIMIT 1';
            $result = $this->query($sql, array($_POST['email'], $_POST['id_product'], $_POST['id_product_related']));
            if($result->num_rows == 0) {
                $sql = 'INSERT INTO '.DDBB_PREFIX.'stock_notices (id_product, id_product_related, `name`, email)
                VALUES (?, ?, ?, ?)';
                $this->query($sql, array($_POST['id_product'], $_POST['id_product_related'], $_POST['name'], $_POST['email']));
            }
            return array(
                'response' => 'ok',
                'mensaje' => LANGTXT['send-notify-stock']
            );
        }

        public function get_popup_cart() {
            $total = 0;
            $html = '';
            $button = true;
            // url of the 'process order' button depending on whether you are a user or not and the language
            $urls = array(
                'en' => PUBLIC_ROUTE.'/checkout',
                'es' => PUBLIC_ROUTE.'/tramitar-pedido'
            );
            if(!isset($_SESSION['user'])) {
                $urls = array(
                    'en' => PUBLIC_ROUTE.'/access?checkout',
                    'es' => PUBLIC_ROUTE.'/acceso?checkout'
                );
            }
            $button_url = $urls[strtolower(LANG)];
            // I get the product and cart data
            $sql = 'SELECT c.*, p.price, pl.name AS product_name, r.price_change, r.stock
                    FROM '.DDBB_PREFIX.'carts_products AS c
                        INNER JOIN '.DDBB_PREFIX.'products AS p ON p.id_product = c.id_product
                        INNER JOIN '.DDBB_PREFIX.'products_related AS r ON r.id_product_related = c.id_product_related
                        INNER JOIN '.DDBB_PREFIX.'products_language AS pl ON pl.id_product = c.id_product
                        INNER JOIN '.DDBB_PREFIX.'ct_languages AS a ON a.id_language = pl.id_language
                    WHERE c.id_cart = ? AND a.name = ? ORDER BY c.id';
            $result = $this->query($sql, array($_COOKIE['id_cart'], strtolower(LANG)));
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    if($row['stock'] > 0) {
                        // Get attributes
                        $attributes = $this->get_product_related_attributes($row['id_product_related']);
                        // Get the route of the main product related of the product
                        $product_url = $this->get_product_routes($row['id_product'], $row['id_category']);
                        $product_url = PUBLIC_ROUTE.$product_url[strtolower(LANG)].'?r='.$row['id_product_related'];
                        // Get the image
                        $sql = 'SELECT i.name FROM '.DDBB_PREFIX.'products_related_images AS ri
                                    INNER JOIN '.DDBB_PREFIX.'products_images AS p ON p.id_product_image = ri.id_product_image
                                    INNER JOIN '.DDBB_PREFIX.'images AS i ON i.id_image = p.id_image
                                WHERE ri.id_product_related = ? ORDER BY p.priority LIMIT 1';
                        $result_image = $this->query($sql, array($row['id_product_related']));
                        if($result_image->num_rows != 0) {
                            $row_image = $result_image->fetch_assoc();
                            $image_url = PUBLIC_PATH.'/img/products/thumbnails/'.$row_image['name'];
                        } else {
                            $image_url = '';
                        }
                        // If there is less stock than what you ask for, I update it
                        if($row['stock'] < $row['amount']) {
                            $sql = 'UPDATE '.DDBB_PREFIX.'carts_products SET amount = ? WHERE id = ? LIMIT 1';
                            $this->query($sql, array($row['stock'], $row['id']));
                            $row['amount'] = $row['stock'];
                        }
                        // I adjust the price if there is a variant or offer
                        $price = $row['price'] + $row['price_change'];
                        $total += ($price * $row['amount']);
                        $price = number_format(floatval($price), 2, ',', '.');
                        $price .= ' €';
                        // Draw html
                        $html .= '<div class="row item" id-cart="'.$row['id'].'">';
                        $html .=    '<div class="btn-remove-cart-product" title="'.LANGTXT['delete-from-cart'].'">';
                        $html .=        '<i class="fa-solid fa-trash-can"></i>';
                        $html .=    '</div>';
                        $html .=    '<div class="col-4">';
                        $html .=        '<a href="'.$product_url.'" class="image" style="background-image: url('.$image_url.');"></a>';
                        $html .=    '</div>';
                        $html .=    '<div class="col-8">';
                        $html .=        '<a href="'.$product_url.'" class="name dots" title="'.$row['product_name'].'">'.$row['product_name'].'</a>';
                        if($attributes != null) {
                            $html .= '<div class="content-attributes">';
                            foreach($attributes[strtolower(LANG)] as $value) {
                                $html .= '<div class="dots">'.$value['attribute_name'].': '.$value['value_name'].'</div>';
                            }
                            $html .= '</div>';
                        }
                        $html .=        '<div class="row">';
                        $html .=            '<div class="col-3">';
                        $html .=                '<input type="number" class="input-sm w-100 input-cart-product-amount" value="'.$row['amount'].'">';
                        $html .=            '</div>';
                        $html .=            '<div class="col-2 text-center">x</div>';
                        $html .=            '<div class="col-7">';
                        $html .=                '<div class="price">'.$price.'</div>';
                        $html .=            '</div>';
                        $html .=        '</div>';
                        $html .=    '</div>';
                        $html .= '</div>';    
                    } else {
                        // If it's out of stock, I'll remove it from the cart.
                        $sql = 'DELETE FROM '.DDBB_PREFIX.'carts_products WHERE id = ? LIMIT 1';
                        $this->query($sql, array($_COOKIE['id_cart'], $row['id']));
                    }
                }
            } else {
                $html = '<div class="pt-20">'.LANGTXT['cart-empty'].'</div>';
                $button = false;
            }
            return array(
                'response' => 'ok',
                'total' => number_format(floatval($total), 2, ',', '.').' €',
                'html' => $html,
                'button_display' => $button,
                'button_url' => $button_url
            );
        }

        public function remove_cart_product() {
            $sql = 'DELETE FROM '.DDBB_PREFIX.'carts_products WHERE id_cart = ? AND id = ? LIMIT 1';
            $this->query($sql, array($_COOKIE['id_cart'], $_POST['id']));
            return array('response' => 'ok');
        }

        public function change_product_amount() {
            // I do not need to check the stock here since I do it when loading the cart again
            $sql = 'UPDATE '.DDBB_PREFIX.'carts_products SET amount = ? WHERE id_cart = ? AND id = ? LIMIT 1';
            $this->query($sql, array($_POST['amount'], $_COOKIE['id_cart'], $_POST['id']));
            return array('response' => 'ok');
        }

        public function get_addresses() {
            usleep($this->sleep);
            $sql = 'SELECT a.*, c.'.LANG.' AS country_name, p.'.LANG.' AS province_name
                    FROM '.DDBB_PREFIX.'users_addresses AS a
                        INNER JOIN '.DDBB_PREFIX.'ct_countries AS c ON c.id_country = a.id_country
                        INNER JOIN '.DDBB_PREFIX.'ct_provinces AS p ON p.id_province = a.id_province
                    WHERE a.id_user = ?';
            $result = $this->query($sql, array($_SESSION['user']['id_user']));
            if($result->num_rows != 0) {
                $html = '';
                while($row = $result->fetch_assoc()) {
                    if($row['main_address'] == 1) {
                        $class = ' active';
                    } else {
                        $class = '';
                    }
                    $html .= '<div class="col-12 col-sm-6">';
                    $html .=    '<div class="item'.$class.'" id-user-address="'.$row['id_user_address'].'">';
                    $html .=        '<div class="pb-10"><b>'.$row['name'].' '.$row['lastname'].'</b></div>';
                    $html .=        '<div class="pb-5">'.$row['address'].'</div>';
                    $html .=        '<div class="pb-5">'.$row['location'].' '.$row['postal_code'].'</div>';
                    $html .=        '<div class="pb-5">'.$row['province_name'].', '.$row['country_name'].'</div>';
                    $html .=        '<div class="pb-5">'.LANGTXT['telephone'].': '.$row['telephone'].'</div>';
                    $html .=        '<div class="row pt-20">';
                    $html .=            '<div class="col-12 col-sm-4 pr-10 pr-sm-0">';
                    $html .=                '<div class="btn-select-address btn btn-black btn-sm w-100">'.LANGTXT['select'].'</div>';
                    $html .=            '</div>';
                    $html .=            '<div class="col-12 col-sm-4 pr-10 pr-sm-0">';
                    $html .=                '<div class="btn-edit-address btn btn-black btn-sm w-100">'.LANGTXT['edit'].'</div>';
                    $html .=            '</div>';
                    $html .=            '<div class="col-12 col-sm-4">';
                    $html .=                '<div class="btn-delete-address btn btn-black btn-sm w-100">'.LANGTXT['delete'].'</div>';
                    $html .=            '</div>';
                    $html .=        '</div>';
                    $html .=    '</div>';
                    $html .= '</div>';
                }
            } else {
                $html = '<div class="col-12 text-center pb-20">'.LANGTXT['new-address'].'</div>';
            }
            return array(
                'response' => 'ok',
                'html' => $html
            );
        }

        public function save_new_address() {
            if($_POST['main'] == 1) {
                $sql = 'UPDATE '.DDBB_PREFIX.'users_addresses SET main_address = 0 WHERE id_user = ?';
                $this->query($sql, array($_SESSION['user']['id_user']));
            } else {
                // I check if it is the first address it saves to make it the main one
                $sql = 'SELECT id_user_address FROM users_addresses WHERE id_user = ? LIMIT 1';
                $result = $this->query($sql, array($_SESSION['user']['id_user']));
                if($result->num_rows == 0) {
                    $_POST['main'] = 1;
                }
            }
            // Insert new address
            $sql = 'INSERT INTO '.DDBB_PREFIX.'users_addresses
                        (id_user, main_address, name, lastname, id_continent, id_country, id_province, location, address, postal_code, telephone)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
            $values = array(
                $_SESSION['user']['id_user'], $_POST['main'],
                $_POST['name'], $_POST['lastname'], $_POST['id_continent'],
                $_POST['id_country'], $_POST['id_province'],$_POST['location'],
                $_POST['address'], $_POST['postal_code'], $_POST['telephone']
            );
            $this->query($sql, $values);
            return array('response' => 'ok');
        }

        public function get_address($id_user_address) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'users_addresses WHERE id_user = ? AND id_user_address = ? LIMIT 1';
            $result = $this->query($sql, array($_SESSION['user']['id_user'], $id_user_address));
            if($result->num_rows != 0) {
                $row = $result->fetch_assoc();
                return array(
                    'response' => 'ok',
                    'address' => $row,
                    'countries' => $this->get_countries_list($row['id_continent'], $row['id_country'])['html'],
                    'provinces' => $this->get_provinces_list($row['id_country'], $row['id_province'])['html']
                );
            } else {
                return array('response' => 'error');
            }
        }

        public function delete_address($id_user_address) {
            // I check if it is the main address and if it is I change it to another
            $sql = 'SELECT id_user_address FROM '.DDBB_PREFIX.'users_addresses WHERE id_user = ? AND id_user_address = ? AND main_address = 1 LIMIT 1';
            $result = $this->query($sql, array($_SESSION['user']['id_user'], $id_user_address));
            if($result->num_rows != 0) {
                $sql = 'UPDATE '.DDBB_PREFIX.'users_addresses SET main_address = 1 WHERE id_user = ? AND id_user_address != ? LIMIT 1';
                $this->query($sql, array($_SESSION['user']['id_user'], $id_user_address));
            }
            $sql = 'DELETE FROM '.DDBB_PREFIX.'users_addresses WHERE id_user = ? AND id_user_address = ? LIMIT 1';
            $this->query($sql, array($_SESSION['user']['id_user'], $id_user_address));
            return array('response' => 'ok');
        }

        public function save_edit_address() {
            if($_POST['main'] == 1) {
                $sql = 'UPDATE '.DDBB_PREFIX.'users_addresses SET main_address = 0 WHERE id_user = ?';
                $this->query($sql, array($_SESSION['user']['id_user']));
                $sql = 'UPDATE '.DDBB_PREFIX.'users_addresses SET main_address = 1 WHERE id_user = ? AND id_user_address = ? LIMIT 1';
                $this->query($sql, array($_SESSION['user']['id_user'], $_POST['id_user_address']));
            }
            $sql = 'UPDATE '.DDBB_PREFIX.'users_addresses SET name = ?, lastname = ?, id_continent = ?,
                        id_country = ?, id_province = ?, location = ?, address = ?, postal_code = ?, telephone = ?, update_date = NOW()
                    WHERE id_user = ? AND id_user_address = ? LIMIT 1';
            $this->query($sql, array($_POST['name'], $_POST['lastname'], $_POST['id_continent'], $_POST['id_country'], $_POST['id_province'],
                $_POST['location'], $_POST['address'], $_POST['postal_code'], $_POST['telephone'], $_SESSION['user']['id_user'], $_POST['id_user_address']
            ));
            return array('response' => 'ok');
        }

        public function get_billing_addresses() {
            usleep($this->sleep);
            $sql = 'SELECT a.*, c.'.LANG.' AS country_name, p.'.LANG.' AS province_name
                    FROM '.DDBB_PREFIX.'users_billing_addresses AS a
                        INNER JOIN '.DDBB_PREFIX.'ct_countries AS c ON c.id_country = a.id_country
                        INNER JOIN '.DDBB_PREFIX.'ct_provinces AS p ON p.id_province = a.id_province
                    WHERE a.id_user = ?';
            $result = $this->query($sql, array($_SESSION['user']['id_user']));
            if($result->num_rows != 0) {
                $html = '';
                while($row = $result->fetch_assoc()) {
                    if($row['main_address'] == 1) {
                        $class = ' active';
                    } else {
                        $class = '';
                    }
                    $html .= '<div class="col-12 col-sm-6">';
                    $html .=    '<div class="item'.$class.'" id-user-billing-address="'.$row['id_user_billing_address'].'">';
                    $html .=        '<div class="pb-10"><b>'.$row['name'].' '.$row['lastname'].'</b></div>';
                    $html .=        '<div class="pb-5">'.$row['address'].'</div>';
                    $html .=        '<div class="pb-5">'.$row['location'].' '.$row['postal_code'].'</div>';
                    $html .=        '<div class="pb-5">'.$row['province_name'].', '.$row['country_name'].'</div>';
                    $html .=        '<div class="pb-5">'.LANGTXT['telephone'].': '.$row['telephone'].'</div>';
                    $html .=        '<div class="row pt-20">';
                    $html .=            '<div class="col-12 col-sm-4 pr-10 pr-sm-0">';
                    $html .=                '<div class="btn-select-address btn btn-black btn-sm w-100">'.LANGTXT['select'].'</div>';
                    $html .=            '</div>';
                    $html .=            '<div class="col-12 col-sm-4 pr-10 pr-sm-0">';
                    $html .=                '<div class="btn-edit-address btn btn-black btn-sm w-100">'.LANGTXT['edit'].'</div>';
                    $html .=            '</div>';
                    $html .=            '<div class="col-12 col-sm-4">';
                    $html .=                '<div class="btn-delete-address btn btn-black btn-sm w-100">'.LANGTXT['delete'].'</div>';
                    $html .=            '</div>';
                    $html .=        '</div>';
                    $html .=    '</div>';
                    $html .= '</div>';
                }
            } else {
                $html = '<div class="col-12 text-center pb-20">'.LANGTXT['new-billing-address'].'</div>';
            }
            return array(
                'response' => 'ok',
                'html' => $html
            );
        }

        public function get_billing_address($id_user_billing_address) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'users_billing_addresses WHERE id_user = ? AND id_user_billing_address = ? LIMIT 1';
            $result = $this->query($sql, array($_SESSION['user']['id_user'], $id_user_billing_address));
            if($result->num_rows != 0) {
                $row = $result->fetch_assoc();
                return array(
                    'response' => 'ok',
                    'address' => $row,
                    'countries' => $this->get_countries_list($row['id_continent'], $row['id_country'])['html'],
                    'provinces' => $this->get_provinces_list($row['id_country'], $row['id_province'])['html']
                );
            } else {
                return array('response' => 'error');
            }
        }

        public function delete_billing_address($id_user_billing_address) {
            // I check if it is the main address and if it is I change it to another
            $sql = 'SELECT id_user_billing_address FROM '.DDBB_PREFIX.'users_billing_addresses
                    WHERE id_user = ? AND id_user_billing_address = ? AND main_address = 1 LIMIT 1';
            $result = $this->query($sql, array($_SESSION['user']['id_user'], $id_user_billing_address));
            if($result->num_rows != 0) {
                $sql = 'UPDATE '.DDBB_PREFIX.'users_billing_addresses SET main_address = 1 WHERE id_user = ? AND id_user_billing_address != ? LIMIT 1';
                $this->query($sql, array($_SESSION['user']['id_user'], $id_user_billing_address));
            }
            $sql = 'DELETE FROM '.DDBB_PREFIX.'users_billing_addresses WHERE id_user = ? AND id_user_billing_address = ? LIMIT 1';
            $this->query($sql, array($_SESSION['user']['id_user'], $id_user_billing_address));
            return array('response' => 'ok');
        }

        public function save_edit_billing_address() {
            if($_POST['main'] == 1) {
                $sql = 'UPDATE '.DDBB_PREFIX.'users_billing_addresses SET main_address = 0 WHERE id_user = ?';
                $this->query($sql, array($_SESSION['user']['id_user']));
                $sql = 'UPDATE '.DDBB_PREFIX.'users_billing_addresses SET main_address = 1 WHERE id_user = ? AND id_user_billing_address = ? LIMIT 1';
                $this->query($sql, array($_SESSION['user']['id_user'], $_POST['id_user_address']));
            }
            $sql = 'UPDATE '.DDBB_PREFIX.'users_billing_addresses SET name = ?, lastname = ?, id_continent = ?,
                        id_country = ?, id_province = ?, location = ?, address = ?, postal_code = ?, telephone = ?, update_date = NOW()
                    WHERE id_user = ? AND id_user_billing_address = ? LIMIT 1';
            $this->query($sql, array($_POST['name'], $_POST['lastname'], $_POST['id_continent'], $_POST['id_country'], $_POST['id_province'],
                $_POST['location'], $_POST['address'], $_POST['postal_code'], $_POST['telephone'], $_SESSION['user']['id_user'], $_POST['id_user_billing_address']
            ));
            return array('response' => 'ok');
        }

        public function save_new_billing_address() {
            if($_POST['main'] == 1) {
                $sql = 'UPDATE '.DDBB_PREFIX.'users_billing_addresses SET main_address = 0 WHERE id_user = ?';
                $this->query($sql, array($_SESSION['user']['id_user']));
            } else {
                // I check if it is the first address it saves to make it the main one
                $sql = 'SELECT id_user_billing_address FROM users_billing_addresses WHERE id_user = ? LIMIT 1';
                $result = $this->query($sql, array($_SESSION['user']['id_user']));
                if($result->num_rows == 0) {
                    $_POST['main'] = 1;
                }
            }
            // Insert new address
            $sql = 'INSERT INTO '.DDBB_PREFIX.'users_billing_addresses
                        (id_user, main_address, name, lastname, id_continent, id_country, id_province, location, address, postal_code, telephone)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
            $values = array(
                $_SESSION['user']['id_user'], $_POST['main'],
                $_POST['name'], $_POST['lastname'], $_POST['id_continent'],
                $_POST['id_country'], $_POST['id_province'],$_POST['location'],
                $_POST['address'], $_POST['postal_code'], $_POST['telephone']
            );
            $this->query($sql, $values);
            return array('response' => 'ok');
        }

        public function save_order() {
            $order_code = uniqid();
            $sql = 'INSERT INTO orders ()
                    VALUES ()';
            return array('response' => 'ok');
        }

        public function get_countries_list($id_continent, $id_country = null) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'ct_countries WHERE id_continent = ? AND active = 1';
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
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'ct_provinces WHERE id_country = ? AND active = 1';
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

    }

?>