<?php

    /**
     * @author Diego Martín
     * @copyright Hive®
     * @version 1.0
     * @lastUpdated 2025
     */

    class AppAjax extends AppModel {

        public $name_page;

        function __construct($name_page = 'default-page') {
            parent::__construct();
            $this->name_page = $name_page;
            $this->check_maintenance();
        }

        public function set_cookies() {
            Utils::initCookie('acepto_cookies', 'accepted cookies', Utils::ONEYEAR);
            return array('response' => 'ok');
        }

        public function save_newsletter($email) {
            $sql = 'SELECT id_newsletter FROM '.DDBB_PREFIX.'newsletters WHERE email = ? LIMIT 1';
            $result = $this->query($sql, array($email));
            if($result->num_rows == 0) {
                $validation_code = uniqid();
                $sql = 'INSERT INTO '.DDBB_PREFIX.'newsletters (email, validation_code) VALUES (?, ?)';
                $this->query($sql, array($email, $validation_code));
            } else {
                $sql = 'UPDATE '.DDBB_PREFIX.'newsletters SET status = 1 WHERE email = ? LIMIT 1';
                $this->query($sql, array($email));
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
                $this->query($sql, array(
                    $email,
                    $name,
                    $lastname,
                    md5($pass),
                    uniqid(),
                    $this->get_ip()
                ));
                // If you sign up for the newsletter
                if($newsletter == 1) {
                    $validation_code = uniqid();
                    $sql = 'INSERT INTO '.DDBB_PREFIX.'newsletters (email, validation_code) VALUES (?, ?)';
                    $this->query($sql, array($email, $validation_code));
                }
                if(isset($_POST['checkout']) && $_POST['checkout'] == 1) {
                    $url = Utils::getRoute('checkout', array('register' => 'true'));
                } else {
                    $url = Utils::getRoute('/', array('register' => 'true'));
                }
                return array(
                    'response' => 'ok',
                    'url' => $url
                );
            } else {
                return array(
                    'response' => 'error',
                    'message' => LANGTXT['error-register']
                );    
            }
        }

        public function choose_language($language) {
            Utils::initCookie('lang', $language, Utils::ONEYEAR);
            return array('response' => 'ok');
        }

        public function get_product_related() {
            $sql = 'SELECT r.*, p.price AS price FROM '.DDBB_PREFIX.'products_related AS r
                        INNER JOIN '.DDBB_PREFIX.'products AS p ON p.id_product = r.id_product
                    WHERE p.id_product = ? AND p.id_state = 2 AND r.id_state = 2';
            $result = $this->query($sql, array($_POST['id_product']));
            if($result->num_rows != 0) {
                $related = null;
                // If the product has attributes, I look for which one has those attributes.
                if(isset($_POST['id_attribute_values'])) {
                    while($row = $result->fetch_assoc()) {
                        $sql = 'SELECT id_attribute_value FROM '.DDBB_PREFIX.'products_related_attributes
                                WHERE id_attribute_value IN ('.implode(',', $_POST['id_attribute_values']).') AND id_product_related = ?';
                        $result_attr = $this->query($sql, array($row['id_product_related']));
                        if($result_attr->num_rows == count($_POST['id_attribute_values'])) {
                            $related = $row;
                            break;
                        }
                    }
                } else {
                    $related = $result->fetch_assoc();
                }                    
                if($related != null) {
                    // I collect the id of the images that are currently being used to know if they need to be refreshed
                    $sql = 'SELECT id_product_image FROM '.DDBB_PREFIX.'products_related_images WHERE id_product_related = ?';
                    $result_img = $this->query($sql, array($_POST['id_current_product_relared']));
                    $imageIdsCurrent = array();
                    while($row = $result_img->fetch_assoc()) {
                        array_push($imageIdsCurrent, $row['id_product_image']);
                    }
                    // If you have an offer and it is within the deadline
                    $offer = 0;
                    if($related['offer'] != 0 && date('Y-m-d') > $related['offer_start_date'] && date('Y-m-d') < $related['offer_end_date']) {
                        $offer = $related['offer'];
                    }
                    // I adjust the price if there is a variant or offer
                    $related['price'] += $related['price_change'] - $offer;
                    $related['price'] = number_format(floatval($related['price']), 2, ',', '.');
                    $related['price'] .= ' €';
                    // I check if they use the same images so as not to reload them
                    $sql = 'SELECT id_product_image FROM products_related_images WHERE id_product_related = ?';
                    $result_img = $this->query($sql, array($related['id_product_related']));
                    $imageIdsToLoad = array();
                    while($row = $result_img->fetch_assoc()) {
                        array_push($imageIdsToLoad, $row['id_product_image']);
                    }
                    $diff = array_diff($imageIdsCurrent, $imageIdsToLoad);
                    if(count($imageIdsCurrent) == count($imageIdsToLoad) && empty($diff)) {
                        $related['images'] = null;
                    } else {
                        $related['images'] = $this->get_product_related_images($related['id_product_related']);
                    }
                    return array(
                        'response' => 'ok',
                        'product_related' => $related,
                    );        
                }
            }
            return array(
                'response' => 'error',
                'message' => 'There is no product with those attributes'                    
            );
        }

        public function get_product_related_images($id_product_related) {
            // I collect the images of the related products
            $sql = 'SELECT i.url FROM '.DDBB_PREFIX.'products_related_images AS r
                        INNER JOIN '.DDBB_PREFIX.'products_images AS p ON p.id_product_image = r.id_product_image
                        INNER JOIN '.DDBB_PREFIX.'images AS i ON i.id_image = p.id_image
                    WHERE r.id_product_related = ? ORDER BY p.priority';
            $result = $this->query($sql, $id_product_related);
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
            $result = $this->query($sql, array(
                $_COOKIE['id_cart'],
                $_POST['id_product'],
                $_POST['id_product_related']
            ));
            if($result->num_rows == 0) {
                $sql = 'INSERT INTO '.DDBB_PREFIX.'carts_products (id_cart, id_product, id_product_related, id_category, amount)
                        VALUES (?, ?, ?, ?, 1)';
                $this->query($sql, array(
                    $_COOKIE['id_cart'],
                    $_POST['id_product'],
                    $_POST['id_product_related'],
                    $_POST['id_category']
                ));
            } else {
                $sql = 'UPDATE '.DDBB_PREFIX.'carts_products SET amount = amount + 1 WHERE id_cart = ? AND id_product = ? AND id_product_related = ? LIMIT 1';
                $this->query($sql, array(
                    $_COOKIE['id_cart'],
                    $_POST['id_product'],
                    $_POST['id_product_related']
                ));
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
                $result = $this->query($sql, array(
                    $_SESSION['user']['id_user'],
                    $_POST['id_product'],
                    $_POST['id_product_related']
                ));
                if($result->num_rows == 0) {
                    $sql = 'INSERT INTO '.DDBB_PREFIX.'stock_notices (id_product, id_product_related, id_category, `name`, email, id_user)
                            VALUES (?, ?, ?, ?, ?, ?)';
                    $this->query($sql, array(
                        $_POST['id_product'],
                        $_POST['id_product_related'],
                        $_POST['id_category'],
                        $_SESSION['user']['name'],
                        $_SESSION['user']['email'],
                        $_SESSION['user']['id_user']
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
            $result = $this->query($sql, array(
                $_POST['email'],
                $_POST['id_product'],
                $_POST['id_product_related']
            ));
            if($result->num_rows == 0) {
                $sql = 'INSERT INTO '.DDBB_PREFIX.'stock_notices (id_product, id_product_related, id_category, `name`, email)
                VALUES (?, ?, ?, ?, ?)';
                $this->query($sql, array(
                    $_POST['id_product'],
                    $_POST['id_product_related'],
                    $_POST['id_category'],
                    $_POST['name'],
                    $_POST['email']
                ));
            }
            return array(
                'response' => 'ok',
                'message' => LANGTXT['send-notify-stock']
            );
        }

        public function get_popup_cart($id_cart) {
            $cart = $this->get_cart_array($id_cart);
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
            if($cart != null) {
                $buttons = true;
                $total = $cart['total_string'];
                $html = '';
                foreach($cart['products'] as $value) {
                    // Draw html
                    $html .= '<div class="row item" id-cart-product="'.$value['row']['id_cart_product'].'">';
                    $html .=    '<div class="btn-remove-cart-product" title="'.LANGTXT['delete-from-cart'].'">';
                    $html .=        '<i class="fa-solid fa-trash-can"></i>';
                    $html .=    '</div>';
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
                    $html .=        '<div class="row">';
                    $html .=            '<div class="col-3">';
                    $html .=                '<input type="number" class="input-sm w-100 input-cart-product-amount" value="'.$value['row']['amount'].'">';
                    $html .=            '</div>';
                    $html .=            '<div class="col-2 text-center">x</div>';
                    $html .=            '<div class="col-7">';
                    $html .=                '<div class="price">'.$value['price_string'].'</div>';
                    $html .=            '</div>';
                    $html .=        '</div>';
                    $html .=    '</div>';
                    $html .= '</div>';
                }
                // I check if you have discount codes
                $sql = 'SELECT c.id_code, o.code FROM carts_codes AS c
                            INNER JOIN codes AS o ON o.id_code = c.id_code
                        WHERE c.id_cart = ?';
                $result = $this->query($sql, $id_cart);
                $html_codes = '';
                if($result->num_rows != 0) {
                    $html_codes = '';
                    while($row = $result->fetch_assoc()) {
                        $html_codes .= '<div>Código: '.$row['code'];
                        $html_codes .= '</div>';
                    }
                }
            } else {
                $buttons = false;
                $total = '';
                $html = '<div class="pt-20">'.LANGTXT['cart-empty'].'</div>';
                $html_codes = '';
            }
            return array(
                'response' => 'ok',
                'total' => $total,
                'html' => $html,
                'html_codes' => $html_codes,
                'button_display' => $buttons,
                'button_url' => $button_url
            );
        }

        public function remove_cart_product($id_cart, $id_cart_product) {
            $sql = 'DELETE FROM '.DDBB_PREFIX.'carts_products WHERE id_cart = ? AND id_cart_product = ? LIMIT 1';
            $this->query($sql, array(
                $id_cart,
                $id_cart_product
            ));
            return array('response' => 'ok');
        }

        public function change_product_amount() {
            // I do not need to check the stock here since I do it when loading the cart again
            $sql = 'UPDATE '.DDBB_PREFIX.'carts_products SET amount = ? WHERE id_cart = ? AND id_cart_product = ? LIMIT 1';
            $this->query($sql, array(
                $_POST['amount'],
                $_COOKIE['id_cart'],
                $_POST['id_cart_product']
            ));
            return array('response' => 'ok');
        }

        public function get_addresses() {
            usleep($this->sleep);
            $sql = 'SELECT a.*, c.'.LANG.' AS country_name, p.'.LANG.' AS province_name
                    FROM '.DDBB_PREFIX.'users_addresses AS a
                        INNER JOIN '.DDBB_PREFIX.'ct_countries AS c ON c.id_country = a.id_country
                        INNER JOIN '.DDBB_PREFIX.'ct_provinces AS p ON p.id_province = a.id_province
                    WHERE a.id_user = ?';
            $result = $this->query($sql, $_SESSION['user']['id_user']);
            if($result->num_rows != 0) {
                $html = '';
                while($row = $result->fetch_assoc()) {
                    if($row['main_address'] == 1) {
                        $class = ' active';
                        $class_btn = ' hidden';
                    } else {
                        $class = '';
                        $class_btn = '';
                    }
                    $html .= '<div class="col-12 col-sm-6">';
                    $html .=    '<div class="item'.$class.'" id-user-address="'.$row['id_user_address'].'">';
                    $html .=        '<div class="pb-10"><b>'.$row['name'].' '.$row['lastname'].'</b></div>';
                    $html .=        '<div class="info">';
                    $html .=            '<div class="pb-3 dots">'.$row['address'].'</div>';
                    $html .=            '<div class="pb-3 dots">'.$row['location'].' '.$row['postal_code'].'</div>';
                    $html .=            '<div class="pb-3 dots">'.$row['province_name'].', '.$row['country_name'].'</div>';
                    $html .=            '<div class="pb-3">'.LANGTXT['telephone'].': '.$row['telephone'].'</div>';
                    $html .=            '<div class="pt-12">';
                    $html .=                '<div class="btn-select-address btn btn-sm mr-5 mr-sm-0'.$class_btn.'">'.LANGTXT['select'].'</div>';
                    $html .=                '<div class="btn-edit-address btn btn-sm mr-5 mr-sm-0">'.LANGTXT['edit'].'</div>';
                    $html .=                '<div class="btn-delete-address btn btn-sm">'.LANGTXT['delete'].'</div>';
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
                $result = $this->query($sql, $_SESSION['user']['id_user']);
                if($result->num_rows == 0) {
                    $_POST['main'] = 1;
                }
            }
            // Insert new address
            $sql = 'INSERT INTO '.DDBB_PREFIX.'users_addresses
                        (id_user, main_address, name, lastname, id_continent, id_country, id_province, location, address, postal_code, telephone)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
            $values = array(
                $_SESSION['user']['id_user'],
                $_POST['main'],
                $_POST['name'],
                $_POST['lastname'],
                $_POST['id_continent'],
                $_POST['id_country'],
                $_POST['id_province'],
                $_POST['location'],
                $_POST['address'],
                $_POST['postal_code'],
                $_POST['telephone']
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
                $this->query($sql, $_SESSION['user']['id_user']);
                $sql = 'UPDATE '.DDBB_PREFIX.'users_addresses SET main_address = 1 WHERE id_user = ? AND id_user_address = ? LIMIT 1';
                $this->query($sql, array($_SESSION['user']['id_user'], $_POST['id_user_address']));
            }
            $sql = 'UPDATE '.DDBB_PREFIX.'users_addresses SET name = ?, lastname = ?, id_continent = ?,
                        id_country = ?, id_province = ?, location = ?, address = ?, postal_code = ?, telephone = ?, update_date = NOW()
                    WHERE id_user = ? AND id_user_address = ? LIMIT 1';
            $this->query($sql, array(
                $_POST['name'],
                $_POST['lastname'],
                $_POST['id_continent'],
                $_POST['id_country'],
                $_POST['id_province'],
                $_POST['location'],
                $_POST['address'],
                $_POST['postal_code'],
                $_POST['telephone'],
                $_SESSION['user']['id_user'],
                $_POST['id_user_address']
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
            $result = $this->query($sql, $_SESSION['user']['id_user']);
            if($result->num_rows != 0) {
                $html = '';
                while($row = $result->fetch_assoc()) {
                    if($row['main_address'] == 1) {
                        $class = ' active';
                        $class_btn = ' hidden';
                    } else {
                        $class = '';
                        $class_btn = '';
                    }
                    $html .= '<div class="col-12 col-sm-6">';
                    $html .=    '<div class="item'.$class.'" id-user-billing-address="'.$row['id_user_billing_address'].'">';
                    $html .=        '<div class="pb-10"><b>'.$row['name'].' '.$row['lastname'].'</b></div>';
                    $html .=        '<div class="info">';
                    $html .=            '<div class="pb-3 dots">'.$row['address'].'</div>';
                    $html .=            '<div class="pb-3 dots">'.$row['location'].' '.$row['postal_code'].'</div>';
                    $html .=            '<div class="pb-3 dots">'.$row['province_name'].', '.$row['country_name'].'</div>';
                    $html .=            '<div class="pb-3">'.LANGTXT['telephone'].': '.$row['telephone'].'</div>';
                    $html .=            '<div class="pt-12">';
                    $html .=                '<div class="btn-select-address btn btn-sm mr-5 mr-sm-0'.$class_btn.'">'.LANGTXT['select'].'</div>';
                    $html .=                '<div class="btn-edit-address btn btn-sm mr-5 mr-sm-0">'.LANGTXT['edit'].'</div>';
                    $html .=                '<div class="btn-delete-address btn btn-sm">'.LANGTXT['delete'].'</div>';
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
                $this->query($sql, array($_SESSION['user']['id_user'], $_POST['id_user_billing_address']));
            }
            $sql = 'UPDATE '.DDBB_PREFIX.'users_billing_addresses SET name = ?, lastname = ?, id_continent = ?,
                        id_country = ?, id_province = ?, location = ?, address = ?, postal_code = ?, telephone = ?, update_date = NOW()
                    WHERE id_user = ? AND id_user_billing_address = ? LIMIT 1';
            $value = array(
                $_POST['name'],
                $_POST['lastname'],
                $_POST['id_continent'],
                $_POST['id_country'],
                $_POST['id_province'],
                $_POST['location'],
                $_POST['address'],
                $_POST['postal_code'],
                $_POST['telephone'],
                $_SESSION['user']['id_user'],
                $_POST['id_user_billing_address']
            );
            $this->query($sql, $value);
            return array('response' => 'ok');
        }

        public function save_new_billing_address() {
            if($_POST['main'] == 1) {
                $sql = 'UPDATE '.DDBB_PREFIX.'users_billing_addresses SET main_address = 0 WHERE id_user = ?';
                $this->query($sql, $_SESSION['user']['id_user']);
            } else {
                // I check if it is the first address it saves to make it the main one
                $sql = 'SELECT id_user_billing_address FROM users_billing_addresses WHERE id_user = ? LIMIT 1';
                $result = $this->query($sql, $_SESSION['user']['id_user']);
                if($result->num_rows == 0) {
                    $_POST['main'] = 1;
                }
            }
            // Insert new address
            $sql = 'INSERT INTO '.DDBB_PREFIX.'users_billing_addresses
                        (id_user, main_address, name, lastname, id_continent, id_country, id_province, location, address, postal_code, telephone)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
            $values = array(
                $_SESSION['user']['id_user'],
                $_POST['main'],
                $_POST['name'],
                $_POST['lastname'],
                $_POST['id_continent'],
                $_POST['id_country'],
                $_POST['id_province'],
                $_POST['location'],
                $_POST['address'],
                $_POST['postal_code'],
                $_POST['telephone']
            );
            $this->query($sql, $values);
            return array('response' => 'ok');
        }

        public function apply_code($code, $id_cart) {
            $result = $this->check_code($code, $id_cart);
            if($result['response'] == 'ok') {
                $sql = 'INSERT INTO '.DDBB_PREFIX.'carts_codes (id_cart, id_code) VALUES (?, ?)';
                $this->query($sql, array($_COOKIE["id_cart"], $result['id_code']));
            }
            return $result;
        }

        public function get_countries_list($id_continent, $id_country = null) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'ct_countries WHERE id_continent = ? AND id_state = ?';
            $result = $this->query($sql, array($id_continent, UTils::IDACTIVE));
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
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'ct_provinces WHERE id_country = ? AND id_state = ?';
            $result = $this->query($sql, array($id_country, UTils::IDACTIVE));
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

        private function check_shipment_zone_continent($id_continent, $id_shipping_zone) {
            // I collect the continents of the shipping method
            $sql = 'SELECT z.id_continent FROM '.DDBB_PREFIX.'shipping_zone_continents AS z
                        INNER JOIN '.DDBB_PREFIX.'ct_continents AS c ON c.id_continent = z.id_continent
                    WHERE z.id_shipping_zone = ? AND c.id_state = ?';
            $result = $this->query($sql, array($id_shipping_zone, UTils::IDACTIVE));
            if($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    if($row['id_continent'] == $id_continent) {
                        return true;
                    }
                }
            }
            return false;
        }

        private function check_shipment_zone_country($id_country, $id_shipping_zone) {
            // I collect the countries of the shipping method
            $sql = 'SELECT z.id_country FROM '.DDBB_PREFIX.'shipping_zone_countries AS z
                        INNER JOIN '.DDBB_PREFIX.'ct_countries AS c ON c.id_country = z.id_country
                    WHERE z.id_shipping_zone = ? AND c.id_state = ?';
            $result = $this->query($sql, array($id_shipping_zone, UTils::IDACTIVE));
            if($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    if($row['id_country'] == $id_country) {
                        return true;
                    }
                }
            }
            return false;
        }

        private function check_shipment_zone_province($id_province, $id_shipping_zone) {
            // I collect the continents of the shipping method
            $sql = 'SELECT z.id_province FROM '.DDBB_PREFIX.'shipping_zone_provinces AS z
                        INNER JOIN '.DDBB_PREFIX.'ct_provinces AS c ON c.id_province = z.id_province
                    WHERE z.id_shipping_zone = ? AND c.id_state = ?';
            $result = $this->query($sql, array($id_shipping_zone, UTils::IDACTIVE));
            if($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    if($row['id_province'] == $id_province) {
                        return true;
                    }
                }
            }
            return false;
        }

        public function get_shipping_methods($id_cart) {
            usleep($this->sleep);
            $cart_price = $this->get_cart_total_price($id_cart);
            $cart_weight = $this->get_cart_total_weight($id_cart);
            $sql = 'SELECT s.*, l.name AS shipping_name FROM '.DDBB_PREFIX.'shipping_methods AS s
                        INNER JOIN '.DDBB_PREFIX.'shipping_methods_language AS l ON l.id_shipping_method = s.id_shipping_method
                        INNER JOIN '.DDBB_PREFIX.'ct_languages AS a ON a.id_language = l.id_language
                    WHERE s.id_state = ? AND a.name = ?';
            $result = $this->query($sql, array(UTils::IDACTIVE, strtolower(LANG)));
            $html = '';
            if($result->num_rows > 0) {
                // I collect the values ​​from the client's address
                $sql = 'SELECT * FROM users_addresses WHERE id_user_address = ? LIMIT 1';
                $result_address = $this->query($sql, $_POST['id_user_address']);
                $row_address = $result_address->fetch_assoc();
                // Check if the cart meets the shipping method requirements.
                while($row = $result->fetch_assoc()) {
                    if($cart_price < $row['min_order_value'] || ($cart_price > $row['max_order_value'] && $row['max_order_value'] != 0)) {
                        continue;
                    }
                    if($cart_weight < $row['min_order_weight'] || ($cart_weight > $row['max_order_weight'] && $row['max_order_weight'] != 0)) {
                        continue;
                    }
                    // I check if the active shipping methods covers the selected zone
                    $sql = 'SELECT s.id_shipping_zone FROM '.DDBB_PREFIX.'shipping_methods_zones AS s
                                INNER JOIN shipping_zones AS z ON z.id_shipping_zone = s.id_shipping_zone
                            WHERE s.id_shipping_method = ? AND z.id_state = ?';
                    $result_zones = $this->query($sql, array($row['id_shipping_method'], UTils::IDACTIVE));
                    if($result_zones->num_rows > 0) {
                        while($row_zone = $result_zones->fetch_assoc()) {
                            if(!$this->check_shipment_zone_continent($row_address['id_continent'], $row_zone['id_shipping_zone'])) {
                                continue;
                            }
                            if(!$this->check_shipment_zone_country($row_address['id_country'], $row_zone['id_shipping_zone'])) {
                                continue;
                            }
                            if(!$this->check_shipment_zone_province($row_address['id_province'], $row_zone['id_shipping_zone'])) {
                                continue;
                            }
                            // I check the zone covers the weight of the order
                            $sql = 'SELECT p.price AS price FROM '.DDBB_PREFIX.'shipping_methods_weights AS w
                                        INNER JOIN '.DDBB_PREFIX.'shipping_methods_prices AS p ON p.id_shipping_method_weight = w.id_shipping_method_weight
                                    WHERE w.id_shipping_method = ? AND w.max_weight >= ? AND p.id_shipping_zone = ?
                                    ORDER BY w.max_weight LIMIT 1';
                            $result_weight = $this->query($sql, array(
                                $row['id_shipping_method'],
                                $cart_weight,
                                $row_zone['id_shipping_zone']
                            ));
                            if($result_weight->num_rows > 0) {
                                $row_weight = $result_weight->fetch_assoc();
                                $html .= '<div>';
                                $html .=    '<label class="radio"><input type="radio" value="'.$row['id_shipping_method'].'" name="input-shipping-methods" class="input-shipping-methods">';
                                $html .=    '<span class="checkmark"></span> '.$row['shipping_name'].': '.$row_weight['price'].' €.</label>';
                                $html .= '</div>';
                            }
                        }
                    }
                }
            }
            if($html == '') {
                $html = 'No shipping methods found at this time.';
            }
            return array(
                'response' => 'ok',
                'html' => $html
            );
        }

        private function check_payment_zone_continent($id_continent, $id_payment_zone) {
            // I collect the continents of the payment method
            $sql = 'SELECT z.id_continent FROM '.DDBB_PREFIX.'payment_zone_continents AS z
                        INNER JOIN '.DDBB_PREFIX.'ct_continents AS c ON c.id_continent = z.id_continent
                    WHERE z.id_payment_zone = ? AND c.id_state = ?';
            $result = $this->query($sql, array($id_payment_zone, UTils::IDACTIVE));
            if($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    if($row['id_continent'] == $id_continent) {
                        return true;
                    }
                }
            }
            return false;
        }

        private function check_payment_zone_country($id_country, $id_payment_zone) {
            // I collect the countries of the payment method
            $sql = 'SELECT z.id_country FROM '.DDBB_PREFIX.'payment_zone_countries AS z
                        INNER JOIN '.DDBB_PREFIX.'ct_countries AS c ON c.id_country = z.id_country
                    WHERE z.id_payment_zone = ? AND c.id_state = ?';
            $result = $this->query($sql, array($id_payment_zone, UTils::IDACTIVE));
            if($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    if($row['id_country'] == $id_country) {
                        return true;
                    }
                }
            }
            return false;
        }

        private function check_payment_zone_province($id_province, $id_payment_zone) {
            // I collect the continents of the payment method
            $sql = 'SELECT z.id_province FROM '.DDBB_PREFIX.'payment_zone_provinces AS z
                        INNER JOIN '.DDBB_PREFIX.'ct_provinces AS c ON c.id_province = z.id_province
                    WHERE z.id_payment_zone = ? AND c.id_state = ?';
            $result = $this->query($sql, array($id_payment_zone, UTils::IDACTIVE));
            if($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    if($row['id_province'] == $id_province) {
                        return true;
                    }
                }
            }
            return false;
        }

        public function get_payment_methods($id_cart) {
            usleep($this->sleep);
            $cart_price = $this->get_cart_total_price($id_cart);
            $sql = 'SELECT s.*, l.name AS payment_name FROM '.DDBB_PREFIX.'payment_methods AS s
                        INNER JOIN '.DDBB_PREFIX.'payment_methods_language AS l ON l.id_payment_method = s.id_payment_method
                        INNER JOIN '.DDBB_PREFIX.'ct_languages AS a ON a.id_language = l.id_language
                    WHERE s.id_state = ? AND a.name = ?';
            $result = $this->query($sql, array(UTils::IDACTIVE, strtolower(LANG)));
            $html = '';
            if($result->num_rows > 0) {
                // I collect the values ​​from the client's address
                $sql = 'SELECT * FROM users_addresses WHERE id_user_address = ? LIMIT 1';
                $result_address = $this->query($sql, $_POST['id_user_address']);
                $row_address = $result_address->fetch_assoc();
                while($row = $result->fetch_assoc()) {
                    if($cart_price < $row['min_order_value'] || ($cart_price > $row['max_order_value'] && $row['max_order_value'] != 0)) {
                        continue;
                    }
                    // I check if the active payment methods covers the selected zone
                    $sql = 'SELECT s.id_payment_zone FROM '.DDBB_PREFIX.'payment_methods_zones AS s
                                INNER JOIN payment_zones AS z ON z.id_payment_zone = s.id_payment_zone
                            WHERE s.id_payment_method = ? AND z.id_state = ?';
                    $result_zones = $this->query($sql, array($row['id_payment_method'], UTils::IDACTIVE));
                    if($result_zones->num_rows > 0) {
                        while($row_zone = $result_zones->fetch_assoc()) {
                            if(!$this->check_payment_zone_continent($row_address['id_continent'], $row_zone['id_payment_zone'])) {
                                continue;
                            }
                            if(!$this->check_payment_zone_country($row_address['id_country'], $row_zone['id_payment_zone'])) {
                                continue;
                            }
                            if(!$this->check_payment_zone_province($row_address['id_province'], $row_zone['id_payment_zone'])) {
                                continue;
                            }
                            $html .= '<div>';
                            $html .=    '<label class="radio"><input type="radio" value="'.$row['id_payment_method'].'" name="input-payment-methods" class="input-payment-methods">';
                            $html .=    '<span class="checkmark"></span> '.$row['payment_name'].'.</label>';
                            $html .= '</div>';
                        }
                    }
                }
            }
            if($html == '') {
                $html = 'No payment methods found at this time.';
            }
            return array(
                'response' => 'ok',
                'html' => $html
            );
        }

        private function save_products_price_to_cart($id_cart) {
            $sql = 'SELECT c.id_cart_product, p.price, r.price_change, r.offer, r.offer_start_date, r.offer_end_date
                    FROM '.DDBB_PREFIX.'carts_products AS c
                        INNER JOIN '.DDBB_PREFIX.'products AS p ON p.id_product = c.id_product
                        INNER JOIN '.DDBB_PREFIX.'products_related AS r ON r.id_product_related = c.id_product_related
                    WHERE c.id_cart = ? ORDER BY c.id_cart_product';
            $result = $this->query($sql, array($id_cart));
            if($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    // If you have an offer and it is within the deadline
                    $offer = 0;
                    if($row['offer'] != 0 && date('Y-m-d') > $row['offer_start_date'] && date('Y-m-d') < $row['offer_end_date']) {
                        $offer = $row['offer'];
                    }
                    $price = $row['price'] + $row['price_change'] - $offer;
                    $sql = 'UPDATE '.DDBB_PREFIX.'carts_products SET price = ? WHERE id_cart_product = ? LIMIT 1';
                    $this->query($sql, array(
                        $price,
                        $row['id_cart_product']
                    ));
                }
            }
        }

        public function save_order_to_cart() {
            $transactionId = uniqid().'-'.rand(1000, 9999);
            $sql = 'UPDATE '.DDBB_PREFIX.'carts SET id_user_address = ?, id_user_billing_address = ?,
                        id_shipping_method = ?, id_payment_method = ?, comments = ?, id_transaction = ?
                    WHERE id_cart = ? LIMIT 1';
            $this->query($sql, array(
                $_POST['id_user_address'],
                $_POST['id_user_billing_address'],
                $_POST['id_shipping_method'],
                $_POST['id_payment_method'],
                $_POST['comments'],
                $transactionId,
                $_COOKIE['id_cart']
            ));
            // I save the current price of the products in the cart
            $this->save_products_price_to_cart($_COOKIE['id_cart']);
            // Payment by card via Stripe
            if($_POST['id_payment_method'] == 1) {
                // I get the keys from Stripe
                $sql = 'SELECT * FROM '.DDBB_PREFIX.'configuration WHERE name = "STRIPE_ENV" LIMIT 1';
                $result_env = $this->query($sql);
                $row_env = $result_env->fetch_assoc();
                if($row_env['value'] == 'pro') {
                    $sql = 'SELECT
                                (SELECT value FROM settings WHERE name = "STRIPE_DEV_TOKEN" LIMIT 1) AS token,
                                (SELECT value FROM settings WHERE name = "STRIPE_DEV_TOKEN_PK" LIMIT 1) AS token_pk';
                    $result_stripe = $this->query($sql);
                } else {
                    $sql = 'SELECT
                                (SELECT value FROM settings WHERE name = "STRIPE_DEV_TOKEN" LIMIT 1) AS token,
                                (SELECT value FROM settings WHERE name = "STRIPE_DEV_TOKEN_PK" LIMIT 1) AS token_pk';
                    $result_stripe = $this->query($sql);
                }
                $row_stripe = $result_stripe->fetch_assoc();
                $cart_price = $this->get_cart_total_price($_COOKIE['id_cart']);
                $sessionId = $this->stripe_create_session($row_stripe['token'], $transactionId, $cart_price);
                // I save the transaction id with Stripe
                $sql = 'UPDATE '.DDBB_PREFIX.'carts SET id_stripe = ? WHERE id_cart = ? LIMIT 1';
                $this->query($sql, array($sessionId, $_COOKIE['id_cart']));
                return array(
                    'response' => 'ok',
                    'token' => $row_stripe['token_pk'],
                    'session_id' => $sessionId
                );
            }
            $url = '';
            // Payment by back transfer
            if($_POST['id_payment_method'] == 2) {
                $this->save_order_from_cart($_COOKIE['id_cart']);
                $url = Utils::getRoute('checkout-bank-transfer', array('transaction_id' => $transactionId));
                return array(
                    'response' => 'ok',
                    'url' => $url
                );
            }
            // Payment by cash on delivery
            if($_POST['id_payment_method'] == 3) {
                $this->save_order_from_cart($_COOKIE['id_cart']);
                $url = Utils::getRoute('checkout-cash-delivery', array('transaction_id' => $transactionId));
                return array(
                    'response' => 'ok',
                    'url' => $url
                );
            }
            return array(
                'response' => 'error',
                'message' => 'An error occurred while applying the payment method. Please try again.'
            );
        }

        private function stripe_create_session($token, $transactionId, $amount) {
            require_once(SERVER_PATH.'/app/vendor/stripe/init.php');
            \Stripe\Stripe::setApiKey($token);
            $urlSuccess = URL_ROUTE.'/save-checkout-successful?transaction_id='.$transactionId;
            $urlFail = URL_ROUTE.'/checkout-failed';
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => HOST.': order '.$_COOKIE['id_cart'],
                            'images' => [URL_ROUTE.'/img/stripe-image.png']
                        ],
                        'unit_amount' => ($amount * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $urlSuccess,
                'cancel_url' => $urlFail,
                'customer_email' => $_SESSION['user']['email']
            ]);
            return $session->id;
        }

    }

?>