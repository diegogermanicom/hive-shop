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
            return $title.' | Hive';
        }

        public function security_app_logout() {
            if(isset($_SESSION['user'])) {
                header('Location: '.PUBLIC_ROUTE.'/');
                exit;
            }
        }

        public function security_app_login() {
            if(!isset($_SESSION['user'])) {
                header('Location: '.PUBLIC_ROUTE.'/');
                exit;
            }
        }

        public function login($email, $pass, $remember) {
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
                        setcookie("user_remember", $row["remember_code"], time() + (60 * 60 * 24 * 7), PUBLIC_PATH.'/'); // 7 dias
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
                        'mensaje' => LANGTXT['user-fail']
                    );                    
                }
            } else {
                return array(
                    'response' => 'error',
                    'mensaje' => LANGTXT['error-login']
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
                    $routes[$row['language_name']] = $row['route'];
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
                    $routes[$row['language_name']] = $row['route'];
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

        public function get_cart_array($id_cart) {
            // I get the product and cart data
            $sql = 'SELECT c.*, p.price, pl.name AS product_name, r.price_change, r.stock
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
                    'products' => array()
                );
                $total = 0;
                while($row = $result->fetch_assoc()) {
                    if($row['stock'] > 0) {
                        // Get attributes
                        $attributes = $this->get_product_related_attributes($row['id_product_related'])[strtolower(LANG)];
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
                        // Product object
                        $product = array(
                            'row' => $row,
                            'attributes' => $attributes,
                            'url' => $product_url,
                            'image' => $image_url,
                            'price' => $row['price'] + $row['price_change']
                        );
                        // I adjust the price if there is a variant or offer
                        $product['price_string'] = number_format(floatval($product['price']), 2, ',', '.');
                        $product['price_string'] .= ' €';
                        $cart['total'] += ($product['price'] * $row['amount']);
                        array_push($cart['products'], $product);
                    } else {
                        // If it's out of stock, I'll remove it from the cart.
                        $sql = 'DELETE FROM '.DDBB_PREFIX.'carts_products WHERE id = ? LIMIT 1';
                        $this->query($sql, array($id_cart, $row['id']));
                    }
                }
                $cart['total_string'] = number_format(floatval($cart['total']), 2, ',', '.').' €';
            } else {
                $cart = null;
            }
            return $cart;
        }

    }

?>