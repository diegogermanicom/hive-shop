<?php

    /**
     * @author Diego Martín
     * @copyright Hive®
     * @version 1.0
     * @lastUpdated 2025
     */

    class CApp extends Controller {

        // App services ------------------------------------------------
        
        public function root($args) {
            Utils::redirect('/home');
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
            $data['og']['og:url'] = URL_ROUTE.$route;
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
            $data['og']['og:url'] = URL_ROUTE.$route;
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
            if($args['_index'] == false) {
                $data['head']['robots'] = 'noindex, noimageindex, nofollow';
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
            if($args['_index'] == false) {
                $data['head']['robots'] = 'noindex, noimageindex, follow';
            }
            $this->view('/404', $data);
        }

        public function my_account($args) {
            $app = new App('my_account-page');
            $app->security_app_login();
            $data = $app->getAppData();
            if(LANG == 'en') {
                $data['meta']['title'] = $app->setTitle('My account');
                $data['meta']['description'] = 'Review and modify your user data.';
            } else if(LANG == 'es') {
                $data['meta']['title'] = $app->setTitle('Mi cuenta');
                $data['meta']['description'] = 'Revisa y modifica tu datos de usuario.';
            }
            $data['routes'] = ROUTES['my-account'];
            $this->view('/my-account', $data);
        }

        public function logout($args) {
            $app = new App();
            $app->security_app_login();
            $app->logout();
            Utils::redirect('/home', array(
                'logout' => 'true'
            ));
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
            $app->security_app_login();
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
            $app->refresh_cart_stock($_COOKIE['id_cart']);
            $data['cart'] = $app->get_checkout_cart($_COOKIE['id_cart']);
            if($data['cart'] == null) {
                header('Location: '.PUBLIC_ROUTE.'/');
                exit;
            }
            $data['javascript'] = json_encode(array(
                'shippingAddressErrorTitle' => 'Missing Data',
                'shippingAddressErrorText' => 'To continue with your order, you must fill in a shipping address.',
                'billingAddressErrorTitle' => 'Missing Data',
                'billingAddressErrorText' => 'To continue with your order, you must enter a billing address.',
                'shippingMethodErrorTitle' => 'Missing Data',
                'shippingMethodErrorText' => 'To continue with your order, you must select a shipping method.',
                'paymentMethodErrorTitle' => 'Missing Data',
                'paymentMethodErrorText' => 'To continue with your order, you must select a payment method.'
            ));
            $this->view('/checkout', $data);
        }

        public function save_checkout_successful($args) {
            // Intermediate step between Stripe's response and the payment information screen
            if(!isset($_GET['transaction_id'])) {
                header('Location: '.PUBLIC_ROUTE.'/checkout_failed');
                exit();
            }
            $app = new App('save-checkout-successful-page');
            if($app->check_transaction_id($_GET['transaction_id']) == true) {
                $app->save_order_from_cart($_COOKIE['id_cart']);
                header('Location: '.PUBLIC_ROUTE.'/checkout_successful');
                exit();
            } else {
                header('Location: '.PUBLIC_ROUTE.'/checkout_failed');
                exit();
            }
        }
        public function checkout_successful($args) {
            $app = new App('checkout-successful-page');
            $data = $app->getAppData();
            if(LANG == 'en') {
                $data['meta']['title'] = $app->setTitle('Checkout successful');
                $data['meta']['description'] = 'Your order has been placed successfully.';
            } else if(LANG == 'es') {
                $data['meta']['title'] = $app->setTitle('Pedido realizado correctamente');
                $data['meta']['description'] = 'Tu pedido se ha realizado correctamente.';
            }
            $data['routes'] = ROUTES['checkout-successful'];
            $this->view('/checkout-successful', $data);
        }

        public function checkout_failed($args) {
            $app = new App('checkout-failed-page');
            $data = $app->getAppData();
            if(LANG == 'en') {
                $data['meta']['title'] = $app->setTitle('Checkout failed');
                $data['meta']['description'] = 'An error occurred while placing your order.';
            } else if(LANG == 'es') {
                $data['meta']['title'] = $app->setTitle('Pedido fallido');
                $data['meta']['description'] = 'Se ha producido un error al realizar tu pedido.';
            }
            $data['routes'] = ROUTES['checkout-failed'];
            $this->view('/checkout-failed', $data);
        }

        public function checkout_bank_transfer($args) {
            $app = new App('checkout-bank-transfer-page');
            $data = $app->getAppData();
            if(LANG == 'en') {
                $data['meta']['title'] = $app->setTitle('Checkout bank transfer');
                $data['meta']['description'] = 'Order placed by bank transfer.';
            } else if(LANG == 'es') {
                $data['meta']['title'] = $app->setTitle('Pedido por transferencia bancaria');
                $data['meta']['description'] = 'Pedido realizado mediante transferencia bancaria.';
            }
            $data['routes'] = ROUTES['checkout-bank-transfer'];
            $this->view('/checkout-bank-transfer', $data);
        }

        public function checkout_cash_delivery($args) {
            $app = new App('checkout-cash-delivery-page');
            $data = $app->getAppData();
            if(LANG == 'en') {
                $data['meta']['title'] = $app->setTitle('Checkout cash delivery');
                $data['meta']['description'] = 'Order placed by cash delivery.';
            } else if(LANG == 'es') {
                $data['meta']['title'] = $app->setTitle('Pedido por contrareembolso');
                $data['meta']['description'] = 'Pedido realizado por contrareembolso.';
            }
            $data['routes'] = ROUTES['checkout-cash-delivery'];
            $this->view('/checkout-cash-delivery', $data);
        }

    }

?>