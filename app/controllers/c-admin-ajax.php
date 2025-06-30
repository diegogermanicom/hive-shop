<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2023
     */

    class CAdminAjax extends Controller {
        
        // App ajax services ------------------------------------------------

        public function send_login($args) {
            $admin = new AdminAjax('ajax-admin-login');
            $admin->security_admin_logout();
            $result = [];
            $result['login'] = $admin->login($_POST['email'], md5($_POST['pass']), $_POST['remember']);
            echo json_encode($result);
        }

        public function create_new_sitemap() {
            $admin = new AdminAjax('ajax-sitemap');
            $admin->security_admin_login();
            $result = [];
            $result['sitemap'] = $admin->create_new_sitemap();
            echo json_encode($result);
        }

        public function save_new_product() {
            $admin = new AdminProductAjax('ajax-save-new-product');
            $admin->security_admin_login();
            $result = [];
            $result['save_new_product'] = $admin->save_new_product();
            echo json_encode($result);
        }

        public function get_product_images() {
            $admin = new AdminProductAjax('ajax-get-product-images');
            $admin->security_admin_login();
            $result = [];
            $result['get_product_images'] = $admin->get_product_images($_POST['id_product']);
            echo json_encode($result);
        }

        public function save_product_main_image() {
            $admin = new AdminProductAjax('ajax-save-product-main-image');
            $admin->security_admin_login();
            $result = [];
            $result['save_product_main_image'] = $admin->save_product_main_image($_POST['id_product'], $_POST['id_product_image']);
            echo json_encode($result);
        }

        public function save_product_hover_image() {
            $admin = new AdminProductAjax('ajax-save-product-hover-image');
            $admin->security_admin_login();
            $result = [];
            $result['save_product_hover_image'] = $admin->save_product_hover_image($_POST['id_product'], $_POST['id_product_image']);
            echo json_encode($result);
        }

        public function delete_product_image() {
            $admin = new AdminProductAjax('ajax-delete-product-image');
            $admin->security_admin_login();
            $result = [];
            $result['delete_product_image'] = $admin->delete_product_image($_POST['id_product_image'], $_POST['id_product']);
            echo json_encode($result);
        }
        
        public function delete_product_server_image() {
            $admin = new AdminProductAjax('ajax-delete-server-image');
            $admin->security_admin_login();
            $result = [];
            $result['delete_product_server_image'] = $admin->delete_product_server_image($_POST['id_image']);
            echo json_encode($result);
        }

        public function save_edit_product() {
            $admin = new AdminProductAjax('ajax-save-edit-product');
            $admin->security_admin_login();
            $result = [];
            $result['save_edit_product'] = $admin->save_edit_product();
            echo json_encode($result);
        }

        public function get_add_images() {
            $admin = new AdminProductAjax('ajax-get-images');
            $admin->security_admin_login();
            $result = [];
            $result['images'] = $admin->get_add_images($_POST['id_product']);
            echo json_encode($result);
        }
        
        public function get_related() {
            $admin = new AdminProductAjax('ajax-get-related');
            $admin->security_admin_login();
            $result = [];
            $result['get_related'] = $admin->get_related($_POST['id_product']);
            echo json_encode($result);
        }

        public function get_add_related() {
            $admin = new AdminProductAjax('ajax-get-add-related');
            $admin->security_admin_login();
            $result = [];
            $result['get_add_related'] = $admin->get_add_related($_POST['id_product']);
            echo json_encode($result);
        }

        public function add_related() {
            $admin = new AdminProductAjax('ajax-add-related');
            $admin->security_admin_login();
            $result = [];
            $result['add_related'] = $admin->add_related();
            echo json_encode($result);
        }

        public function get_edit_related() {
            $admin = new AdminProductAjax('ajax-get-edit-related');
            $admin->security_admin_login();
            $result = [];
            $result['get_edit_related'] = $admin->get_edit_related($_POST['id_product_related']);
            echo json_encode($result);
        }

        public function save_related() {
            $admin = new AdminProductAjax('ajax-save-related');
            $admin->security_admin_login();
            $result = [];
            $result['save_related'] = $admin->save_related();
            echo json_encode($result);
        }

        public function delete_related() {
            $admin = new AdminProductAjax('ajax-delete-related');
            $admin->security_admin_login();
            $result = [];
            $result['delete_related'] = $admin->delete_related($_POST['id_product_related']);
            echo json_encode($result);
        }

        public function delete_product() {
            $admin = new AdminProductAjax('ajax-delete-product');
            $admin->security_admin_login();
            $result = [];
            $result['delete_product'] = $admin->delete_product($_POST['id_product']);
            echo json_encode($result);
        }

        public function get_product_categories_list() {
            $admin = new AdminProductAjax('ajax-get-product-categories-list');
            $admin->security_admin_login();
            $result = [];
            $result['get_product_categories_list'] = $admin->get_product_categories_list($_POST['id_product']);
            echo json_encode($result);
        }

        public function save_new_product_custom_route() {
            $admin = new AdminProductAjax('ajax-save-new-product-custom-route');
            $admin->security_admin_login();
            $result = [];
            $result['save_new_product_custom_route'] = $admin->save_new_product_custom_route();
            echo json_encode($result);
        }

        public function delete_product_custom_route() {
            $admin = new AdminProductAjax('ajax-delete-product-custom-route');
            $admin->security_admin_login();
            $result = [];
            $result['delete_product_custom_route'] = $admin->delete_product_custom_route($_POST['id_product_custom_route']);
            echo json_encode($result);
        }

        public function save_new_category() {
            $admin = new AdminAjax('ajax-save-new-category');
            $admin->security_admin_login();
            $result = [];
            $result['save_new_category'] = $admin->save_new_category();
            echo json_encode($result);
        }

        public function save_edit_category() {
            $admin = new AdminAjax('ajax-save-edit-category');
            $admin->security_admin_login();
            $result = [];
            $result['save_edit_category'] = $admin->save_edit_category();
            echo json_encode($result);
        }

        public function delete_category() {
            $admin = new AdminAjax('ajax-delete-category');
            $admin->security_admin_login();
            $result = [];
            $result['delete_category'] = $admin->delete_category($_POST['id_category']);
            echo json_encode($result);
        }

        public function delete_category_custom_route() {
            $admin = new AdminAjax('ajax-delete-category-custom-route');
            $admin->security_admin_login();
            $result = [];
            $result['delete_category_custom_route'] = $admin->delete_category_custom_route($_POST['id_category_custom_route']);
            echo json_encode($result);
        }

        public function save_new_category_custom_route() {
            $admin = new AdminAjax('ajax-save-new-category-custom-route');
            $admin->security_admin_login();
            $result = [];
            $result['save_new_category_custom_route'] = $admin->save_new_category_custom_route();
            echo json_encode($result);
        }

        public function save_new_attribute() {
            $admin = new AdminAjax('ajax-save-new-attribute');
            $admin->security_admin_login();
            $result = [];
            $result['save_new_attribute'] = $admin->save_new_attribute();
            echo json_encode($result);
        }

        public function save_edit_attribute() {
            $admin = new AdminAjax('ajax-save-edit-attribute');
            $admin->security_admin_login();
            $result = [];
            $result['save_edit_attribute'] = $admin->save_edit_attribute();
            echo json_encode($result);
        }

        public function get_attribute_values() {
            $admin = new AdminAjax('ajax-get-attribute-values');
            $admin->security_admin_login();
            $result = [];
            $result['get_attribute_values'] = $admin->get_attribute_values($_POST['id_attribute'], $_POST['type']);
            echo json_encode($result);
        }

        public function get_attribute_value_properties() {
            $admin = new AdminAjax('ajax-get-attribute-value-properties');
            $admin->security_admin_login();
            $result = [];
            $result['get_attribute_value_properties'] = $admin->get_attribute_value_properties($_POST['id_attribute_value']);
            echo json_encode($result);
        }

        public function save_attribute_value_properties() {
            $admin = new AdminAjax('ajax-save-attribute-value-properties');
            $admin->security_admin_login();
            $result = [];
            $result['save_attribute_value_properties'] = $admin->save_attribute_value_properties();
            echo json_encode($result);
        }

        public function delete_attribute() {
            $admin = new AdminAjax('ajax-delete-attribute');
            $admin->security_admin_login();
            $result = [];
            $result['delete_attribute'] = $admin->delete_attribute($_POST['id_attribute']);
            echo json_encode($result);
        }

        public function save_new_code() {
            $admin = new AdminAjax('ajax-save-new-code');
            $admin->security_admin_login();
            $result = [];
            $result['save_new_code'] = $admin->save_new_code();
            echo json_encode($result);
        }
        
        public function save_edit_code() {
            $admin = new AdminAjax('ajax-save-edit-code');
            $admin->security_admin_login();
            $result = [];
            $result['save_edit_code'] = $admin->save_edit_code();
            echo json_encode($result);
        }

        public function delete_code() {
            $admin = new AdminAjax('ajax-delete-code');
            $admin->security_admin_login();
            $result = [];
            $result['delete_code'] = $admin->delete_code($_POST['id_code']);
            echo json_encode($result);
        }

        public function add_code_rule() {
            $admin = new AdminAjax('ajax-add-code-rule');
            $admin->security_admin_login();
            $result = [];
            $result['add_code_rule'] = $admin->add_code_rule();
            echo json_encode($result);
        }

        public function save_code_rule() {
            $admin = new AdminAjax('ajax-save-code-rule');
            $admin->security_admin_login();
            $result = [];
            $result['save_code_rule'] = $admin->save_code_rule();
            echo json_encode($result);
        }

        public function delete_code_rule() {
            $admin = new AdminAjax('ajax-delete-code-rule');
            $admin->security_admin_login();
            $result = [];
            $result['delete_code_rule'] = $admin->delete_code_rule($_POST['id_code_rule']);
            echo json_encode($result);
        }

        public function get_code_rule() {
            $admin = new AdminAjax('ajax-get-code-rule');
            $admin->security_admin_login();
            $result = [];
            $result['get_code_rule'] = $admin->get_code_rule($_POST['id_code_rule']);
            echo json_encode($result);
        }

        public function get_code_rules() {
            $admin = new AdminAjax('ajax-get-code-rules');
            $admin->security_admin_login();
            $result = [];
            $result['get_code_rules'] = $admin->get_code_rules($_POST['id_code']);
            echo json_encode($result);
        }

        public function get_code_rule_elements_list() {
            $admin = new AdminAjax('ajax-get-code-rule-elements-list');
            $admin->security_admin_login();
            $result = [];
            $result['get_code_rule_elements_list'] = $admin->get_code_rule_elements_list($_POST['id_code_rule_type']);
            echo json_encode($result);
        }

        public function save_new_shipment() {
            $admin = new AdminShippingAjax('ajax-save-new-shipment');
            $admin->security_admin_login();
            $result = [];
            $result['save_new_shipment'] = $admin->save_new_shipment();
            echo json_encode($result);
        }

        public function save_edit_shipment() {
            $admin = new AdminShippingAjax('ajax-save-edit-shipment');
            $admin->security_admin_login();
            $result = [];
            $result['save_edit_shipment'] = $admin->save_edit_shipment();
            echo json_encode($result);
        }

        public function delete_shipment() {
            $admin = new AdminShippingAjax('ajax-delete-shipment');
            $admin->security_admin_login();
            $result = [];
            $result['delete_shipment'] = $admin->delete_shipment($_POST['id_shipping_method']);
            echo json_encode($result);
        }

        public function save_new_shipping_zone() {
            $admin = new AdminShippingAjax('ajax-save-new-shipping-zone');
            $admin->security_admin_login();
            $result = [];
            $result['save_new_shipping_zone'] = $admin->save_new_shipping_zone();
            echo json_encode($result);            
        }

        public function save_edit_shipping_zone() {
            $admin = new AdminShippingAjax('ajax-save-edit-shipping-zone');
            $admin->security_admin_login();
            $result = [];
            $result['save_edit_shipping_zone'] = $admin->save_edit_shipping_zone();
            echo json_encode($result);            
        }

        public function delete_shipping_zone() {
            $admin = new AdminShippingAjax('ajax-delete-shipping-zone');
            $admin->security_admin_login();
            $result = [];
            $result['delete_shipping_zone'] = $admin->delete_shipping_zone($_POST['id_shipping_zone']);
            echo json_encode($result);            
        }

        public function get_shipping_zone_countries() {
            $admin = new AdminShippingAjax('ajax-get-shipping-zone-countries');
            $admin->security_admin_login();
            $result = [];
            $result['get_shipping_zone_countries'] = $admin->get_shipping_zone_countries($_POST['id_shipping_zone'], $_POST['id_continent'], $_POST['page']);
            echo json_encode($result);            
        }

        public function get_shipping_zone_provinces() {
            $admin = new AdminShippingAjax('ajax-get-shipping-zone-provinces');
            $admin->security_admin_login();
            $result = [];
            $result['get_shipping_zone_provinces'] = $admin->get_shipping_zone_provinces($_POST['id_shipping_zone'], $_POST['id_country'], $_POST['page']);
            echo json_encode($result);            
        }

        public function save_new_payment() {
            $admin = new AdminPaymentAjax('ajax-save-new-payment');
            $admin->security_admin_login();
            $result = [];
            $result['save_new_payment'] = $admin->save_new_payment();
            echo json_encode($result);
        }

        public function save_edit_payment() {
            $admin = new AdminPaymentAjax('ajax-save-edit-payment');
            $admin->security_admin_login();
            $result = [];
            $result['save_edit_payment'] = $admin->save_edit_payment();
            echo json_encode($result);
        }

        public function delete_payment() {
            $admin = new AdminPaymentAjax('ajax-delete-payment');
            $admin->security_admin_login();
            $result = [];
            $result['delete_payment'] = $admin->delete_payment($_POST['id_payment_method']);
            echo json_encode($result);
        }

        public function save_new_payment_zone() {
            $admin = new AdminPaymentAjax('ajax-save-new-payment-zone');
            $admin->security_admin_login();
            $result = [];
            $result['save_new_payment_zone'] = $admin->save_new_payment_zone();
            echo json_encode($result);
        }

        public function save_edit_payment_zone() {
            $admin = new AdminPaymentAjax('ajax-save-edit-payment-zone');
            $admin->security_admin_login();
            $result = [];
            $result['save_edit_payment_zone'] = $admin->save_edit_payment_zone();
            echo json_encode($result);            
        }

        public function delete_payment_zone() {
            $admin = new AdminPaymentAjax('ajax-delete-payment-zone');
            $admin->security_admin_login();
            $result = [];
            $result['delete_payment_zone'] = $admin->delete_payment_zone($_POST['id_payment_zone']);
            echo json_encode($result);            
        }

        public function get_payment_zone_countries() {
            $admin = new AdminPaymentAjax('ajax-get-payment-zone-countries');
            $admin->security_admin_login();
            $result = [];
            $result['get_payment_zone_countries'] = $admin->get_payment_zone_countries($_POST['id_payment_zone'], $_POST['id_continent'], $_POST['page']);
            echo json_encode($result);            
        }

        public function get_payment_zone_provinces() {
            $admin = new AdminPaymentAjax('ajax-get-payment-zone-provinces');
            $admin->security_admin_login();
            $result = [];
            $result['get_payment_zone_provinces'] = $admin->get_payment_zone_provinces($_POST['id_payment_zone'], $_POST['id_country'], $_POST['page']);
            echo json_encode($result);            
        }

        public function save_edit_user() {
            $admin = new AdminAjax('ajax-save-edit-user');
            $admin->security_admin_login();
            $result = [];
            $result['save_edit_user'] = $admin->save_edit_user();
            echo json_encode($result);
        }
        
        public function get_user_addresses() {
            $admin = new AdminAjax('ajax-get-user-addresses');
            $admin->security_admin_login();
            $result = [];
            $result['get_user_addresses'] = $admin->get_user_addresses($_POST['id_user']);
            echo json_encode($result);
        }

        public function get_user_address() {
            $admin = new AdminAjax('ajax-get-user-address');
            $admin->security_admin_login();
            $result = [];
            $result['get_user_address'] = $admin->get_user_address($_POST['id_user_address']);
            echo json_encode($result);
        }

        public function get_countries_list() {
            $admin = new AdminAjax('ajax-get-countries-list');
            $admin->security_admin_login();
            $result = [];
            if(isset($_POST['id_country'])) {
                $result['get_countries_list'] = $admin->get_countries_list($_POST['id_continent'], $_POST['id_country']);
            } else {
                $result['get_countries_list'] = $admin->get_countries_list($_POST['id_continent']);
            }
            echo json_encode($result);
        }

        public function get_provinces_list() {
            $admin = new AdminAjax('ajax-get-provinces-list');
            $admin->security_admin_login();
            $result = [];
            if(isset($_POST['id_province'])) {
                $result['get_provinces_list'] = $admin->get_provinces_list($_POST['id_country'], $_POST['id_province']);
            } else {
                $result['get_provinces_list'] = $admin->get_provinces_list($_POST['id_country']);
            }
            echo json_encode($result);
        }

        public function save_edit_user_address() {
            $admin = new AdminAjax('ajax-save-edit-user-address');
            $admin->security_admin_login();
            $result = [];
            $result['save_edit_user_address'] = $admin->save_edit_user_address();
            echo json_encode($result);
        }

        public function delete_user_address() {
            $admin = new AdminAjax('ajax-delete-user-address');
            $admin->security_admin_login();
            $result = [];
            $result['delete_user_address'] = $admin->delete_user_address($_POST['id_user_address']);
            echo json_encode($result);
        }

        public function close_user_sessions() {
            $admin = new AdminAjax('ajax-close-user-sessions');
            $admin->security_admin_login();
            $result = [];
            $result['close_user_seassons'] = $admin->close_user_sessions($_POST['id_user']);
            echo json_encode($result);
        }

        public function send_validation_email() {
            $admin = new AdminAjax('ajax-send-validation-email');
            $admin->security_admin_login();
            $result = [];
            $result['send_validation_email'] = $admin->send_validation_email($_POST['id_user']);
            echo json_encode($result);
        }

        public function delete_user() {
            $admin = new AdminAjax('ajax-delete-user');
            $admin->security_admin_login();
            $result = [];
            $result['delete_user'] = $admin->delete_user($_POST['id_user']);
            echo json_encode($result);
        }

        public function save_new_admin_user() {
            $admin = new AdminAjax('ajax-save-new-admin-user');
            $admin->security_admin_login();
            $result = [];
            $result['save_new_admin_user'] = $admin->save_new_admin_user();
            echo json_encode($result);
        }

        public function save_edit_admin_user() {
            $admin = new AdminAjax('ajax-save-edit-admin-user');
            $admin->security_admin_login();
            $result = [];
            $result['save_edit_admin_user'] = $admin->save_edit_admin_user();
            echo json_encode($result);
        }

        public function close_admin_user_sessions() {
            $admin = new AdminAjax('ajax-close-admin-user-sessions');
            $admin->security_admin_login();
            $result = [];
            $result['close_admin_user_sessions'] = $admin->close_admin_user_sessions($_POST['id_admin']);
            echo json_encode($result);
        }

        public function delete_admin_user() {
            $admin = new AdminAjax('ajax-delete-admin-user');
            $admin->security_admin_login();
            $result = [];
            $result['delete_admin_user'] = $admin->delete_admin_user($_POST['id_admin']);
            echo json_encode($result);
        }

        public function save_new_tax_type() {
            $admin = new AdminTaxAjax('ajax-save-new-tax-type');
            $admin->security_admin_login();
            $result = [];
            $result['save_new_tax_type'] = $admin->save_new_tax_type();
            echo json_encode($result);
        }

        public function save_edit_tax_type() {
            $admin = new AdminTaxAjax('ajax-save-edit-tax-type');
            $admin->security_admin_login();
            $result = [];
            $result['save_edit_tax_type'] = $admin->save_edit_tax_type();
            echo json_encode($result);
        }

        public function delete_tax_type() {
            $admin = new AdminTaxAjax('ajax-delete-tax-type');
            $admin->security_admin_login();
            $result = [];
            $result['delete_tax_type'] = $admin->delete_tax_type($_POST['id_tax_type']);
            echo json_encode($result);
        }

        public function ftpu_get_dir($args) {
            $admin = new AdminAjax('ajax-ftpu-get-dir');
            $admin->security_admin_login();
            $upload = new FtpUpload();
            $upload->connect();
            $upload->login();
            $result = [];
            $result['get_dir'] = $upload->get_folder_html($_POST['dir']);
            echo json_encode($result);
        }

        public function ftpu_compare($args) {
            $admin = new AdminAjax('ajax-ftpu-compare');
            $admin->security_admin_login();
            $upload = new FtpUpload();
            $upload->connect();
            $upload->login();
            $result = [];
            $result['compare'] = $upload->ftp_comparar($_POST['folder'], $_POST['file']);
            echo json_encode($result);
        }

        public function ftpu_upload($args) {
            $admin = new AdminAjax('ajax-ftpu-upload');
            $admin->security_admin_login();
            $upload = new FtpUpload();
            $upload->connect();
            $upload->login();
            $result = [];
            $result['upload'] = $upload->upload_ftp($_POST['folder'], $_POST['file']);
            echo json_encode($result);
        }
        
        public function ftpu_upload_all($args) {
            $admin = new AdminAjax('ajax-ftpu-all');
            $admin->security_admin_login();
            $upload = new FtpUpload();
            $upload->connect();
            $upload->login();
            $result = [];
            $result['upload'] = $upload->upload_all_ftp($_POST['folder'], $_POST['files']);
            echo json_encode($result);
        }

        public function ftpu_create_folder($args) {
            $admin = new AdminAjax('ajax-ftpu-create-dir');
            $admin->security_admin_login();
            $upload = new FtpUpload();
            $upload->connect();
            $upload->login();
            $result = [];
            $result['folder'] = $upload->create_folder($_POST['folder'], $_POST['name']);
            echo json_encode($result);
        }

    }

?>