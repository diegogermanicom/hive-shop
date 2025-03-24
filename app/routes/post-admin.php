<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2023
     */

    // Admin Post
    $R->setController('CAdminAjax');
    $R->postAdmin('/send-login'                         , 'send_login');
    $R->postAdmin('/save-new-product'                   , 'save_new_product');
    $R->postAdmin('/get-product-images'                 , 'get_product_images');
    $R->postAdmin('/save-product-main-image'            , 'save_product_main_image');
    $R->postAdmin('/save-product-hover-image'           , 'save_product_hover_image');
    $R->postAdmin('/delete-product-image'               , 'delete_product_image');
    $R->postAdmin('/delete-server-image'                , 'delete_server_image');
    $R->postAdmin('/save-edit-product'                  , 'save_edit_product');
    $R->postAdmin('/get-add-images'                     , 'get_add_images');
    $R->postAdmin('/get-related'                        , 'get_related');
    $R->postAdmin('/get-add-related'                    , 'get_add_related');
    $R->postAdmin('/add-related'                        , 'add_related');
    $R->postAdmin('/get-edit-related'                   , 'get_edit_related');
    $R->postAdmin('/save-related'                       , 'save_related');
    $R->postAdmin('/delete-related'                     , 'delete_related');
    $R->postAdmin('/delete-product'                     , 'delete_product');
    $R->postAdmin('/delete-product-custom-route'        , 'delete_product_custom_route');
    $R->postAdmin('/get-product-categories-list'        , 'get_product_categories_list');
    $R->postAdmin('/save-new-product-custom-route'      , 'save_new_product_custom_route');
    $R->postAdmin('/save-new-category'                  , 'save_new_category');
    $R->postAdmin('/save-edit-category'                 , 'save_edit_category');
    $R->postAdmin('/delete-category'                    , 'delete_category');
    $R->postAdmin('/delete-category-custom-route'       , 'delete_category_custom_route');
    $R->postAdmin('/save-new-category-custom-route'     , 'save_new_category_custom_route');
    $R->postAdmin('/save-new-attribute'                 , 'save_new_attribute');
    $R->postAdmin('/save-edit-attribute'                , 'save_edit_attribute');
    $R->postAdmin('/get-attribute-values'               , 'get_attribute_values');
    $R->postAdmin('/get-attribute-value-properties'     , 'get_attribute_value_properties');
    $R->postAdmin('/save-attribute-value-properties'    , 'save_attribute_value_properties');
    $R->postAdmin('/delete-attribute'                   , 'delete_attribute');
    $R->postAdmin('/save-new-code'                      , 'save_new_code');
    $R->postAdmin('/save-edit-code'                     , 'save_edit_code');
    $R->postAdmin('/delete-code'                        , 'delete_code');
    $R->postAdmin('/add-code-rule'                      , 'add_code_rule');
    $R->postAdmin('/save-code-rule'                     , 'save_code_rule');
    $R->postAdmin('/delete-code-rule'                   , 'delete_code_rule');
    $R->postAdmin('/get-code-rule'                      , 'get_code_rule');
    $R->postAdmin('/get-code-rules'                     , 'get_code_rules');
    $R->postAdmin('/get-code-rule-elements-list'        , 'get_code_rule_elements_list');
    $R->postAdmin('/save-new-shipment'                  , 'save_new_shipment');
    $R->postAdmin('/save-edit-shipment'                 , 'save_edit_shipment');
    $R->postAdmin('/save-new-shipping-zone'             , 'save_new_shipping_zone');
    $R->postAdmin('/save-new-payment'                   , 'save_new_payment');
    $R->postAdmin('/save-edit-payment'                  , 'save_edit_payment');
    $R->postAdmin('/save-edit-user'                     , 'save_edit_user');
    $R->postAdmin('/get-user-addresses'                 , 'get_user_addresses');
    $R->postAdmin('/get-user-address'                   , 'get_user_address');
    $R->postAdmin('/get-countries-list'                 , 'get_countries_list');
    $R->postAdmin('/get-provinces-list'                 , 'get_provinces_list');
    $R->postAdmin('/save-edit-user-address'             , 'save_edit_user_address');
    $R->postAdmin('/delete-user-address'                , 'delete_user_address');
    $R->postAdmin('/close-user-sessions'                , 'close_user_sessions');
    $R->postAdmin('/send-validation-email'              , 'send_validation_email');
    $R->postAdmin('/delete-user'                        , 'delete_user');
    $R->postAdmin('/save-new-admin-user'                , 'save_new_admin_user');
    $R->postAdmin('/save-edit-admin-user'               , 'save_edit_admin_user');
    $R->postAdmin('/close-admin-user-sessions'          , 'close_admin_user_sessions');
    $R->postAdmin('/delete-admin-user'                  , 'delete_admin_user');

    $R->postAdmin('/ftpu-get-dir'                       , 'ftpu_get_dir');
    $R->postAdmin('/ftpu-compare'                       , 'ftpu_compare');
    $R->postAdmin('/ftpu-upload'                        , 'ftpu_upload');
    $R->postAdmin('/ftpu-upload-all'                    , 'ftpu_upload_all');
    $R->postAdmin('/ftpu-create-folder'                 , 'ftpu_create_folder');

?>