<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2023
     */   

    class AppModel extends Model {

        function __construct() {
            parent::__construct();
        }

        public function setTitle($title) {
            return $title.META_EXTRA_TITLE;
        }

        public function security_app_logout() {
            if(isset($_SESSION['user'])) {
                if(METHOD == 'get') {
                    header('Location: '.PUBLIC_ROUTE.'/');
                    exit;
                } else {
                    return json_encode(array(
                        'response' => 'error',
                        'message' => 'You do not have permissions to perform this action.'
                    ));
                }
            }
        }

        public function security_app_login() {
            if(!isset($_SESSION['user'])) {
                if(METHOD == 'get') {
                    header('Location: '.PUBLIC_ROUTE.'/');
                    exit;
                } else {
                    return json_encode(array(
                        'response' => 'error',
                        'message' => 'You do not have permissions to perform this action.'
                    ));
                }
            }
        }

        public function login($email, $pass, $remember = 0) {
            // Pass must come in md5
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'users WHERE email = ? AND pass = ? LIMIT 1';
            $result = $this->query($sql, array($email, $pass));
            if($result->num_rows != 0) {
                $row = $result->fetch_assoc();
                if($row['id_state'] == 2) {
                    $sql = 'UPDATE '.DDBB_PREFIX.'users SET last_access = NOW(), ip_last_access = ? WHERE id_user = ? LIMIT 1';
                    $this->query($sql, array($this->get_ip(), $row['id_user']));
                    $_SESSION['user'] = [
                        'id_user' => $row['id_user'],
                        'email' => $row['email'],
                        'name' => $row['name']
                    ];
                    // If the user still does not have a remember code, I will create one for him
                    if($row["remember_code"] == '') {
                        $row["remember_code"] = uniqid();
                        $sql = 'UPDATE '.DDBB_PREFIX.'users SET remember_code = ? WHERE id_user = ? LIMIT 1';
                        $this->query($sql, array($row["remember_code"], $row['id_user']));
                    }
                    if($remember == 1) {
                        Utils::initCookie('user_remember', $row["remember_code"], Utils::ONEMONTH);
                    }
                    // I associate the cart to the user
                    $sql = 'UPDATE '.DDBB_PREFIX.'carts SET id_user = ? WHERE id_cart = ? AND id_user = 0 LIMIT 1';
                    $this->query($sql, array($row['id_user'], $_COOKIE['id_cart']));
                    // If you come from the place order page
                    if(isset($_POST['checkout']) && $_POST['checkout'] == 1) {
                        if(LANG == 'en') {
                            $url = PUBLIC_ROUTE.'/checkout?login';
                        } else if(LANG == 'es') {
                            $url = PUBLIC_ROUTE.'/tramitar-pedido?login';
                        }
                    } else {
                        $url = PUBLIC_ROUTE.'/?login';
                    }
                    return array(
                        'response' => 'ok',
                        'url' => $url
                    );
                } else {
                    return array(
                        'response' => 'error',
                        'message' => LANGTXT['user-fail']
                    );                    
                }
            } else {
                return array(
                    'response' => 'error',
                    'message' => LANGTXT['error-login']
                );
            }
        }

        public function get_product_routes($id_product, $id_category) {
            // I take the route of the product in a certain category in all languages
            $sql = 'SELECT r.*, l.name AS language_name FROM products_routes AS r
                        INNER JOIN ct_languages AS l ON l.id_language = r.id_language
                    WHERE r.id_product = ? AND r.id_category = ?';
            $result = $this->query($sql, array($id_product, $id_category));
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

        public function get_product_routes_main_category($id_product) {
            // I take the route of the product in its main category in all languages
            $sql = 'SELECT r.route, l.name AS language_name FROM products_categories AS c
                        INNER JOIN products_routes AS r ON r.id_category = c.id_category
                        INNER JOIN ct_languages AS l ON l.id_language = r.id_language
                    WHERE r.id_product = ? AND c.main = 1';
            $result = $this->query($sql, array($id_product));
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

        public function get_product_related_attributes($id_product_related) {
            $sql = 'SELECT al.name AS attribute_name, vl.name AS value_name, a.name AS language_name
                    FROM products_related_attributes AS r
                        INNER JOIN attributes_language AS al ON al.id_attribute = r.id_attribute
                        INNER JOIN attributes_value_language AS vl ON vl.id_attribute_value = r.id_attribute_value
                        INNER JOIN ct_languages AS a ON a.id_language = al.id_language
                    WHERE r.id_product_related = ? AND vl.id_language = a.id_language';
            $result = $this->query($sql, array($id_product_related));
            if($result->num_rows != 0) {
                $attributes = array();
                while($row = $result->fetch_assoc()) {
                    if(!isset($attributes[$row['language_name']])) {
                        $attributes[$row['language_name']] = array();
                    }
                    array_push($attributes[$row['language_name']], $row);
                }
                return $attributes;
            } else {
                return null;
            }
        }

        public function refresh_cart_stock($id_cart) {
            $sql = 'SELECT c.id, c.amount, r.stock
                    FROM '.DDBB_PREFIX.'carts_products AS c
                        INNER JOIN '.DDBB_PREFIX.'products_related AS r ON r.id_product_related = c.id_product_related
                    WHERE c.id_cart = ? ORDER BY c.id';
            $result = $this->query($sql, array($id_cart));
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    // If there is less stock than what you ask for, I update it
                    if($row['stock'] < $row['amount']) {
                        $sql = 'UPDATE '.DDBB_PREFIX.'carts_products SET amount = ? WHERE id = ? LIMIT 1';
                        $this->query($sql, array($row['stock'], $row['id']));
                    }
                    // If it's out of stock, I'll remove it from the cart.
                    if($row['stock'] == 0) {
                        $sql = 'DELETE FROM '.DDBB_PREFIX.'carts_products WHERE id = ? LIMIT 1';
                        $this->query($sql, array($id_cart, $row['id']));
                    }
                }
            }
        }

        public function get_cart_total_price($id_cart) {
            $sql = 'SELECT p.price, r.price_change, r.offer, r.offer_start_date, r.offer_end_date
                    FROM '.DDBB_PREFIX.'carts_products AS c
                        INNER JOIN '.DDBB_PREFIX.'products AS p ON p.id_product = c.id_product
                        INNER JOIN '.DDBB_PREFIX.'products_related AS r ON r.id_product_related = c.id_product_related
                    WHERE c.id_cart = ? ORDER BY c.id';
            $result = $this->query($sql, array($id_cart));
            $total = 0;
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    // If you have an offer and it is within the deadline
                    $offer = 0;
                    if($row['offer'] != 0 && date('Y-m-d') > $row['offer_start_date'] && date('Y-m-d') < $row['offer_end_date']) {
                        $offer = $row['offer'];
                    }
                    $price = $row['price'] + $row['price_change'] - $offer;
                    $total += $price;
                }
            }
            return $total;
        }

        public function get_cart_total_weight($id_cart) {
            $sql = 'SELECT p.weight, r.weight_change
                    FROM '.DDBB_PREFIX.'carts_products AS c
                        INNER JOIN '.DDBB_PREFIX.'products AS p ON p.id_product = c.id_product
                        INNER JOIN '.DDBB_PREFIX.'products_related AS r ON r.id_product_related = c.id_product_related
                    WHERE c.id_cart = ? ORDER BY c.id';
            $result = $this->query($sql, array($id_cart));
            $total = 0;
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $weight = $row['weight'] + $row['weight_change'];
                    $total += $weight;
                }
            }
            return $total;
        }

        public function get_cart_array($id_cart) {
            // I get the product and cart data
            $sql = 'SELECT c.*, p.price, pl.name AS product_name, r.price_change, r.stock, r.offer, r.offer_start_date, r.offer_end_date
                    FROM '.DDBB_PREFIX.'carts_products AS c
                        INNER JOIN '.DDBB_PREFIX.'products AS p ON p.id_product = c.id_product
                        INNER JOIN '.DDBB_PREFIX.'products_related AS r ON r.id_product_related = c.id_product_related
                        INNER JOIN '.DDBB_PREFIX.'products_language AS pl ON pl.id_product = c.id_product
                        INNER JOIN '.DDBB_PREFIX.'ct_languages AS a ON a.id_language = pl.id_language
                    WHERE c.id_cart = ? AND a.name = ? ORDER BY c.id';
            $result = $this->query($sql, array($id_cart, strtolower(LANG)));
            if($result->num_rows != 0) {
                $cart = array(
                    'total' => 0,
                    'total_string' => '',
                    'free_shipping' => false,
                    'products' => array(),
                    'codes' => $this->get_cart_codes_array($id_cart)
                );
                while($row = $result->fetch_assoc()) {
                    // Get attributes
                    $attributes = $this->get_product_related_attributes($row['id_product_related'])[strtolower(LANG)];
                    // Get the route of the main product related of the product
                    $product_url = null;
                    $product_routes = $this->get_product_routes($row['id_product'], $row['id_category']);
                    foreach($product_routes as $route) {
                        if($route['language'] == LANG) {
                            $product_url = $route['route'].'?r='.$row['id_product_related'];
                        }
                    }
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
                    // If you have an offer and it is within the deadline
                    $offer = 0;
                    if($row['offer'] != 0 && date('Y-m-d') > $row['offer_start_date'] && date('Y-m-d') < $row['offer_end_date']) {
                        $offer = $row['offer'];
                    }
                    $price = $row['price'] + $row['price_change'] - $offer;
                    // I check if there are discount codes for this product
                    foreach($cart['codes'] as $code) {
                        if($code['exclude_sales'] == 1 && $offer != 0) {
                            // The code is not compatible with products on sale
                        } else {
                            // If you have rules
                            $discount = false;
                            if(count($code['rules']) > 0) {
                                foreach($code['rules'] as $rule) {
                                    // Product
                                    if($rule['id_code_rule_type'] == 1) {
                                        foreach($rule['elements'] as $element) {
                                            if($element == $row['id_product']) {
                                                $discount = true;
                                                break;
                                            }
                                        }
                                    } else if($rule['id_code_rule_type'] == 2) {
                                        // I check if any of the categories match the rule
                                        $sql = 'SELECT id_category FROM '.DDBB_PREFIX.'products_categories WHERE id_product = ?';
                                        $result_categories = $this->query($sql, array($row['id_product']));
                                        while($row_categories = $result_categories->fetch_assoc()) {
                                            foreach($rule['elements'] as $element) {
                                                if($element == $row_categories['id_category']) {
                                                    $discount = true;
                                                    break 2;
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                $discount = true;
                            }    
                            if($discount == true) {
                                // Free shipping
                                if($code['free_shipping'] == 1) {
                                    $cart['free_shipping'] = true;
                                }
                                // Percentage
                                $percentage_price = ($price * $code['amount']) / 100;
                                if($code['type'] == 1 && $percentage_price < $price) {
                                    $price -= $percentage_price;
                                } else if($code['type'] == 2 && $code['amount'] < $price) {
                                    $price -= $code['amount'];
                                }
                            }
                        }
                    }
                    // Product object
                    $product = array(
                        'row' => $row,
                        'attributes' => $attributes,
                        'url' => $product_url,
                        'image' => $image_url,
                        'price' => $price
                    );
                    // I create the price in text string
                    $product['price_string'] = number_format(floatval($product['price']), 2, ',', '.');
                    $product['price_string'] .= ' €';
                    $cart['total'] += ($product['price'] * $row['amount']);
                    array_push($cart['products'], $product);
                }
                $cart['total_string'] = number_format(floatval($cart['total']), 2, ',', '.').' €';
            } else {
                $cart = null;
            }
            return $cart;
        }

        public function get_cart_codes_array($id_cart) {
            // I collect the discount codes and create an array
            $sql = 'SELECT o.* FROM '.DDBB_PREFIX.'carts_codes AS c
                        INNER JOIN '.DDBB_PREFIX.'codes AS o ON c.id_code = o.id_code
                    WHERE id_cart = ?';
            $result_codes = $this->query($sql, array($id_cart));
            $codes = array();
            if($result_codes->num_rows != 0) {
                while($row_code = $result_codes->fetch_assoc()) {
                    $result_code = $this->check_code($row_code['code'], $id_cart);
                    // If the code returns an error or it is not compatible with other codes I delete it
                    if($result_code['response'] = 'error' && $result_code['type'] != 1) {
                        $sql = 'DELETE FROM carts_codes WHERE id_code = ? AND id_cart = ? LIMIT 1';
                        $this->query($sql, array($row_code['id_code'], $id_cart));
                    } else {
                        // I collect the rules of the code if it has
                        $sql = 'SELECT * FROM '.DDBB_PREFIX.'codes_rules WHERE id_code = ?';
                        $result_rules = $this->query($sql, array($row_code['id_code']));
                        $rules = array();
                        if($result_rules->num_rows != 0) {
                            while($row_rule = $result_rules->fetch_assoc()) {
                                // I collect the ids of the rule elements
                                $sql = 'SELECT * FROM '.DDBB_PREFIX.'codes_rules_elements WHERE id_code_rule = ?';
                                $result_elements = $this->query($sql, array($row_rule['id_code_rule']));
                                $elements = array();
                                while($row_element = $result_elements->fetch_assoc()) {
                                    array_push($elements, $row_element['id_element']);
                                }
                                $row_rule['elements'] = $elements;
                                array_push($rules, $row_rule);
                            }
                        }
                        $row_code['rules'] = $rules;
                        array_push($codes, $row_code);
                    }
                }
            }
            return $codes;
        }

        public function check_code($code, $id_cart) {
            $cart_price = $this->get_cart_total_price($id_cart);
            // If you have no products in your cart
            if($cart_price == 0) {
                return array(
                    'response' => 'error',
                    'type' => 10,
                    'title' => LANGTXT['code-empty-cart-title'],
                    'description' => LANGTXT['code-empty-cart-description']
                );
            }
            $code = strtoupper($code);
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'codes WHERE code = ? AND id_state = 2 LIMIT 1';
            $result = $this->query($sql, array($code));
            // If the code does not exist or is inactive
            if($result->num_rows == 0) {
                return array(
                    'response' => 'error',
                    'type' => 9,
                    'title' => LANGTXT['apply-code-ko-title'],
                    'description' => LANGTXT['apply-code-ko-description']
                );
            }
            $row = $result->fetch_assoc();
            // I check if it is already added
            $sql= 'SELECT id_cart_code FROM '.DDBB_PREFIX.'carts_codes WHERE id_code = ? AND id_cart = ?';
            $result_cart_code = $this->query($sql, array($row['id_code'], $id_cart));
            if($result_cart_code->num_rows != 0) {
                return array(
                    'response' => 'error',
                    'type' => 1,
                    'title' => LANGTXT['code-have-it-title'],
                    'description' => LANGTXT['code-have-it-description']
                );    
            }
            // I'll check if it's still available
            if($row['available'] <= 0) {
                return array(
                    'response' => 'error',
                    'type' => 2,
                    'title' => LANGTXT['code-no-available-title'],
                    'description' => LANGTXT['code-no-available-description']
                );    
            }
            // If it is out of date
            if($row['start_date'] > date('Y-m-d')) {
                return array(
                    'response' => 'error',
                    'type' => 3,
                    'title' => LANGTXT['code-soon-title'],
                    'description' => LANGTXT['code-soon-description']
                );    
            }
            if($row['end_date'] < date('Y-m-d')) {
                return array(
                    'response' => 'error',
                    'type' => 4,
                    'title' => LANGTXT['code-expired-title'],
                    'description' => LANGTXT['code-expired-description']
                );    
            }
            // If there is a limit of uses per user
            if(isset($_SESSION['user']) && $row['per_user'] != 0) {
                $sql = 'SELECT id_order FROM '.DDBB_PREFIX.'orders WHERE id_code = ? AND id_user = ?';
                $result_num_codes = $this->query($sql, array($row['id_code'], $_SESSION['user']['id_user']));
                if($row['per_user'] <= $result_num_codes->num_rows) {
                    return array(
                        'response' => 'error',
                        'type' => 5,
                        'title' => LANGTXT['code-per-user-title'],
                        'description' => LANGTXT['code-per-user-description']
                    );    
                }
            }
            // If the cart value does not exceed the discount code minimum
            if($cart_price < $row['minimum']) {
                return array(
                    'response' => 'error',
                    'type' => 6,
                    'title' => LANGTXT['code-minimum-title'],
                    'description' => LANGTXT['code-minimum-description'].$row['minimum'].'€.'
                );    
            }
            // I check if it is compatible with other codes of the cart
            if($row['compatible'] == 0) {
                $sql = 'SELECT id_cart_code FROM '.DDBB_PREFIX.'carts_codes WHERE id_cart = ?';
                $result_compatible = $this->query($sql, array($id_cart));
                if($result_compatible->num_rows != 0) {
                    return array(
                        'response' => 'error',
                        'type' => 7,
                        'title' => LANGTXT['code-compatible-title'],
                        'description' => LANGTXT['code-compatible-description']
                    );
                }
            }
            if($row['registered'] == 1 && !isset($_SESSION['user'])) {
                return array(
                    'response' => 'ok',
                    'type' => 11,
                    'id_code' => $row['id_code'],
                    'title' => LANGTXT['code-registered-title'],
                    'description' => LANGTXT['code-registered-description']
                );    
            }
            return array(
                'response' => 'ok',
                'type' => 8,
                'id_code' => $row['id_code'],
                'title' => LANGTXT['apply-code-ok-title'],
                'description' => LANGTXT['apply-code-ok-description']
            );
        }

    }

?>