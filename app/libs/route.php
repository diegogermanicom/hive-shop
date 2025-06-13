<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2025
     */

    class Route {

        public $prefix = '';
        public $root = null;
        public $controller = null;
        public $method = null;
        // I save all the routes that have been configured in this array
        public $routes;

        function __construct() {
            $this->routes = array(
                'get' => array(),
                'post' => array(),
                'put' => array(),
                'connect' => array(),
                'trace' => array(),
                'patch' => array(),
                'delete' => array()
            );
        }

        public function reset() {
            $this->prefix = '';
            $this->root = null;
            $this->controller = null;
            $this->method = null;
        }

        public function setPrefix($prefix) {
            $this->prefix = $prefix;
        }
        
        public function setRoot($root) {
            $this->root = $root;
        }

        public function setController($controller) {
            $this->controller = $controller;
        }

        public function setMethod($method) {
            $this->method = $method;
        }

        public function getRoutes() {
            return $this->routes['get'];
        }

        public function postRoutes() {
            return $this->routes['post'];
        }

        public function add($routes) {
            foreach($routes as $route) {
                // I check if a default controller has been selected
                if($this->controller == null) {
                    list($controller, $function) = explode("#", $route[1]);
                } else {
                    $controller = $this->controller;
                    $function = $route[1];
                }
                $objRoute = array(
                    'route' => PUBLIC_PATH.$this->prefix.$route[0],
                    'controller' => $controller,
                    'function' => $function,
                    'language' => LANG
                );
                // If you specify a language
                if(MULTILANGUAGE == true && isset($route[2])) {
                    //If you specify a specific route
                    if($this->root != null) {
                        $objRoute['route'] = $this->root.'/'.$route[2].$this->prefix.$route[0];
                    } else {
                        $objRoute['route'] = PUBLIC_PATH.'/'.$route[2].$this->prefix.$route[0];
                    }
                    $objRoute['language'] = $route[2];
                    // If the alias does not exist
                    if(!isset($this->routes[$this->method][$route[3]])) {
                        $this->routes[$this->method][$route[3]] = array();
                    }
                    array_push($this->routes[$this->method][$route[3]], $objRoute);
                } else {
                    //If you specify a specific route
                    if($this->root != null) {
                        $objRoute['route'] = $this->root.$this->prefix.$route[0];
                    }
                    // If the alias does not exist
                    if(!isset($this->routes[$this->method]['root'])) {
                        $this->routes[$this->method]['root'] = array();
                    }
                    array_push($this->routes[$this->method]['root'], $objRoute);
                }
            }
        }

        public function init() {
            foreach($this->routes as $method => $methods) {
                foreach($methods as $alias) {
                    foreach($alias as $route) {
                        $this->call($method, $route['route'], $route['controller'], $route['function']);
                    }
                }
            }
            $this->empty();
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

        public function call($type, $route, $controller, $function) {
            list($scan_route, $args) = $this->scan_route($route);
            if(METHOD == $type && ROUTE == $scan_route) {
                // I save the call details
                $args['_route'] = $route;
                // If the object exists
                $class_exist = class_exists($controller);
                if($class_exist == true) {
                    $obj = eval('return new '.$controller.'();');
                    // If the function exists in the object
                    if(method_exists($obj, $function)) {
                        eval('$obj->'.$function.'($args);');    
                        exit;
                    }
                }
            } 
        }

        public function get(...$routes) {
            $this->method = __FUNCTION__;
            $this->add($routes);
        }

        public function post(...$routes) {
            $this->method = __FUNCTION__;
            $this->add($routes);
        }

        public function put(...$routes) {
            $this->method = __FUNCTION__;
            $this->add($routes);
        }

        public function connect(...$routes) {
            $this->method = __FUNCTION__;
            $this->add($routes);
        }

        public function trace(...$routes) {
            $this->method = __FUNCTION__;
            $this->add($routes);
        }

        public function patch(...$routes) {
            $this->method = __FUNCTION__;
            $this->add($routes);
        }

        public function delete(...$routes) {
            $this->method = __FUNCTION__;
            $this->add($routes);
        }

        public function empty() {
            if(METHOD == 'get') {
                header('Location: '.PUBLIC_ROUTE.'/404');
            } else {
                echo json_encode(array(
                    'status' => '404',
                    'error' => 'Route not found',
                    'route' => ROUTE
                ));
            }
            exit;
        }

        public function get_categories() {
            if(METHOD == 'get' && !in_array(ROUTE, array('', '/'))) {
                if(PUBLIC_ROUTE != '' && strpos(ROUTE, PUBLIC_ROUTE) === 0) {
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
                if(PUBLIC_ROUTE != '' && strpos(ROUTE, PUBLIC_ROUTE) === 0) {
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
                if(PUBLIC_ROUTE != '' && strpos(ROUTE, PUBLIC_ROUTE) === 0) {
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
                if(PUBLIC_ROUTE != '' && strpos(ROUTE, PUBLIC_ROUTE) === 0) {
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