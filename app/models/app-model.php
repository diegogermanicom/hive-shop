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

    }

?>