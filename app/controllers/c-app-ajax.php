<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2023
     */

    class CAppAjax extends Controller {

        // App ajax services ------------------------------------------------
        
        public function set_cookies($args) {
            $app = new App();
            $app->set_cookies();
            $result = [];
            $result['cookie'] = array(
                'response' => 'ok'
            );
            echo json_encode($result);
        }

        public function save_newsletter($args) {
            $app = new AppAjax();
            $result = [];
            $result['newsletter'] = $app->save_newsletter($_POST['email']);
            echo json_encode($result);
        }

        public function login_send($args) {
            $app = new App();
            $result = [];
            $result['login'] = $app->login($_POST['email'], md5($_POST['pass']), $_POST['remember']);
            echo json_encode($result);
        }

        public function register_send($args) {
            $app = new AppAjax();
            $result = [];
            $result['register'] = $app->register($_POST['email'], $_POST['name'], $_POST['lastname'], $_POST['pass1'], $_POST['newsletter']);
            echo json_encode($result);
        }

        public function choose_language($args) {
            $app = new AppAjax();
            $app->choose_language($_POST['language']);
            $result = [];
            $route_tail = str_replace(PUBLIC_ROUTE, '', $_POST['route']);
            $result['language'] = array(
                'response' => 'ok',
                'route' => PUBLIC_PATH.'/'.$_COOKIE['lang'].$route_tail,
                'language_route' => PUBLIC_PATH.'/'.$_COOKIE['lang'].$_POST['language_route']
            );
            echo json_encode($result);
        }

        public function choose_color_mode($args) {
            setcookie('color-mode', $_POST['mode'], time() + (24 * 60 * 60 * 365), PUBLIC_PATH.'/'); // 1 año
            $result = [];
            echo json_encode($result);
        }

        public function get_product_related($args) {
            $app = new AppAjax('ajax-get-product-related');
            $result = [];
            $result['get_product_related'] = $app->get_product_related();
            echo json_encode($result);
        }

        public function add_cart($args) {
            $app = new AppAjax('ajax-add-cart');
            $result = [];
            $result['add_cart'] = $app->add_cart();
            echo json_encode($result);
        }

        public function notify_stock($args) {
            $app = new AppAjax('ajax-notify-stock');
            $result = [];
            $result['notify_stock'] = $app->notify_stock();
            echo json_encode($result);
        }

        public function send_notify_stock($args) {
            $app = new AppAjax('ajax-send-notify-stock');
            $result = [];
            $result['send_notify_stock'] = $app->send_notify_stock();
            echo json_encode($result);
        }

        public function get_popup_cart($args) {
            $app = new AppAjax('ajax-get-popup-cart');
            $result = [];
            $result['get_popup_cart'] = $app->get_popup_cart();
            echo json_encode($result);
        }

        public function remove_cart_product($args) {
            $app = new AppAjax('ajax-remove-cart-product');
            $result = [];
            $result['remove_cart_product'] = $app->remove_cart_product();
            echo json_encode($result);
        }

        public function change_product_amount($args) {
            $app = new AppAjax('ajax-change-product-amount');
            $result = [];
            $result['change_product_amount'] = $app->change_product_amount();
            echo json_encode($result);
        }

        public function get_address($args) {
            $app = new AppAjax('ajax-get-address');
            $result = [];
            $result['get_address'] = $app->get_address();
            echo json_encode($result);
        }

        public function get_countries_list($args) {
            $app = new AppAjax('ajax-get-countries-list');
            $result = [];
            $result['get_countries_list'] = $app->get_countries_list($_POST['id_continent']);
            echo json_encode($result);
        }
        
        public function get_provinces_list($args) {
            $app = new AppAjax('ajax-get-provinces-list');
            $result = [];
            $result['get_provinces_list'] = $app->get_provinces_list($_POST['id_country']);
            echo json_encode($result);
        }

    }

?>