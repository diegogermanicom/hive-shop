<?php

    /**
     * @author Diego Martín
     * @copyright Hive®
     * @version 1.0
     * @lastUpdated 2025
     */

    class Route {

        private $route = null;
        private $controller = null;
        private $function = null;
        private $language = null;
        private $alias = null;
        private $index = null;
        // Method being processed
        private $method = null;
        // Default route variables
        private $defaultPrefix = '';
        private $defaultController = null;
        private $defaultLanguage = null;
        private $defaultIndex = true;
        // I save all the routes that have been configured in this array
        private $routes;

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
            $this->resetRoute();
            $this->resetDefault();
        }

        private function resetRoute() {
            $this->method = null;
            $this->route = null;
            $this->controller = null;
            $this->function = null;
            $this->language = null;
            $this->alias = null;
            $this->index = null;
        }

        private function resetDefault() {
            // Default route variables
            $this->defaultPrefix = '';
            $this->defaultController = null;
            $this->defaultLanguage = null;
            $this->defaultIndex = true;
        }

        public function setPrefix($prefix) {
            $this->defaultPrefix = $prefix;
        }
        
        public function setController($controller) {
            $this->defaultController = $controller;
        }

        public function setLanguage($lang) {
            $this->defaultLanguage = $lang;
        }

        public function setIndex($index) {
            $this->defaultIndex = $index;
        }

        public function resetPrefix() {
            $this->defaultPrefix = '';
        }

        public function resetController() {
            $this->defaultController = null;
        }

        public function resetLanguage() {
            $this->defaultLanguage = null;
        }

        public function resetIndex() {
            $this->defaultIndex = true;
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

        public function init() {
            foreach($this->routes as $method => $methods) {
                foreach($methods as $alias) {
                    foreach($alias as $route) {
                        $this->processRoute($method, $route);
                    }
                }
            }
            $this->get_categories();
            $this->get_products();
            $this->get_categories_custom_routes();
            $this->get_products_custom_routes();
            $this->empty();
        }

        private function scanRoute($route): array {
            // Is dynamic
            if(strpos($route, '$') !== false) {
                $args = [];
                // I remove the text from PUBLIC_ROUTE to the path
                $varsRoute = explode("/", ROUTE);
                $varsCode = explode("/", $route);
                // I check that the routes to compare are equal in length
                if(count($varsRoute) == count($varsCode) && !in_array(ROUTE, array('', '/'))) {
                    $parseRoute = '';
                    for($i = 1; $i < count($varsRoute); $i++) {
                        if(strpos($varsCode[$i], '$') !== false) {
                            $parseRoute .= '/'.$varsRoute[$i];
                            $args[str_replace('$', '', $varsCode[$i])] = $varsRoute[$i];
                        } else if($varsRoute[$i] == $varsCode[$i]) {
                            $parseRoute .= '/'.$varsRoute[$i];
                        } else {
                            return array($route, null);
                        }
                    }
                    return array($parseRoute, $args);
                } else {
                    return array($route, null);
                }
            } else {
                return array($route, null);
            }
        }
        private function processRoute($type, $route) {
            list($scan_route, $args) = $this->scanRoute($route['route']);
            // The route is valid
            if(METHOD == $type && ROUTE == $scan_route) {
                // I save the call details
                $args['_method'] = $route['method'];
                $args['_route'] = $route['route'];
                $args['_controller'] = $route['controller'];
                $args['_function'] = $route['function'];
                $args['_language'] = $route['language'];
                $args['_alias'] = $route['alias'];
                $args['_index'] = $route['index'];
                // If you pass it a function instead of a controller and a function
                if(is_callable($route['function'])) {
                    call_user_func($route['function'], $args);
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

        private function checkRepeatRoute($checkRoute) {
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

        private function addRoute() {
            if(MULTILANGUAGE == true && $this->method == 'get') {
                $this->route = PUBLIC_PATH.'/'.$this->language.$this->defaultPrefix.$this->route;
            } else {
                $this->route = PUBLIC_PATH.$this->defaultPrefix.$this->route;
            }
            // I check that the route is not repeated
            $this->checkRepeatRoute($this->route);
            // If the alias does not exist
            if(!isset($this->routes[$this->method][$this->alias])) {
                $this->routes[$this->method][$this->alias] = array();
            }
            $obj = array(
                'method' => $this->method,
                'route' => $this->route,
                'controller' => $this->controller,
                'function' => $this->function,
                'language' => $this->language,
                'alias' => $this->alias,
                'index' => $this->index
            );
            if(MULTILANGUAGE == true && $this->method == 'get') {
                $this->routes[$this->method][$this->alias][$this->language] = $obj;
            } else {
                $this->routes[$this->method][$this->alias][LANG] = $obj;
            }
        }

        private function checkRepeatAlias() {
            foreach($this->routes as $methods) {
                foreach($methods as $alias) {
                    foreach($alias as $route) {
                        if(MULTILANGUAGE == true) {
                            if($route['method'] == $this->method && $route['alias'] == $this->alias && $route['language'] == $this->language) {
                                Utils::error('The <b>'.$this->alias.'</b> alias in the <b>'.$this->language.'</b> language is repeated.');
                            }    
                        } else {
                            if($route['method'] == $this->method && $route['alias'] == $this->alias) {
                                Utils::error('The <b>'.$this->alias.'</b> alias is repeated.');
                            }    
                        }
                    }
                }
            }
        }

        public function add($alias = '', $index = null) {
            if(!is_string($alias)) {
                Utils::error('The alias value must be a string.');
            }
            // I check if it is indexed or not
            if($index === null) {
                $this->index = $this->defaultIndex;
            } else {
                if(!is_bool($index)) {
                    Utils::error('The index value must be a boolean.');
                }
                $this->index = $index;
            }
            // I collect the alias and language if indicated
            $arrayAlias = explode(":", $alias);
            if(count($arrayAlias) == 2) {
                $this->language = strtolower($arrayAlias[0]);
                $this->alias = $arrayAlias[1];
            } else {
                if($this->defaultLanguage != null) {
                    $this->language = $this->defaultLanguage;
                } else {
                    $this->language = null;
                }
                $this->alias = $arrayAlias[0];
            }
            if(MULTILANGUAGE == true && $this->method == 'get') {
                if($this->language == null) {
                    Utils::error('You must specify a language for the route <b>'.$this->route.'</b>');
                }
                if(!Utils::validateISOLanguage($this->language)) {
                    Utils::error('The language <b>'.$this->language.'</b> value of the route must be an ISO language code.');
                }
            } else {
                // I set the default language for when I create the sitemap
                $this->language = LANG;
            }
            if(!is_string($this->alias)) {
                Utils::error('The alias value must be a string.');
            }
            if($this->alias == 'admin') {
                Utils::error('The alias <b>admin</b> is reserved for the system.');
            }
            $this->checkRepeatAlias();
            $this->addRoute();
        }

        public function call($controller) {
            if(!is_callable($controller) && (!is_string($controller) || $controller == '')) {
                Utils::error('The controller must have a non-empty string value.');
            }
            if(!is_callable($controller)) {
                $arrayController = explode("@", $controller);
                if(count($arrayController) == 2) {
                    $this->controller = $arrayController[0];
                    $this->function = $arrayController[1];
                } else {                
                    if($this->defaultController == null) {
                        Utils::error('You must select a driver for the route.');
                    }
                    $this->controller = $this->defaultController;
                    $this->function = $arrayController[0];
                }
            } else {
                $this->controller = null;
                $this->function = $controller;
            }
            return $this;
        }

        public function call_admin($controller) {
            if(!is_callable($controller) && (!is_string($controller) || $controller == '')) {
                Utils::error('The controller must have a non-empty string value.');
            }
            if(!is_callable($controller)) {
                $arrayController = explode("@", $controller);
                if(count($arrayController) == 2) {
                    $this->controller = $arrayController[0];
                    $this->function = $arrayController[1];
                } else {                
                    if($this->defaultController == null) {
                        Utils::error('You must select a driver for the route.');
                    }
                    $this->controller = $this->defaultController;
                    $this->function = $arrayController[0];
                }
            } else {
                $this->controller = null;
                $this->function = $controller;
            }
            // If the alias does not exist
            if(!isset($this->routes[$this->method]['admin'])) {
                $this->routes[$this->method]['admin'] = array();
            }
            $obj = array(
                'method' => $this->method,
                'route' => ADMIN_PATH.$this->defaultPrefix.$this->route,
                'controller' => $this->controller,
                'function' => $this->function,
                'language' => null,
                'alias' => null,
                'index' => false
            );
            array_push($this->routes[$this->method]['admin'], $obj);
        }

        private function setRoute($route, $function) {
            $this->resetRoute();
            if(!is_string($route)) {
                Utils::error('The route value must be a string.');
            }
            if($route != '' && strpos($route, '/') !== 0) {
                Utils::error('The first character of the path must be <b>/</b>.');
            }
            $this->route = $route;
            $this->method = $function;
        }

        public function get($route) {
            $this->setRoute($route, __FUNCTION__);
            return $this;
        }

        public function post($route) {
            $this->setRoute($route, __FUNCTION__);
            return $this;
        }

        public function put($route) {
            $this->setRoute($route, __FUNCTION__);
            return $this;
        }

        public function connect($route) {
            $this->setRoute($route, __FUNCTION__);
            return $this;
        }

        public function trace($route) {
            $this->setRoute($route, __FUNCTION__);
            return $this;
        }

        public function patch($route) {
            $this->setRoute($route, __FUNCTION__);
            return $this;
        }

        public function delete($route) {
            $this->setRoute($route, __FUNCTION__);
            return $this;
        }

        public function empty() {
            if(METHOD == 'get') {
                Utils::redirect('page-404');
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