<?php

    /**
     * @author Diego Martín
     * @copyright Hive®
     * @version 1.0
     * @lastUpdated 2025
     */

    // Admin Post
    $R->setController('CAdminAjax');

    $R->post('/send-login')                         ->call_admin('send_login');
    $R->post('/create-new-sitemap')                 ->call_admin('create_new_sitemap');
    $R->post('/save-new-product')                   ->call_admin('save_new_product');
    $R->post('/get-product-images')                 ->call_admin('get_product_images');
    $R->post('/save-product-main-image')            ->call_admin('save_product_main_image');
    $R->post('/save-product-hover-image')           ->call_admin('save_product_hover_image');
    $R->post('/delete-product-image')               ->call_admin('delete_product_image');
    $R->post('/delete-server-image')                ->call_admin('delete_product_server_image');
    $R->post('/save-edit-product')                  ->call_admin('save_edit_product');
    $R->post('/get-add-images')                     ->call_admin('get_add_images');
    $R->post('/get-related')                        ->call_admin('get_related');
    $R->post('/get-add-related')                    ->call_admin('get_add_related');
    $R->post('/add-related')                        ->call_admin('add_related');
    $R->post('/get-edit-related')                   ->call_admin('get_edit_related');
    $R->post('/save-related')                       ->call_admin('save_related');
    $R->post('/delete-related')                     ->call_admin('delete_related');
    $R->post('/delete-product')                     ->call_admin('delete_product');
    $R->post('/delete-product-custom-route')        ->call_admin('delete_product_custom_route');
    $R->post('/get-product-categories-list')        ->call_admin('get_product_categories_list');
    $R->post('/save-new-product-custom-route')      ->call_admin('save_new_product_custom_route');
    $R->post('/save-new-category')                  ->call_admin('save_new_category');
    $R->post('/save-edit-category')                 ->call_admin('save_edit_category');
    $R->post('/delete-category')                    ->call_admin('delete_category');
    $R->post('/delete-category-custom-route')       ->call_admin('delete_category_custom_route');
    $R->post('/save-new-category-custom-route')     ->call_admin('save_new_category_custom_route');
    $R->post('/save-new-attribute')                 ->call_admin('save_new_attribute');
    $R->post('/save-edit-attribute')                ->call_admin('save_edit_attribute');
    $R->post('/get-attribute-values')               ->call_admin('get_attribute_values');
    $R->post('/get-attribute-value-properties')     ->call_admin('get_attribute_value_properties');
    $R->post('/save-attribute-value-properties')    ->call_admin('save_attribute_value_properties');
    $R->post('/delete-attribute')                   ->call_admin('delete_attribute');
    $R->post('/save-new-code')                      ->call_admin('save_new_code');
    $R->post('/save-edit-code')                     ->call_admin('save_edit_code');
    $R->post('/delete-code')                        ->call_admin('delete_code');
    $R->post('/add-code-rule')                      ->call_admin('add_code_rule');
    $R->post('/save-code-rule')                     ->call_admin('save_code_rule');
    $R->post('/delete-code-rule')                   ->call_admin('delete_code_rule');
    $R->post('/get-code-rule')                      ->call_admin('get_code_rule');
    $R->post('/get-code-rules')                     ->call_admin('get_code_rules');
    $R->post('/get-code-rule-elements-list')        ->call_admin('get_code_rule_elements_list');
    $R->post('/create-order-from-cart')             ->call_admin('create_order_from_cart');
    // Shipment
    $R->post('/save-new-shipment')                  ->call_admin('save_new_shipment');
    $R->post('/save-edit-shipment')                 ->call_admin('save_edit_shipment');
    $R->post('/delete-shipment')                    ->call_admin('delete_shipment');
    $R->post('/save-new-shipping-zone')             ->call_admin('save_new_shipping_zone');
    $R->post('/save-edit-shipping-zone')            ->call_admin('save_edit_shipping_zone');
    $R->post('/delete-shipping-zone')               ->call_admin('delete_shipping_zone');
    $R->post('/get-shipping-zone-countries')        ->call_admin('get_shipping_zone_countries');
    $R->post('/get-shipping-zone-provinces')        ->call_admin('get_shipping_zone_provinces');
    // Payment
    $R->post('/save-new-payment')                   ->call_admin('save_new_payment');
    $R->post('/save-edit-payment')                  ->call_admin('save_edit_payment');
    $R->post('/delete-payment')                     ->call_admin('delete_payment');
    $R->post('/save-new-payment-zone')              ->call_admin('save_new_payment_zone');
    $R->post('/save-edit-payment-zone')             ->call_admin('save_edit_payment_zone');
    $R->post('/delete-payment-zone')                ->call_admin('delete_payment_zone');
    $R->post('/get-payment-zone-countries')         ->call_admin('get_payment_zone_countries');
    $R->post('/get-payment-zone-provinces')         ->call_admin('get_payment_zone_provinces');
    // Tax
    $R->post('/save-new-tax-type')                  ->call_admin('save_new_tax_type');
    $R->post('/save-edit-tax-type')                 ->call_admin('save_edit_tax_type');
    $R->post('/delete-tax-type')                    ->call_admin('delete_tax_type');
    $R->post('/save-new-tax-zone')                  ->call_admin('save_new_tax_zone');
    $R->post('/save-edit-tax-zone')                 ->call_admin('save_edit_tax_zone');
    $R->post('/delete-tax-zone')                    ->call_admin('delete_tax_zone');
    $R->post('/get-tax-zone-countries')             ->call_admin('get_tax_zone_countries');
    $R->post('/get-tax-zone-provinces')             ->call_admin('get_tax_zone_provinces');
    // User
    $R->post('/save-edit-user')                     ->call_admin('save_edit_user');
    $R->post('/get-user-addresses')                 ->call_admin('get_user_addresses');
    $R->post('/get-user-address')                   ->call_admin('get_user_address');
    $R->post('/get-countries-list')                 ->call_admin('get_countries_list');
    $R->post('/get-provinces-list')                 ->call_admin('get_provinces_list');
    $R->post('/save-edit-user-address')             ->call_admin('save_edit_user_address');
    $R->post('/delete-user-address')                ->call_admin('delete_user_address');
    $R->post('/close-user-sessions')                ->call_admin('close_user_sessions');
    $R->post('/send-validation-email')              ->call_admin('send_validation_email');
    $R->post('/delete-user')                        ->call_admin('delete_user');
    $R->post('/save-new-admin-user')                ->call_admin('save_new_admin_user');
    $R->post('/save-edit-admin-user')               ->call_admin('save_edit_admin_user');
    $R->post('/close-admin-user-sessions')          ->call_admin('close_admin_user_sessions');
    $R->post('/delete-admin-user')                  ->call_admin('delete_admin_user');
    // FPT Upload
    $R->post('/ftpu-get-dir')                       ->call_admin('ftpu_get_dir');
    $R->post('/ftpu-compare')                       ->call_admin('ftpu_compare');
    $R->post('/ftpu-upload')                        ->call_admin('ftpu_upload');
    $R->post('/ftpu-upload-all')                    ->call_admin('ftpu_upload_all');
    $R->post('/ftpu-create-folder')                 ->call_admin('ftpu_create_folder');

?>