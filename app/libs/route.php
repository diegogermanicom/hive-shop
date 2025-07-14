<?php

    /**
     * @author Diego Martín
     * @copyright Hive®
     * @version 1.0
     * @lastUpdated 2025
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
            // I need to make a dump because routes with associated functions cannot belong to a constant variable.
            $routes = array_merge([], $this->routes['get']);
            foreach($routes as $indexAlias => $alias) {
                foreach($alias as $indexRoute => $route) {
                    if(is_callable($route['function'])) {
                        $routes[$indexAlias][$indexRoute]['function'] = null;
                    }
                }
            }
            return $routes;
        }

        public function postRoutes() {
            // I need to make a dump because routes with associated functions cannot belong to a constant variable.
            $routes = array_merge([], $this->routes['post']);
            foreach($routes as $indexAlias => $alias) {
                foreach($alias as $indexRoute => $route) {
                    if(is_callable($route['function'])) {
                        $routes[$indexAlias][$indexRoute]['function'] = null;
                    }
                }
            }
            return $routes;
        }

        private function add($routes) {
            foreach($routes as $route) {
                $objRoute = array(
                    'route' => null,
                    'controller' => null,
                    'function' => null,
                    'language' => LANG,
                    'index' => true
                );
                if($this->method == 'get') {
                    if(MULTILANGUAGE == true) {
                        if(count($route) < 2 || count($route) > 5) {
                            Utils::error('The values passed to the function to manage the route are invalid. ('.count($route).')');
                        }
                        if(isset($route[3])) {
                            if(!Utils::validateISOLanguage($route[2])) {
                                Utils::error('The language value of the route must be an ISO language code.');
                            }
                            if(!is_string($route[3]) || $route[3] == '' || strlen($route[3] > 50)) {
                                Utils::error('The alias value of the route must be a string of maximum 50 characters in <b>'.$route[3].'</b>.');
                            }
                            if($route[3] == 'root') {
                                Utils::error('The alias <b>root</b> cannot be used because it is reserved for the system.');
                            }
                        }
                        // If there is an indexing configuration in the sitemap, I save it.
                        if(count($route) == 3) {
                            $objRoute['index'] = $route[2];
                        }
                        if(isset($route[4])) {
                            $objRoute['index'] = $route[4];
                        }
                    } else {
                        if(count($route) < 2 || count($route) > 3) {
                            Utils::error('The values passed to the function to manage the route are invalid. ('.count($route).')');
                        }
                        // If there is an indexing configuration in the sitemap, I save it.
                        if(isset($route[2])) {
                            $objRoute['index'] = $route[2];
                        }
                    }    
                    if(count($route) == 3 && !is_bool($route[2]) || count($route) == 5 && !is_bool($route[4])) {
                        Utils::error('The value to define the indexing of a route must be boolean.');
                    }
                } else {
                    if(count($route) != 2) {
                        Utils::error('The values passed to the function to manage the route are invalid. ('.count($route).')');
                    }
                }
                if(!is_string($route[0])) {
                    Utils::error('The route value must be a string '.$route[0].'.');
                }
                if(!is_callable($route[1])) {
                    if($route[1] == '' || !is_string($route[1])) {
                        Utils::error('The controller value must be a string '.$route[1].'.');
                    }
                    // I check if a default controller has been selected
                    $parts = explode("@", $route[1]);
                    if(count($parts) > 2) {
                        Utils::error('An error occurred while processing the route handler '.$route[0].'.');
                    }    
                    if($this->controller == null) {
                        if(count($parts) != 2) {
                            Utils::error('An error occurred while processing the route handler '.$route[0].'.');
                        }
                        list($objRoute['controller'], $objRoute['function']) = $parts;
                    } else {
                        if(count($parts) == 2) {
                            list($objRoute['controller'], $objRoute['function']) = $parts;
                        } else {
                            $objRoute['controller'] = $this->controller;
                            $objRoute['function'] = $route[1];
                        }
                    }
                    if($objRoute['controller'] == '' || is_numeric($objRoute['controller'][0])) {
                        Utils::error('An error occurred while processing the route handler '.$route[0].'.');
                    }
                    if($objRoute['function'] == '' || is_numeric($objRoute['function'][0])) {
                        Utils::error('An error occurred while processing the route function '.$route[0].'.');
                    }
                } else {
                    $objRoute['function'] = $route[1];
                }
                // If you specify a language
                if(MULTILANGUAGE == true && isset($route[3])) {
                    //If you specify a specific route
                    if($this->root == null) {
                        $objRoute['route'] = PUBLIC_PATH.'/'.$route[2].$this->prefix.$route[0];
                    } else {
                        $objRoute['route'] = $this->root.'/'.$route[2].$this->prefix.$route[0];
                    }
                    $objRoute['language'] = strtolower($route[2]);
                    // If the alias does not exist
                    if(!isset($this->routes[$this->method][$route[3]])) {
                        $this->routes[$this->method][$route[3]] = array();
                    }
                    // I check that the route is not repeated
                    $this->checkRepeat($objRoute['route']);
                    $this->routes[$this->method][$route[3]][$route[2]] = $objRoute;
                } else {
                    //If you specify a specific route
                    if($this->root == null) {
                        $objRoute['route'] = PUBLIC_PATH.$this->prefix.$route[0];
                    } else {
                        $objRoute['route'] = $this->root.$this->prefix.$route[0];
                    }
                    // If the alias does not exist
                    if(!isset($this->routes[$this->method]['root'])) {
                        $this->routes[$this->method]['root'] = array();
                    }
                    // I check that the route is not repeated
                    $this->checkRepeat($objRoute['route']);
                    array_push($this->routes[$this->method]['root'], $objRoute);
                }
            }
        }

        private function checkRepeat($checkRoute) {
            foreach($this->routes as $methods) {
                foreach($methods as $alias) {
                    foreach($alias as $route) {
                        if($route['route'] == $checkRoute) {
                            Utils::error('The <b>'.$checkRoute.'</b> route is repeated.');
                        }
                    }
                }
            }
        }

        public function init() {
            foreach($this->routes as $method => $methods) {
                foreach($methods as $alias) {
                    foreach($alias as $route) {
                        $this->call($method, $route);
                    }
                }
            }
            $this->get_categories();
            $this->get_products();
            $this->get_categories_custom_routes();
            $this->get_products_custom_routes();
            $this->empty();
        }

        private function scan_route($route): array {
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
        private function call($type, $route) {
            list($scan_route, $args) = $this->scan_route($route['route']);
            // The route is valid
            if(METHOD == $type && ROUTE == $scan_route) {
                // I save the call details
                $args['_route'] = $route['route'];
                $args['_controller'] = $route['controller'];
                $args['_function'] = $route['function'];
                $args['_index'] = $route['index'];
                // If you pass it a function instead of a controller and a function
                if(is_callable($route['function'])) {
                    call_user_func($route['function']);
                } else {
                    // If the object exists
                    $class_exist = class_exists($route['controller']);
                    if($class_exist == true) {
                        $obj = new $route['controller']();
                        // If the function exists in the object
                        if(method_exists($obj, $route['function'])) {
                            call_user_func([$obj, $route['function']], $args);
                            exit;
                        } else {
                            $message = 'The function you are trying to access via the <b>'.$route['route'].'</b> route does not exist.';
                            Utils::error($message);
                        }
                    } else {
                        $message = 'The controller you are trying to access via the <b>'.$route['route'].'</b> route does not exist.';
                        Utils::error($message);
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
                Utils::redirect('/404');
            } else {
                echo json_encode(array(
                    'status' => '404',
                    'error' => 'Route not found'
                ));
                exit;
            }
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