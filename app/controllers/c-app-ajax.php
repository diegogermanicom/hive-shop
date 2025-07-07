<?php

    /**
     * @author Diego Martín
     * @copyright Hive®
     * @version 1.0
     * @lastUpdated 2025
     */

    class CAppAjax extends Controller {

        // App ajax services ------------------------------------------------
        
        public function set_cookies($args) {
            $app = new AppAjax();
            $result = [];
            $result['cookie'] = $app->set_cookies();
            echo json_encode($result);
        }

        public function save_newsletter($args) {
            $app = new AppAjax();
            $result = [];
            $result['newsletter'] = $app->save_newsletter($_POST['email']);
            echo json_encode($result);
        }

        public function login_send($args) {
            $app = new AppAjax();
            $app->security_app_logout();
            $result = [];
            $result['login'] = $app->login($_POST['email'], md5($_POST['pass']), $_POST['remember']);
            echo json_encode($result);
        }

        public function register_send($args) {
            $app = new AppAjax();
            $app->security_app_logout();
            $result = [];
            $result['register'] = $app->register($_POST['email'], $_POST['name'], $_POST['lastname'], $_POST['pass1'], $_POST['newsletter']);
            echo json_encode($result);
        }

        public function choose_language($args) {
            $app = new AppAjax();
            $result = [];
            $result['language'] = $app->choose_language($_POST['language']);
            echo json_encode($result);
        }

        public function choose_color_mode($args) {
            Utils::setThemeColor($_POST['mode']);
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
            $app->refresh_cart_stock($_COOKIE['id_cart']);
            $result['get_popup_cart'] = $app->get_popup_cart($_COOKIE['id_cart']);
            echo json_encode($result);
        }

        public function remove_cart_product($args) {
            $app = new AppAjax('ajax-remove-cart-product');
            $result = [];
            $result['remove_cart_product'] = $app->remove_cart_product($_COOKIE['id_cart'], $_POST['id']);
            echo json_encode($result);
        }

        public function change_product_amount($args) {
            $app = new AppAjax('ajax-change-product-amount');
            $result = [];
            $result['change_product_amount'] = $app->change_product_amount();
            echo json_encode($result);
        }

        public function get_addresses($args) {
            $app = new AppAjax('ajax-get-addresses');
            $result = [];
            $result['get_addresses'] = $app->get_addresses();
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

        public function save_new_address($args) {
            $app = new AppAjax('ajax-save-new-address');
            $result = [];
            $result['save_new_address'] = $app->save_new_address();
            echo json_encode($result);
        }

        public function get_address($args) {
            $app = new AppAjax('ajax-get-address');
            $result = [];
            $result['get_address'] = $app->get_address($_POST['id_user_address']);
            echo json_encode($result);
        }

        public function delete_address($args) {
            $app = new AppAjax('ajax-delete-address');
            $result = [];
            $result['delete_address'] = $app->delete_address($_POST['id_user_address']);
            echo json_encode($result);
        }

        public function save_edit_address($args) {
            $app = new AppAjax('ajax-save-edit-address');
            $result = [];
            $result['save_edit_address'] = $app->save_edit_address();
            echo json_encode($result);
        }

        public function get_billing_addresses($args) {
            $app = new AppAjax('ajax-get-billing-addresses');
            $result = [];
            $result['get_billing_addresses'] = $app->get_billing_addresses();
            echo json_encode($result);
        }

        public function save_new_billing_address($args) {
            $app = new AppAjax('ajax-save-new-billing-address');
            $result = [];
            $result['save_new_billing_address'] = $app->save_new_billing_address();
            echo json_encode($result);
        }

        public function get_billing_address($args) {
            $app = new AppAjax('ajax-get-billing-address');
            $result = [];
            $result['get_billing_address'] = $app->get_billing_address($_POST['id_user_billing_address']);
            echo json_encode($result);
        }

        public function delete_billing_address($args) {
            $app = new AppAjax('ajax-delete-billing-address');
            $result = [];
            $result['delete_billing_address'] = $app->delete_billing_address($_POST['id_user_billing_address']);
            echo json_encode($result);
        }

        public function save_edit_billing_address($args) {
            $app = new AppAjax('ajax-save-edit-billing-address');
            $result = [];
            $result['save_edit_billing_address'] = $app->save_edit_billing_address();
            echo json_encode($result);
        }
        
        public function get_shipping_methods($args) {
            $app = new AppAjax('ajax-get-shipping-methods');
            $result = [];
            $result['get_shipping_methods'] = $app->get_shipping_methods($_COOKIE['id_cart']);
            echo json_encode($result);
        }

        public function get_payment_methods($args) {
            $app = new AppAjax('ajax-get-payment-methods');
            $result = [];
            $result['get_payment_methods'] = $app->get_payment_methods($_COOKIE['id_cart']);
            echo json_encode($result);
        }

        public function apply_code($args) {
            $app = new AppAjax('ajax-apply-code');
            $result = [];
            $result['apply_code'] = $app->apply_code($_POST['code'], $_COOKIE['id_cart']);
            echo json_encode($result);
        }

        public function save_order_to_cart($args) {
            $app = new AppAjax('ajax-save-order-to-cart');
            $result = [];
            $result['save_order_to_cart'] = $app->save_order_to_cart();
            echo json_encode($result);
        }

    }

?>