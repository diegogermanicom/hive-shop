<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2024
     */

    class Route {

        public $root = PUBLIC_ROUTE;
        public $controller = null;
        // I save all the routes that have been configured in this array
        public $routesGet = array();

        public function reset() {
            $this->root = PUBLIC_ROUTE;
            $this->controller = null;
        }

        public function setRoot($root = '') {
            $this->root = PUBLIC_ROUTE.$root;
        }
        
        public function setController($controller = null) {
            $this->controller = $controller;
        }

        public function scan_route($route): array {
            // Is dynamic
            if(strpos($route, '$') !== false) {
                $args = [];
                // I remove the text from PUBLIC_ROUTE to the path
                $bars1 = explode("/", ROUTE);
                $bars2 = explode("/", $route);
                // I check that the routes to compare are equal in length
                if(count($bars1) == count($bars2) && !in_array(ROUTE, array('', '/'))) {
                    $parse_route = '';
                    for($i = 1; $i < count($bars1); $i++) {
                        if(strpos($bars2[$i], '$') !== false) {
                            $parse_route .= '/'.$bars1[$i];
                            $args[str_replace('$', '', $bars2[$i])] = $bars1[$i];
                        } else if($bars1[$i] == $bars2[$i]) {
                            $parse_route .= '/'.$bars1[$i];
                        } else {
                            return array($route, null);
                        }
                    }
                    return array($parse_route, $args);
                } else {
                    return array($route, null);
                }
            } else {
                return array($route, null);
            }
        }

        public function call($type, $route, $function) {
            list($scan_route, $args) = $this->scan_route($route);
            if(METHOD == $type && ROUTE == $scan_route) {
                // I save the call details
                $args['_route'] = $route;
                $args['_function'] = $function;
                // If i pass a function
                if(is_callable($function)) {
                    $function($args);
                    exit;
                } else {
                    // I check if a default controller has been selected
                    if($this->controller == null) {
                        list($controller, $function_controller) = explode("#", $function);
                    } else {
                        $controller = $this->controller;
                        $function_controller = $function;
                    }
                    // If the object exists
                    $class_exist = class_exists($controller);
                    if($class_exist == true) {
                        $obj = eval('return new '.$controller.'();');
                        // If the function exists in the object
                        if(method_exists($obj, $function_controller)) {
                            eval('$obj->'.$function_controller.'($args);');    
                            exit;
                        }
                    }
                }
            } 
        }

        public function get($route, $function, $public = true) {
            if(is_array($route)) {
                foreach($route AS $r) {
                    if($public == true) {
                        array_push($this->routesGet, $this->root.$r);
                    }
                    $this->call(__FUNCTION__, $this->root.$r, $function);
                }
            } else {
                if($public == true) {
                    array_push($this->routesGet, $this->root.$route);
                }
                $this->call(__FUNCTION__, $this->root.$route, $function);
            }
        }

        public function post($route, $function) {
            $this->call(__FUNCTION__, PUBLIC_PATH.$route, $function);
        }

        public function put($route, $function) {
            $this->call(__FUNCTION__, PUBLIC_PATH.$route, $function);
        }

        public function connect($route, $function) {
            $this->call(__FUNCTION__, PUBLIC_PATH.$route, $function);
        }

        public function trace($route, $function) {
            $this->call(__FUNCTION__, PUBLIC_PATH.$route, $function);
        }

        public function patch($route, $function) {
            $this->call(__FUNCTION__, PUBLIC_PATH.$route, $function);
        }

        public function delete($route, $function) {
            $this->call(__FUNCTION__, PUBLIC_PATH.$route, $function);
        }

        public function getAdmin($route, $function) {
            $this->call('get', ADMIN_PATH.$route, $function);
        }

        public function postAdmin($route, $function) {
            $this->call('post', ADMIN_PATH.$route, $function);
        }

        public function putAdmin($route, $function) {
            $this->call('put', ADMIN_PATH.$route, $function);
        }

        public function connectAdmin($route, $function) {
            $this->call('connect', ADMIN_PATH.$route, $function);
        }

        public function traceAdmin($route, $function) {
            $this->call('trace', ADMIN_PATH.$route, $function);
        }

        public function patchAdmin($route, $function) {
            $this->call('patch', ADMIN_PATH.$route, $function);
        }

        public function deleteAdmin($route, $function) {
            $this->call('delete', ADMIN_PATH.$route, $function);
        }

        public function createSiteMap() {

        }

        public function empty() {
            if(METHOD == 'get') {
                header('Location: '.PUBLIC_ROUTE.'/404');
            } else {
                echo json_encode(array(
                    'status' => '404',
                    'error' => 'Permission denied'
                ));
            }
            exit;
        }

        public function get_categories() {
            if(METHOD == 'get' && !in_array(ROUTE, array('', '/'))) {
                if(PUBLIC_ROUTE != '' && strpos(ROUTE, PUBLIC_ROUTE) == 0) {
                    $route = explode(PUBLIC_ROUTE, ROUTE, 2)[1];
                } else {
                    $route = ROUTE;
                }
                if(!in_array($route, array('', '/'))) {
                    $sql = 'SELECT * FROM '.DDBB_PREFIX.'categories_routes AS r
                                INNER JOIN '.DDBB_PREFIX.'ct_languages AS a ON a.id_language = r.id_language
                            WHERE r.route = ? AND a.name = ? LIMIT 1';
                    $result = Utils::query($sql, array($route, strtolower(LANG)));
                    if($result->num_rows != 0) {
                        $row = $result->fetch_assoc();
                        $capp = new CApp();
                        $capp->category_route($row['id_category'], $route);
                        exit;
                    }
                }
            }
        }

        public function get_products() {
            if(METHOD == 'get' && !in_array(ROUTE, array('', '/'))) {
                if(PUBLIC_ROUTE != '' && strpos(ROUTE, PUBLIC_ROUTE) == 0) {
                    $route = explode(PUBLIC_ROUTE, ROUTE, 2)[1];
                } else {
                    $route = ROUTE;
                }
                if(!in_array($route, array('', '/'))) {
                    $sql = 'SELECT * FROM '.DDBB_PREFIX.'products_routes AS r
                                INNER JOIN '.DDBB_PREFIX.'ct_languages AS a ON a.id_language = r.id_language
                            WHERE r.route = ? AND a.name = ? LIMIT 1';
                    $result = Utils::query($sql, array($route, strtolower(LANG)));
                    if($result->num_rows != 0) {
                        $row = $result->fetch_assoc();
                        $capp = new CApp();
                        $capp->product_route($row['id_product'], $row['id_category'], $route);
                        exit;
                    }
                }
            }
        }

        public function get_categories_custom_routes() {
            if(METHOD == 'get' && !in_array(ROUTE, array('', '/'))) {
                if(PUBLIC_ROUTE != '' && strpos(ROUTE, PUBLIC_ROUTE) == 0) {
                    $route = explode(PUBLIC_ROUTE, ROUTE, 2)[1];
                } else {
                    $route = ROUTE;
                }
                if(!in_array($route, array('', '/'))) {
                    $sql = 'SELECT * FROM '.DDBB_PREFIX.'categories_custom_routes AS r
                                INNER JOIN '.DDBB_PREFIX.'ct_languages AS a ON a.id_language = r.id_language
                            WHERE r.route = ? AND a.name = ? LIMIT 1';
                    $result = Utils::query($sql, array($route, strtolower(LANG)));
                    if($result->num_rows != 0) {
                        $row = $result->fetch_assoc();
                        $capp = new CApp();
                        $capp->category_route($row['id_category'], $route);
                        exit;
                    }
                }
            }
        }

        public function get_products_custom_routes() {
            if(METHOD == 'get' && !in_array(ROUTE, array('', '/'))) {
                if(PUBLIC_ROUTE != '' && strpos(ROUTE, PUBLIC_ROUTE) == 0) {
                    $route = explode(PUBLIC_ROUTE, ROUTE, 2)[1];
                } else {
                    $route = ROUTE;
                }
                if(!in_array($route, array('', '/'))) {
                    $sql = 'SELECT * FROM '.DDBB_PREFIX.'products_custom_routes AS r
                                INNER JOIN '.DDBB_PREFIX.'ct_languages AS a ON a.id_language = r.id_language
                            WHERE r.route = ? AND a.name = ? LIMIT 1';
                    $result = Utils::query($sql, array($route, strtolower(LANG)));
                    if($result->num_rows != 0) {
                        $row = $result->fetch_assoc();
                        $capp = new CApp();
                        $capp->product_route($row['id_product'], $row['id_category'], $route);
                        exit;
                    }
                }
            }            
        }

    }

?>