<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2023
     */

    class CApp extends Controller {

        // App services ------------------------------------------------
        
        public function root($args) {
            if(LANG == 'en') {
                header('Location: '.PUBLIC_ROUTE.'/home');
            } else if(LANG == 'es') {
                header('Location: '.PUBLIC_ROUTE.'/inicio');
            }
            exit;
        }

        // Store categories controller
        public function category_route($id_category, $route) {
            $app = new App('category-page');
            $data = $app->getAppData();
            $data['category'] = $app->get_category($id_category);
            if($data['category'] == null) {
                header('Location: '.PUBLIC_ROUTE.'/404');
                exit;
            }
            $data['meta']['title'] = $app->setTitle($data['category']['meta_title']);
            $data['meta']['description'] = $data['category']['meta_description'];
            $data['meta']['keywords'] = $data['category']['meta_keywords'];
            $data['og']['og:title'] = $data['category']['meta_title'];
            $data['og']['og:description'] = $data['category']['meta_description'];
            $data['og']['og:url'] = URL.$route;
            $data['routes'] = $app->get_category_routes($id_category);
            $app->update_visit_category($id_category);
            $this->view('/'.$data['category']['view_name'], $data);
        }

        // Store products controller
        public function product_route($id_product, $id_category, $route) {
            $app = new App('product-page');
            $data = $app->getAppData();
            $data['product'] = $app->get_product($id_product, $id_category);
            if($data['product'] == null) {
                header('Location: '.PUBLIC_ROUTE.'/404');
                exit;
            }
            // This variable indicates the related product that we want to see
            if(!isset($_GET['r'])) {
                $_GET['r'] = $app->get_product_related_id($data['product']['valid_ids']['valid_products_related_id']);
                if($_GET['r'] == null) {
                    header('Location: '.PUBLIC_ROUTE.'/404');
                    exit;
                }
            } else {
                // I check that the related product Id is correct
                if(!in_array($_GET['r'], $data['product']['valid_ids']['valid_products_related_id'])) {
                    header('Location: '.PUBLIC_ROUTE.'/404');
                    exit;
                }
            }
            $data['meta']['title'] = $app->setTitle($data['product']['meta_title']);
            $data['meta']['description'] = $data['product']['meta_description'];
            $data['meta']['keywords'] = $data['product']['meta_keywords'];
            $data['og']['og:title'] = $data['product']['meta_title'];
            $data['og']['og:description'] = $data['product']['meta_description'];
            $data['og']['og:url'] = URL.$route;
            $data['routes'] = $app->get_product_routes($id_product, $id_category);
            $data['product']['active_values_id'] = $app->get_product_related_values($_GET['r']);
            $app->update_visit_product($id_product);
            $this->view('/'.$data['product']['view_name'], $data);
        }

        public function home($args) {
            $app = new App('home-page');
            $data = $app->getAppData();
            if(LANG == 'en') {
                $data['meta']['title'] = $app->setTitle('Home');
            } else if(LANG == 'es') {
                $data['meta']['title'] = $app->setTitle('Inicio');
            }
            $data['routes'] = ROUTES['home'];
            $this->view('/home', $data);
        }

        public function privacy_policy($args) {
            $app = new App('privacy-policy-page');
            $data = $app->getAppData();
            if(LANG == 'en') {
                $data['meta']['title'] = $app->setTitle('Privacy Policy');
                $data['meta']['description'] = 'Find out about our privacy policies to make good use of our application.';
                $data['meta']['keywords'] .= ', privacy policy, legal, cookies, cookies policy';
            } else if(LANG == 'es') {
                $data['meta']['title'] = $app->setTitle('Política de privacidad');
                $data['meta']['description'] = 'Infórmate de nuestras políticas de privacidad para hacer un buen uso de nuestra aplicación.';
                $data['meta']['keywords'] .= ', política de privacidad, legal, cookies, política de cookies';
            }
            $data['routes'] = ROUTES['privacy-policy'];
            $this->view('/privacy-policy', $data);
        }

        public function cookie_policy($args) {
            $app = new App('cookie-policy-page');
            $data = $app->getAppData();
            if(LANG == 'en') {
                $data['meta']['title'] = $app->setTitle('Cookie Policy');
                $data['meta']['description'] = 'Find out about our cookie policies to make good use of our application.';
                $data['meta']['keywords'] .= ', cookies, cookies policy';
            } else if(LANG == 'es') {
                $data['meta']['title'] = $app->setTitle('Política de Cookie');
                $data['meta']['description'] = 'Infórmate de nuestras políticas de cookies para hacer un buen uso de nuestra aplicación.';
                $data['meta']['keywords'] .= ', cookies, cookies policy';
            }
            $data['routes'] = ROUTES['cookie-policy'];
            $this->view('/cookie-policy', $data);
        }

        public function service_down($args) {
            $app = new App('service-down-page');
            $data = $app->getAppData();
            if(LANG == 'en') {
                $data['meta']['title'] = $app->setTitle('Service Down');
                $data['meta']['description'] = 'We are making improvements to our app. In a very short time we will be back.';
            } else if(LANG == 'es') {
                $data['meta']['title'] = $app->setTitle('Servicio caído');
                $data['meta']['description'] = 'Estamos realizando mejoras en nuestra aplicación. En muy poco tiempo estaremos de vuelta.';
            }
            $data['routes'] = ROUTES['service-down'];
            $this->view('/service-down', $data);
        }

        public function access($args) {
            $app = new App('access-page');
            $app->security_app_logout();
            $data = $app->getAppData();
            if(LANG == 'en') {
                $data['meta']['title'] = $app->setTitle('Access');
                $data['meta']['description'] = 'Access your store user account or create a new account.';
            } else if(LANG == 'es') {
                $data['meta']['title'] = $app->setTitle('Acceso');
                $data['meta']['description'] = 'Accede a tu cuenta de usuario de la tienda o crea una cuenta nueva.';
            }
            $data['routes'] = ROUTES['access'];
            $this->view('/access', $data);
        }

        public function register($args) {
            $app = new App('register-page');
            $app->security_app_logout();
            $data = $app->getAppData();
            if(LANG == 'en') {
                $data['meta']['title'] = $app->setTitle('Register');
                $data['meta']['description'] = 'Create your user account in the store to have a record of your orders.';
            } else if(LANG == 'es') {
                $data['meta']['title'] = $app->setTitle('Registro');
                $data['meta']['description'] = 'Crea tu cuenta de usuario en la tienda para tener un registro de tus pedidos.';
            }
            $data['routes'] = ROUTES['register'];
            $this->view('/register', $data);
        }

        public function page_404($args) {
            $app = new App('404-page');
            $data = $app->getAppData();
            $data['meta']['title'] = $app->setTitle('404');
            if(LANG == 'en') {
                $data['meta']['description'] = 'If you have come this far, it is because we do not have the page you are looking for.';
                $data['meta']['keywords'] .= ', 404, not found, missing';
            } else if(LANG == 'es') {
                $data['meta']['description'] = 'Si has llegado hasta aquí es porque no tenemos la página que buscas.';
                $data['meta']['keywords'] .= ', 404, no encontrado, pedido';
            }
            $this->view('/404', $data);
        }

        public function logout($args) {
            $app = new App();
            $app->security_app_login();
            $app->logout();
            header('Location: '.PUBLIC_ROUTE.'/?logout');
            exit;
        }

        public function validate_email($args) {
            if(!isset($_GET['code'])) {
                header('Location: '.PUBLIC_ROUTE.'/');
                exit;
            }
            $app = new App();
            $data = $app->getAppData();
            $data['meta']['title'] = $app->setTitle('Validate email');
            $data['validate'] = $app->validate_email($_GET['code']);
            $this->view('/validate-email', $data);
        }

        public function cart($args) {
            $app = new App('cart-page');
            $data = $app->getAppData();
            if(LANG == 'en') {
                $data['meta']['title'] = $app->setTitle('Cart');
                $data['meta']['description'] = 'Review and modify the products in your cart.';
            } else if(LANG == 'es') {
                $data['meta']['title'] = $app->setTitle('Carrito');
                $data['meta']['description'] = 'Revisa y modifica los productos de tu carrito.';
            }
            $data['routes'] = ROUTES['cart'];
            $this->view('/cart', $data);
        }

        public function checkout($args) {
            $app = new App('checkout-page');
            $data = $app->getAppData();
            if(LANG == 'en') {
                $data['meta']['title'] = $app->setTitle('Checkout');
                $data['meta']['description'] = 'Fill in the form data to finalize your order.';
            } else if(LANG == 'es') {
                $data['meta']['title'] = $app->setTitle('Tramitar pedido');
                $data['meta']['description'] = 'Rellena los datos del formulario para finalizar tu pedido.';
            }
            $data['routes'] = ROUTES['checkout'];
            $data['continents'] = $app->get_continents_active_options();
            $data['cart'] = $app->get_checkout_cart($_COOKIE['id_cart']);
            $data['javascript'] = json_encode(array(
                'shippingAddressErrorTitle' => 'Missing Data',
                'shippingAddressErrorText' => 'To continue with your order, you must fill in a shipping address.',
                'billingAddressErrorTitle' => 'Missing Data',
                'billingAddressErrorText' => 'To continue with your order, you must enter a billing address.'
            ));
            $this->view('/checkout', $data);
        }

    }

?>