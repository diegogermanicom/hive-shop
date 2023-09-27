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