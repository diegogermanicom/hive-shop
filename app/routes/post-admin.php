<?php

    /**
     * @author Diego Martín
     * @copyright Hive®
     * @version 1.0
     * @lastUpdated 2025
     */

    // Admin Post
    $R->setRoot(ADMIN_PATH);
    $R->setController('CAdminAjax');

    $R->post('/send-login')                     ->call('send_login')                        ->add('admin-send-login');
    $R->post('/create-new-sitemap')             ->call('create_new_sitemap')                ->add('create-new-sitemap');
    $R->post('/save-new-product')               ->call('save_new_product')                  ->add('save-new-product');
    $R->post('/get-product-images')             ->call('get_product_images')                ->add('get-product-images');
    $R->post('/save-product-main-image')        ->call('save_product_main_image')           ->add('save-product-main-image');
    $R->post('/save-product-hover-image')       ->call('save_product_hover_image')          ->add('save-product-hover-image');
    $R->post('/delete-product-image')           ->call('delete_product_image')              ->add('delete-product-image');
    $R->post('/delete-server-image')            ->call('delete_product_server_image')       ->add('delete-server-image');
    $R->post('/save-edit-product')              ->call('save_edit_product')                 ->add('save-edit-product');
    $R->post('/get-add-images')                 ->call('get_add_images')                    ->add('get-add-images');
    $R->post('/get-related')                    ->call('get_related')                       ->add('get-related');
    $R->post('/get-add-related')                ->call('get_add_related')                   ->add('get-add-related');
    $R->post('/add-related')                    ->call('add_related')                       ->add('add-related');
    $R->post('/get-edit-related')               ->call('get_edit_related')                  ->add('get-edit-related');
    $R->post('/save-related')                   ->call('save_related')                      ->add('save-related');
    $R->post('/delete-related')                 ->call('delete_related')                    ->add('delete-related');
    $R->post('/delete-product')                 ->call('delete_product')                    ->add('delete-product');
    $R->post('/delete-product-custom-route')    ->call('delete_product_custom_route')       ->add('delete-product-custom-route');
    $R->post('/get-product-categories-list')    ->call('get_product_categories_list')       ->add('get-product-categories-list');
    $R->post('/save-new-product-custom-route')  ->call('save_new_product_custom_route')     ->add('save-new-product-custom-route');
    $R->post('/save-new-category')              ->call('save_new_category')                 ->add('save-new-category');
    $R->post('/save-edit-category')             ->call('save_edit_category')                ->add('save-edit-category');
    $R->post('/delete-category')                ->call('delete_category')                   ->add('delete-category');
    $R->post('/delete-category-custom-route')   ->call('delete_category_custom_route')      ->add('delete-category-custom-route');
    $R->post('/save-new-category-custom-route') ->call('save_new_category_custom_route')    ->add('save-new-category-custom-route');
    $R->post('/save-new-attribute')             ->call('save_new_attribute')                ->add('save-new-attribute');
    $R->post('/save-edit-attribute')            ->call('save_edit_attribute')               ->add('save-edit-attribute');
    $R->post('/get-attribute-values')           ->call('get_attribute_values')              ->add('get-attribute-values');
    $R->post('/get-attribute-value-properties') ->call('get_attribute_value_properties')    ->add('get-attribute-value-properties');
    $R->post('/save-attribute-value-properties')->call('save_attribute_value_properties')   ->add('save-attribute-value-properties');
    $R->post('/delete-attribute')               ->call('delete_attribute')                  ->add('delete-attribute');
    $R->post('/save-new-code')                  ->call('save_new_code')                     ->add('save-new-code');
    $R->post('/save-edit-code')                 ->call('save_edit_code')                    ->add('save-edit-code');
    $R->post('/delete-code')                    ->call('delete_code')                       ->add('delete-code');
    $R->post('/add-code-rule')                  ->call('add_code_rule')                     ->add('add-code-rule');
    $R->post('/save-code-rule')                 ->call('save_code_rule')                    ->add('save-code-rule');
    $R->post('/delete-code-rule')               ->call('delete_code_rule')                  ->add('delete-code-rule');
    $R->post('/get-code-rule')                  ->call('get_code_rule')                     ->add('get-code-rule');
    $R->post('/get-code-rules')                 ->call('get_code_rules')                    ->add('get-code-rules');
    $R->post('/get-code-rule-elements-list')    ->call('get_code_rule_elements_list')       ->add('get-code-rule-elements-list');
    $R->post('/create-order-from-cart')         ->call('create_order_from_cart')            ->add('create-order-from-cart');
    // Shipment
    $R->post('/save-new-shipment')              ->call('save_new_shipment')                 ->add('save-new-shipment');
    $R->post('/save-edit-shipment')             ->call('save_edit_shipment')                ->add('save-edit-shipment');
    $R->post('/delete-shipment')                ->call('delete_shipment')                   ->add('delete-shipment');
    $R->post('/save-new-shipping-zone')         ->call('save_new_shipping_zone')            ->add('save-new-shipping-zone');
    $R->post('/save-edit-shipping-zone')        ->call('save_edit_shipping_zone')           ->add('save-edit-shipping-zone');
    $R->post('/delete-shipping-zone')           ->call('delete_shipping_zone')              ->add('delete-shipping-zone');
    $R->post('/get-shipping-zone-countries')    ->call('get_shipping_zone_countries')       ->add('get-shipping-zone-countries');
    $R->post('/get-shipping-zone-provinces')    ->call('get_shipping_zone_provinces')       ->add('get-shipping-zone-provinces');
    // Payment
    $R->post('/save-new-payment')               ->call('save_new_payment')                  ->add('save-new-payment');
    $R->post('/save-edit-payment')              ->call('save_edit_payment')                 ->add('save-edit-payment');
    $R->post('/delete-payment')                 ->call('delete_payment')                    ->add('delete-payment');
    $R->post('/save-new-payment-zone')          ->call('save_new_payment_zone')             ->add('save-new-payment-zone');
    $R->post('/save-edit-payment-zone')         ->call('save_edit_payment_zone')            ->add('save-edit-payment-zone');
    $R->post('/delete-payment-zone')            ->call('delete_payment_zone')               ->add('delete-payment-zone');
    $R->post('/get-payment-zone-countries')     ->call('get_payment_zone_countries')        ->add('get-payment-zone-countries');
    $R->post('/get-payment-zone-provinces')     ->call('get_payment_zone_provinces')        ->add('get-payment-zone-provinces');
    // Tax
    $R->post('/save-new-tax-type')              ->call('save_new_tax_type')                 ->add('save-new-tax-type');
    $R->post('/save-edit-tax-type')             ->call('save_edit_tax_type')                ->add('save-edit-tax-type');
    $R->post('/delete-tax-type')                ->call('delete_tax_type')                   ->add('delete-tax-type');
    $R->post('/save-new-tax-zone')              ->call('save_new_tax_zone')                 ->add('save-new-tax-zone');
    $R->post('/save-edit-tax-zone')             ->call('save_edit_tax_zone')                ->add('save-edit-tax-zone');
    $R->post('/delete-tax-zone')                ->call('delete_tax_zone')                   ->add('delete-tax-zone');
    $R->post('/get-tax-zone-countries')         ->call('get_tax_zone_countries')            ->add('get-tax-zone-countries');
    $R->post('/get-tax-zone-provinces')         ->call('get_tax_zone_provinces')            ->add('get-tax-zone-provinces');
    // User
    $R->post('/save-edit-user')                 ->call('save_edit_user')                    ->add('save-edit-user');
    $R->post('/get-user-addresses')             ->call('get_user_addresses')                ->add('get-user-addresses');
    $R->post('/get-user-address')               ->call('get_user_address')                  ->add('get-user-address');
    $R->post('/get-countries-list')             ->call('get_countries_list')                ->add('admin-get-countries-list');
    $R->post('/get-provinces-list')             ->call('get_provinces_list')                ->add('admin-get-provinces-list');
    $R->post('/save-edit-user-address')         ->call('save_edit_user_address')            ->add('save-edit-user-address');
    $R->post('/delete-user-address')            ->call('delete_user_address')               ->add('delete-user-address');
    $R->post('/close-user-sessions')            ->call('close_user_sessions')               ->add('close-user-sessions');
    $R->post('/send-validation-email')          ->call('send_validation_email')             ->add('send-validation-email');
    $R->post('/delete-user')                    ->call('delete_user')                       ->add('delete-user');
    $R->post('/save-new-admin-user')            ->call('save_new_admin_user')               ->add('save-new-admin-user');
    $R->post('/save-edit-admin-user')           ->call('save_edit_admin_user')              ->add('save-edit-admin-user');
    $R->post('/close-admin-user-sessions')      ->call('close_admin_user_sessions')         ->add('close-admin-user-sessions');
    $R->post('/delete-admin-user')              ->call('delete_admin_user')                 ->add('delete-admin-user');
    // FPT Upload
    $R->post('/ftpu-get-dir')                   ->call('ftpu_get_dir')                      ->add('ftpu-get-dir');
    $R->post('/ftpu-compare')                   ->call('ftpu_compare')                      ->add('ftpu-compare');
    $R->post('/ftpu-upload')                    ->call('ftpu_upload')                       ->add('ftpu-upload');
    $R->post('/ftpu-upload-all')                ->call('ftpu_upload_all')                   ->add('ftpu-upload-all');
    $R->post('/ftpu-create-folder')             ->call('ftpu_create_folder')                ->add('ftpu-create-folder');

?>