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
            $this->viewAdmin('/home', $data);
        }

        public function products($args) {
            $admin = new Admin('admin-products-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['meta']['title'] = $admin->setTitle('Products');
            if(!isset($_GET['page'])) {
                $_GET['page'] = 1;
            }
            $data['products'] = $admin->get_products($_GET['page']);
            $this->viewAdmin('/products', $data);
        }

        public function products_custom_routes($args) {
            $admin = new Admin('admin-products-custom-routes-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['meta']['title'] = $admin->setTitle('Products Custom Routes');
            if(!isset($_GET['page'])) {
                $_GET['page'] = 1;
            }
            $data['routes'] = $admin->get_products_custom_routes($_GET['page']);
            $data['products_list'] = $admin->get_products_list();
            $data['languages'] = $admin->get_languages_array();
            $this->viewAdmin('/products-custom-routes', $data);
        }

        public function new_product($args) {
            $admin = new Admin('admin-new-product-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['meta']['title'] = $admin->setTitle('New Product');
            $data['product_views'] = $admin->get_product_views_list();
            $data['product_states'] = $admin->get_states_list();
            $data['categories'] = $admin->get_categories_custom_list();
            $data['attributes'] = $admin->get_attributes_list();
            $data['languages'] = $admin->get_languages_array();
            $this->viewAdmin('/new-product', $data);
        }

        public function edit_product($args) {
            if(!isset($_GET['id_product'])) {
                header('Location: '.ADMIN_PATH.'/products');
                exit;
            }
            $admin = new Admin('admin-edit-product-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
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
            $this->viewAdmin('/edit-product', $data);
        }

        public function categories($args) {
            $admin = new Admin('admin-categories-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['meta']['title'] = $admin->setTitle('Categories');
            if(!isset($_GET['page'])) {
                $_GET['page'] = 1;
            }
            if(!isset($_GET['id_parent'])) {
                $_GET['id_parent'] = null;
            }
            $data['categories'] = $admin->get_categories($_GET['id_parent'], $_GET['page']);
            $this->viewAdmin('/categories', $data);
        }

        public function categories_custom_routes($args) {
            $admin = new Admin('admin-categories-custom-routes-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['meta']['title'] = $admin->setTitle('Categories Custom Routes');
            if(!isset($_GET['page'])) {
                $_GET['page'] = 1;
            }
            $data['routes'] = $admin->get_categories_custom_routes($_GET['page']);
            $data['category_list'] = $admin->get_categories_list();
            $data['languages'] = $admin->get_languages_array();
            $this->viewAdmin('/categories-custom-routes', $data);
        }

        public function new_category($args) {
            $admin = new Admin('admin-new-category-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['meta']['title'] = $admin->setTitle('New Category');
            $data['categories'] = $admin->get_categories_list();
            $data['category_views'] = $admin->get_category_views_list();
            $data['category_states'] = $admin->get_states_list();
            $data['languages'] = $admin->get_languages_array();
            $this->viewAdmin('/new-category', $data);
        }

        public function edit_category($args) {
            if(!isset($_GET['id_category'])) {
                header('Location: '.ADMIN_PATH.'/categories');
                exit;
            }
            $admin = new Admin('admin-edit-category-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
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
            $this->viewAdmin('/edit-category', $data);
        }

        public function attributes($args) {
            $admin = new Admin('admin-attributes-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
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
            $data['meta']['title'] = $admin->setTitle('Edit Code');
            $data['code'] = $admin->get_code($_GET['id_code']);
            if($data['code'] == 'error') {
                header('Location: '.ADMIN_PATH.'/codes');
                exit;
            }            
            $data['code_states'] = $admin->get_states_list($data['code']['id_state']);
            $data['type'] = $admin->get_code_types_list($data['code']['type']);
            $this->viewAdmin('/edit-code', $data);
        }

        public function stats($args) {
            $admin = new Admin('admin-stats-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['meta']['title'] = $admin->setTitle('Stats');
            $data['stats'] = $admin->get_stats();
            $this->viewAdmin('/stats', $data);
        }

        public function languages($args) {
            $admin = new Admin('admin-languages-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
            $data['meta']['title'] = $admin->setTitle('Languages');
            if(!isset($_GET['page'])) {
                $_GET['page'] = 1;
            }
            $data['languages'] = $admin->get_languages($_GET['page']);
            $this->viewAdmin('/languages', $data);
        }

        public function users($args) {
            $admin = new Admin('admin-users-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
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
            $data['meta']['title'] = $admin->setTitle('Edit User');
            $data['user'] = $admin->get_user($_GET['id_user']);
            if($data['user'] == 'error') {
                header('Location: '.ADMIN_PATH.'/users');
                exit;
            }
            $data['continents'] = $admin->get_continents_active_list();
            $data['user_states'] = $admin->get_states_list($data['user']['id_state']);
            $this->viewAdmin('/edit-user', $data);
        }

        public function users_admin($args) {
            $admin = new Admin('admin-users-admin-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
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
            $data['meta']['title'] = $admin->setTitle('View Cart');
            $data['carts'] = $admin->get_cart($_GET['id_cart']);
            $this->viewAdmin('/view-cart', $data);
        }

        public function orders($args) {
            $admin = new Admin('admin-orders-page');
            $admin->security_admin_login();
            $data = $admin->getAdminData();
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
            $data['meta']['title'] = $admin->setTitle('Order');
            $data['order'] = $admin->get_order($_GET['id_order']);
            if($data['order'] == 'error') {
                header('Location: '.ADMIN_PATH.'/orders');
                exit;
            }
            $this->viewAdmin('/order', $data);
        }

        public function ftp_upload($args) {
            $admin = new Admin('ftp-upload-page');
            $admin->security_admin_login();
            $upload = new FtpUpload();
            if($upload->connect()) {
                if($upload->login()) {
                    $data = $admin->getAdminData();
                    $data['meta']['title'] = $admin->setTitle('FTP Upload');
                    $this->viewAdmin('/ftp-upload-view', $data);
                } else {
                    new Err(
                        'The username or password is not correct.',
                        'Check that the <b>$user</b> and <b>$pass</b> variables of the ftp-upload model are correct.'
                    );
                }    
            } else {
                new Err(
                    'Could not connect to server <b>'.$upload->host.'</b>.',
                    'Check that the <b>$host</b> variable of the ftp-upload model is correct.'
                );
            }
        }
        
    }

?>