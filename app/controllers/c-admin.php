<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2023
     */

    class CAdmin extends Controller {

        // Admin services ------------------------------------------------        
    
        public function root($args) {
            header('Location: '.ADMIN_PATH.'/login');
            exit;
        }

        public function login($args) {
            $admin = new Admin('admin-login-page');
            $admin->security_admin_logout();
            $data = $admin->getAdminData();
            $data['meta']['title'] = $admin->setTitle('Login');
            $this->viewAdmin('/login', $data);
        }

        public function logout($args) {
            $admin = new Admin();
            $admin->security_admin_login();
            $admin->logout();
            header('Location: '.ADMIN_PATH.'/login?logout');
            exit;
        }

        public function home($args) {
            $admin = new Admin('admin-home-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['meta']['title'] = $admin->setTitle('Home');
            $data['admin']['tags'] = [
                'home'
            ];
            $this->viewAdmin('/home', $data);
        }

        public function products($args) {
            $admin = new Admin('admin-products-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'catalog',
                'products'
            ];
            $data['meta']['title'] = $admin->setTitle('Products');
            if(!isset($_GET['page'])) {
                $_GET['page'] = 1;
            }
            $data['products'] = $admin->get_products($_GET['page']);
            $this->viewAdmin('/products/products', $data);
        }

        public function products_custom_routes($args) {
            $admin = new Admin('admin-products-custom-routes-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'catalog',
                'products'
            ];
            $data['meta']['title'] = $admin->setTitle('Products Custom Routes');
            if(!isset($_GET['page'])) {
                $_GET['page'] = 1;
            }
            $data['routes'] = $admin->get_products_custom_routes($_GET['page']);
            $data['products_list'] = $admin->get_products_list();
            $data['languages'] = $admin->get_languages_array();
            $this->viewAdmin('/products/products-custom-routes', $data);
        }

        public function new_product($args) {
            $admin = new Admin('admin-new-product-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'catalog',
                'products'
            ];
            $data['meta']['title'] = $admin->setTitle('New Product');
            $data['product_views'] = $admin->get_product_views_list();
            $data['product_states'] = $admin->get_states_list();
            $data['categories'] = $admin->get_categories_custom_list();
            $data['attributes'] = $admin->get_attributes_list();
            $data['languages'] = $admin->get_languages_array();
            $this->viewAdmin('/products/new-product', $data);
        }

        public function edit_product($args) {
            if(!isset($_GET['id_product'])) {
                header('Location: '.ADMIN_PATH.'/products');
                exit;
            }
            $admin = new Admin('admin-edit-product-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'catalog',
                'products'
            ];
            $data['meta']['title'] = $admin->setTitle('Edit Product');
            $data['product'] = $admin->get_product($_GET['id_product']);
            if($data['product'] == 'error') {
                header('Location: '.ADMIN_PATH.'/products');
                exit;
            }
            $data['product_views'] = $admin->get_product_views_list($data['product']['id_product_view']);
            $data['product_states'] = $admin->get_states_list($data['product']['id_state']);
            $data['categories'] = $admin->get_categories_custom_list($_GET['id_product']);
            $data['attributes'] = $admin->get_attributes_list($_GET['id_product']);
            $data['attributes_product'] = $admin->get_attributes_list_product($_GET['id_product']);
            $data['languages'] = $admin->get_languages_product($_GET['id_product']);
            $this->viewAdmin('/products/edit-product', $data);
        }

        public function categories($args) {
            $admin = new Admin('admin-categories-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'catalog',
                'categories'
            ];
            $data['meta']['title'] = $admin->setTitle('Categories');
            if(!isset($_GET['page'])) {
                $_GET['page'] = 1;
            }
            if(!isset($_GET['id_parent'])) {
                $_GET['id_parent'] = null;
            }
            $data['categories'] = $admin->get_categories($_GET['id_parent'], $_GET['page']);
            $this->viewAdmin('/categories/categories', $data);
        }

        public function categories_custom_routes($args) {
            $admin = new Admin('admin-categories-custom-routes-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'catalog',
                'categories'
            ];
            $data['meta']['title'] = $admin->setTitle('Categories Custom Routes');
            if(!isset($_GET['page'])) {
                $_GET['page'] = 1;
            }
            $data['routes'] = $admin->get_categories_custom_routes($_GET['page']);
            $data['category_list'] = $admin->get_categories_list();
            $data['languages'] = $admin->get_languages_array();
            $this->viewAdmin('/categories/categories-custom-routes', $data);
        }

        public function new_category($args) {
            $admin = new Admin('admin-new-category-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'catalog',
                'categories'
            ];
            $data['meta']['title'] = $admin->setTitle('New Category');
            $data['categories'] = $admin->get_categories_list();
            $data['category_views'] = $admin->get_category_views_list();
            $data['category_states'] = $admin->get_states_list();
            $data['languages'] = $admin->get_languages_array();
            $this->viewAdmin('/categories/new-category', $data);
        }

        public function edit_category($args) {
            if(!isset($_GET['id_category'])) {
                header('Location: '.ADMIN_PATH.'/categories');
                exit;
            }
            $admin = new Admin('admin-edit-category-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'catalog',
                'categories'
            ];
            $data['meta']['title'] = $admin->setTitle('Edit Category');
            $data['category'] = $admin->get_category($_GET['id_category']);
            if($data['category'] == 'error') {
                header('Location: '.ADMIN_PATH.'/categories');
                exit;
            }            
            $data['categories'] = $admin->get_categories_list($data['category']['id_parent']);
            $data['category_views'] = $admin->get_category_views_list($data['category']['id_category_view']);
            $data['category_states'] = $admin->get_states_list($data['category']['id_state']);
            $data['languages'] = $admin->get_languages_category($_GET['id_category']);
            $this->viewAdmin('/categories/edit-category', $data);
        }

        public function attributes($args) {
            $admin = new Admin('admin-attributes-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'catalog',
                'attributes'
            ];
            $data['meta']['title'] = $admin->setTitle('Attributes');
            if(!isset($_GET['page'])) {
                $_GET['page'] = 1;
            }
            $data['attributes'] = $admin->get_attributes($_GET['page']);
            $this->viewAdmin('/attributes', $data);
        }

        public function new_attribute($args) {
            $admin = new Admin('admin-new-attribute-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'catalog',
                'attributes'
            ];
            $data['meta']['title'] = $admin->setTitle('New Attribute');
            $data['attributes_types'] = $admin->get_attribute_types_list();
            $data['attributes_html'] = $admin->get_attribute_html_list();
            $data['languages'] = $admin->get_languages_array();
            $this->viewAdmin('/new-attribute', $data);
        }

        public function edit_attribute($args) {
            if(!isset($_GET['id_attribute'])) {
                header('Location: '.ADMIN_PATH.'/attributes');
                exit;
            }
            $admin = new Admin('admin-edit-attribute-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'catalog',
                'attributes'
            ];
            $data['meta']['title'] = $admin->setTitle('Edit Attribute');
            $data['attribute'] = $admin->get_attribute($_GET['id_attribute']);
            if($data['attribute'] == 'error') {
                header('Location: '.ADMIN_PATH.'/attributes');
                exit;
            }            
            $data['attributes_types'] = $admin->get_attribute_types_list($data['attribute']['id_attribute_type']);
            $data['attributes_html'] = $admin->get_attribute_html_list($data['attribute']['id_attribute_html']);
            $data['languages'] = $admin->get_languages_attribute($_GET['id_attribute']);
            $this->viewAdmin('/edit-attribute', $data);
        }

        public function images($args) {
            $admin = new Admin('admin-images-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'catalog',
                'images'
            ];
            $data['meta']['title'] = $admin->setTitle('Images');
            if(!isset($_GET['page'])) {
                $_GET['page'] = 1;
            }
            $data['images'] = $admin->get_images_table($_GET['page']);
            $this->viewAdmin('/images', $data);
        }

        public function codes($args) {
            $admin = new Admin('admin-codes-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'orders-menu',
                'codes'
            ];
            $data['meta']['title'] = $admin->setTitle('Codes');
            if(!isset($_GET['page'])) {
                $_GET['page'] = 1;
            }
            $data['codes'] = $admin->get_codes($_GET['page']);
            $this->viewAdmin('/codes', $data);
        }

        public function new_code($args) {
            $admin = new Admin('admin-new-code-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'orders-menu',
                'codes'
            ];
            $data['meta']['title'] = $admin->setTitle('New Code');
            $data['code_states'] = $admin->get_states_list();
            $data['type'] = $admin->get_code_types_list();
            $this->viewAdmin('/new-code', $data);
        }

        public function edit_code($args) {
            if(!isset($_GET['id_code'])) {
                header('Location: '.ADMIN_PATH.'/codes');
                exit;
            }
            $admin = new Admin('admin-edit-code-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'orders-menu',
                'codes'
            ];
            $data['meta']['title'] = $admin->setTitle('Edit Code');
            $data['code'] = $admin->get_code($_GET['id_code']);
            if($data['code'] == 'error') {
                header('Location: '.ADMIN_PATH.'/codes');
                exit;
            }
            $data['code_states'] = $admin->get_states_list($data['code']['id_state']);
            $data['type'] = $admin->get_code_types_list($data['code']['type']);
            $data['codes_rules_type'] = $admin->get_codes_rules_type_list();
            $data['codes_rules_add_type'] = $admin->get_codes_rules_add_type_list();
            $this->viewAdmin('/edit-code', $data);
        }

        public function stats($args) {
            $admin = new Admin('admin-stats-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'stats-menu',
                'stats'
            ];
            $data['meta']['title'] = $admin->setTitle('Stats');
            $data['stats'] = $admin->get_stats();
            $this->viewAdmin('/stats', $data);
        }

        public function languages($args) {
            $admin = new Admin('admin-languages-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'settings',
                'languages'
            ];
            $data['meta']['title'] = $admin->setTitle('Languages');
            if(!isset($_GET['page'])) {
                $_GET['page'] = 1;
            }
            $data['languages'] = $admin->get_languages($_GET['page']);
            $this->viewAdmin('/languages', $data);
        }

        public function edit_language($args) {
            $admin = new Admin('admin-language-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'settings',
                'languages'
            ];
            $data['meta']['title'] = $admin->setTitle('Language');
            $data['language'] = $admin->get_language($_GET['id_language']);
            $this->viewAdmin('/edit-language', $data);
        }

        public function users($args) {
            $admin = new Admin('admin-users-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'users'
            ];
            $data['meta']['title'] = $admin->setTitle('Users');
            if(!isset($_GET['page'])) {
                $_GET['page'] = 1;
            }
            $data['users'] = $admin->get_users($_GET['page']);
            $this->viewAdmin('/users', $data);
        }

        public function edit_user($args) {
            if(!isset($_GET['id_user'])) {
                header('Location: '.ADMIN_PATH.'/users');
                exit;
            }
            $admin = new Admin('admin-edit-user-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'users'
            ];
            $data['meta']['title'] = $admin->setTitle('Edit User');
            $data['user'] = $admin->get_user($_GET['id_user']);
            if($data['user'] == 'error') {
                header('Location: '.ADMIN_PATH.'/users');
                exit;
            }
            $data['continents'] = $admin->get_continents_active_options();
            $data['user_states'] = $admin->get_states_list($data['user']['id_state']);
            $this->viewAdmin('/edit-user', $data);
        }

        public function users_admin($args) {
            $admin = new Admin('admin-users-admin-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'settings',
                'users-admin'
            ];
            $data['meta']['title'] = $admin->setTitle('Users Admin');
            if(!isset($_GET['page'])) {
                $_GET['page'] = 1;
            }
            $data['users_admin'] = $admin->get_users_admin($_GET['page']);
            $this->viewAdmin('/users-admin', $data);
        }

        public function new_admin_user($args) {
            $admin = new Admin('admin-new-admin-user-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'settings',
                'users-admin'
            ];
            $data['meta']['title'] = $admin->setTitle('New Admin User');
            $data['admin_type'] = $admin->get_admin_type_list();
            $this->viewAdmin('/new-admin-user', $data);
        }

        public function edit_user_admin($args) {
            if(!isset($_GET['id_admin'])) {
                header('Location: '.ADMIN_PATH.'/users-admin');
                exit;
            }
            $admin = new Admin('admin-edit-user-admin-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'settings',
                'users-admin'
            ];
            $data['meta']['title'] = $admin->setTitle('Edit User Admin');
            $data['admin_user'] = $admin->get_admin_user($_GET['id_admin']);
            $data['admin_type'] = $admin->get_admin_type_list($data['admin_user']['id_admin_type']);
            $data['user_states'] = $admin->get_states_list($data['admin_user']['id_state']);
            $this->viewAdmin('/edit-user-admin', $data);
        }

        public function carts($args) {
            $admin = new Admin('admin-carts-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'orders-menu',
                'carts'
            ];
            $data['meta']['title'] = $admin->setTitle('Carts');
            if(!isset($_GET['page'])) {
                $_GET['page'] = 1;
            }
            $data['carts'] = $admin->carts($_GET['page']);
            $this->viewAdmin('/carts', $data);
        }

        public function view_cart($args) {
            $admin = new Admin('admin-view-cart-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'orders-menu',
                'carts'
            ];
            $data['meta']['title'] = $admin->setTitle('View Cart');
            $data['carts'] = $admin->get_cart($_GET['id_cart']);
            $this->viewAdmin('/view-cart', $data);
        }

        public function orders($args) {
            $admin = new Admin('admin-orders-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'orders-menu',
                'orders'
            ];
            $data['meta']['title'] = $admin->setTitle('Orders');
            $data['orders'] = $admin->get_orders();
            $this->viewAdmin('/orders', $data);
        }

        public function order($args) {
            if(!isset($_GET['id_order'])) {
                header('Location: '.ADMIN_PATH.'/orders');
                exit;
            }
            $admin = new Admin('admin-order-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'orders-menu',
                'orders'
            ];
            $data['meta']['title'] = $admin->setTitle('Order');
            $data['order'] = $admin->get_order($_GET['id_order']);
            if($data['order'] == 'error') {
                header('Location: '.ADMIN_PATH.'/orders');
                exit;
            }
            $this->viewAdmin('/order', $data);
        }

        public function shipments($args) {
            $admin = new Admin('admin-shipments-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'shipments',
                'shipments-methods'
            ];
            $data['meta']['title'] = $admin->setTitle('Shipments');
            if(!isset($_GET['page'])) {
                $_GET['page'] = 1;
            }
            $data['shipments'] = $admin->get_shipping_methods($_GET['page']);
            $this->viewAdmin('/shipments/shipments', $data);
        }

        public function new_shipping_method($args) {
            $admin = new Admin('admin-new-shipping-method-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'shipments',
                'shipments-methods'
            ];
            $data['meta']['title'] = $admin->setTitle('New shipping method');
            $data['languages'] = $admin->get_languages_array();
            $data['product_states'] = $admin->get_states_list();
            $this->viewAdmin('/shipments/new-shipping-method', $data);
        }

        public function edit_shipping_method($args) {
            if(!isset($_GET['id_shipping_method'])) {
                header('Location: '.ADMIN_PATH.'/shipments');
                exit;
            }
            $admin = new Admin('admin-edit-shipping-method-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'shipments',
                'shipments-methods'
            ];
            $data['meta']['title'] = $admin->setTitle('Edit shipping method');
            $data['shipping_method'] = $admin->get_shipping_method($_GET['id_shipping_method']);
            if($data['shipping_method'] == 'error') {
                header('Location: '.ADMIN_PATH.'/shipments');
                exit;
            }
            $data['languages'] = $admin->get_languages_shipment($_GET['id_shipping_method']);
            $data['product_states'] = $admin->get_states_list($data['shipping_method']['id_state']);
            $this->viewAdmin('/shipments/edit-shipping-method', $data);
        }

        public function shipping_zones($args) {
            $admin = new Admin('admin-shipping-zones-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'shipments',
                'shipping-zones'
            ];
            $data['meta']['title'] = $admin->setTitle('Shipping zones');
            if(!isset($_GET['page'])) {
                $_GET['page'] = 1;
            }
            $data['shipping_zones'] = $admin->get_shipping_zones($_GET['page']);
            $this->viewAdmin('/shipments/shipping-zones', $data);
        }

        public function new_shipping_zone($args) {
            $admin = new Admin('admin-new-shipping-zone-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'shipments',
                'shipping-zones'
            ];
            $data['meta']['title'] = $admin->setTitle('New shipping zone');
            $data['product_states'] = $admin->get_states_list();
            $this->viewAdmin('/shipments/new-shipping-zone', $data);
        }

        public function edit_shipping_zone($args) {
            if(!isset($_GET['id_shipping_zone'])) {
                header('Location: '.ADMIN_PATH.'/shipping-zones');
                exit;
            }
            $admin = new Admin('admin-edit-shipping-zone-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'shipments',
                'shipping-zones'
            ];
            $data['meta']['title'] = $admin->setTitle('Edit shipping zone');
            $data['shipping_zone'] = $admin->get_shipping_zone($_GET['id_shipping_zone']);
            if($data['shipping_zone'] == 'error') {
                header('Location: '.ADMIN_PATH.'/shipping-zones');
                exit;
            }
            $data['continents'] = $admin->get_shipping_zone_continents($_GET['id_shipping_zone']);
            $data['continents_select'] = $admin->get_continents_active_options();
            $data['countries_select'] = $admin->get_countries_active_options();
            $data['product_states'] = $admin->get_states_list($data['shipping_zone']['id_state']);
            $this->viewAdmin('/shipments/edit-shipping-zone', $data);
        }

        public function payments($args) {
            $admin = new Admin('admin-payments-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'payments-menu',
                'payments'
            ];
            $data['meta']['title'] = $admin->setTitle('Payments');
            if(!isset($_GET['page'])) {
                $_GET['page'] = 1;
            }
            $data['payments'] = $admin->get_payment_methods($_GET['page']);
            $this->viewAdmin('/payments/payments', $data);
        }

        public function new_payment_method($args) {
            $admin = new Admin('admin-new-payment-method-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'payments-menu',
                'payments'
            ];
            $data['meta']['title'] = $admin->setTitle('New payment method');
            $data['languages'] = $admin->get_languages_array();
            $data['product_states'] = $admin->get_states_list();
            $this->viewAdmin('/payments/new-payment-method', $data);
        }

        public function edit_payment_method($args) {
            if(!isset($_GET['id_payment_method'])) {
                header('Location: '.ADMIN_PATH.'/payments');
                exit;
            }
            $admin = new Admin('admin-edit-payment-method-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'payments-menu',
                'payments'
            ];
            $data['meta']['title'] = $admin->setTitle('Edit payment method');
            $data['payment_method'] = $admin->get_payment_method($_GET['id_payment_method']);
            if($data['payment_method'] == 'error') {
                header('Location: '.ADMIN_PATH.'/payments');
                exit;
            }
            $data['languages'] = $admin->get_languages_payment($_GET['id_payment_method']);
            $data['product_states'] = $admin->get_states_list($data['payment_method']['id_state']);
            $this->viewAdmin('/payments/edit-payment-method', $data);
        }

        public function payment_zones($args) {
            $admin = new Admin('admin-payment-zones-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'payments-menu',
                'payment-zones'
            ];
            $data['meta']['title'] = $admin->setTitle('Payment zones');
            if(!isset($_GET['page'])) {
                $_GET['page'] = 1;
            }
            $data['payments_zones'] = $admin->get_payment_zones($_GET['page']);
            $this->viewAdmin('/payments/payment-zones', $data);
        }

        public function new_payment_zone($args) {
            $admin = new Admin('admin-new-payment-zone-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'payments-menu',
                'payment-zones'
            ];
            $data['meta']['title'] = $admin->setTitle('New payment zone');
            $data['product_states'] = $admin->get_states_list();
            $this->viewAdmin('/payments/new-payment-zone', $data);
        }

        public function edit_payment_zone($args) {
            if(!isset($_GET['id_payment_zone'])) {
                header('Location: '.ADMIN_PATH.'/payments');
                exit;
            }
            $admin = new Admin('admin-edit-payment-zone-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'payments-menu',
                'payment-zones'
            ];
            $data['meta']['title'] = $admin->setTitle('Edit payment zone');
            $data['payment_zone'] = $admin->get_payment_zone($_GET['id_payment_zone']);
            if($data['payment_zone'] == 'error') {
                header('Location: '.ADMIN_PATH.'/payments');
                exit;
            }
            $data['product_states'] = $admin->get_states_list($data['payment_zone']['id_state']);
            $this->viewAdmin('/payments/edit-payment-zone', $data);
        }

        public function taxes($args) {
            $admin = new Admin('admin-taxes-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'taxes-menu',
                'taxes'
            ];
            $data['meta']['title'] = $admin->setTitle('Taxes');
            $this->viewAdmin('/taxes', $data);
        }

        public function locations($args) {
            $admin = new Admin('admin-locations-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['admin']['tags'] = [
                'settings',
                'locations'
            ];
            $data['meta']['title'] = $admin->setTitle('Locations');
            $this->viewAdmin('/locations', $data);
        }

        public function ftp_upload($args) {
            $admin = new Admin('ftp-upload-page');
            $admin->security_admin_login();
            $upload = new FtpUpload();
            if($upload->connect()) {
                if($upload->login()) {
                    $data = $admin->getAdminData();
                    $data['admin']['tags'] = [
                        'settings',
                        'ftp-upload'
                    ];
                    $data['meta']['title'] = $admin->setTitle('FTP Upload');
                    $this->viewAdmin('/ftp-upload-view', $data);
                } else {
                    Utils::error('The Ftp Upload user or password is not correct.');
                }    
            } else {
                Utils::error('Ftp Upload could not connect to server.');
            }
        }
        
    }

?>