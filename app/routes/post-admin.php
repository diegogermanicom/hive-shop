<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2023
     */

    // Admin Post
    $R->postAdmin('/send-login'                         , 'CAdminAjax#send_login');
    $R->postAdmin('/save-new-product'                   , 'CAdminAjax#save_new_product');
    $R->postAdmin('/get-product-images'                 , 'CAdminAjax#get_product_images');
    $R->postAdmin('/save-product-main-image'            , 'CAdminAjax#save_product_main_image');
    $R->postAdmin('/save-product-hover-image'           , 'CAdminAjax#save_product_hover_image');
    $R->postAdmin('/delete-product-image'               , 'CAdminAjax#delete_product_image');
    $R->postAdmin('/delete-server-image'                , 'CAdminAjax#delete_server_image');
    $R->postAdmin('/save-edit-product'                  , 'CAdminAjax#save_edit_product');
    $R->postAdmin('/get-add-images'                     , 'CAdminAjax#get_add_images');
    $R->postAdmin('/get-related'                        , 'CAdminAjax#get_related');
    $R->postAdmin('/get-add-related'                    , 'CAdminAjax#get_add_related');
    $R->postAdmin('/add-related'                        , 'CAdminAjax#add_related');
    $R->postAdmin('/get-edit-related'                   , 'CAdminAjax#get_edit_related');
    $R->postAdmin('/save-related'                       , 'CAdminAjax#save_related');
    $R->postAdmin('/delete-related'                     , 'CAdminAjax#delete_related');
    $R->postAdmin('/delete-product'                     , 'CAdminAjax#delete_product');
    $R->postAdmin('/delete-product-custom-route'        , 'CAdminAjax#delete_product_custom_route');
    $R->postAdmin('/get-product-categories-list'        , 'CAdminAjax#get_product_categories_list');
    $R->postAdmin('/save-new-product-custom-route'      , 'CAdminAjax#save_new_product_custom_route');
    $R->postAdmin('/save-new-category'                  , 'CAdminAjax#save_new_category');
    $R->postAdmin('/save-edit-category'                 , 'CAdminAjax#save_edit_category');
    $R->postAdmin('/delete-category'                    , 'CAdminAjax#delete_category');
    $R->postAdmin('/delete-category-custom-route'       , 'CAdminAjax#delete_category_custom_route');
    $R->postAdmin('/save-new-category-custom-route'     , 'CAdminAjax#save_new_category_custom_route');
    $R->postAdmin('/save-new-attribute'                 , 'CAdminAjax#save_new_attribute');
    $R->postAdmin('/save-edit-attribute'                , 'CAdminAjax#save_edit_attribute');
    $R->postAdmin('/get-attribute-values'               , 'CAdminAjax#get_attribute_values');
    $R->postAdmin('/get-attribute-value-properties'     , 'CAdminAjax#get_attribute_value_properties');
    $R->postAdmin('/save-attribute-value-properties'    , 'CAdminAjax#save_attribute_value_properties');
    $R->postAdmin('/delete-attribute'                   , 'CAdminAjax#delete_attribute');
    $R->postAdmin('/save-new-code'                      , 'CAdminAjax#save_new_code');
    $R->postAdmin('/save-edit-code'                     , 'CAdminAjax#save_edit_code');
    $R->postAdmin('/delete-code'                        , 'CAdminAjax#delete_code');
    $R->postAdmin('/save-edit-user'                     , 'CAdminAjax#save_edit_user');
    $R->postAdmin('/get-user-addresses'                 , 'CAdminAjax#get_user_addresses');
    $R->postAdmin('/get-user-address'                   , 'CAdminAjax#get_user_address');
    $R->postAdmin('/get-countries-list'                 , 'CAdminAjax#get_countries_list');
    $R->postAdmin('/get-provinces-list'                 , 'CAdminAjax#get_provinces_list');
    $R->postAdmin('/save-edit-user-address'             , 'CAdminAjax#save_edit_user_address');
    $R->postAdmin('/delete-user-address'                , 'CAdminAjax#delete_user_address');
    $R->postAdmin('/close-user-sessions'                , 'CAdminAjax#close_user_sessions');
    $R->postAdmin('/send-validation-email'              , 'CAdminAjax#send_validation_email');
    $R->postAdmin('/delete-user'                        , 'CAdminAjax#delete_user');
    $R->postAdmin('/save-new-admin-user'                , 'CAdminAjax#save_new_admin_user');
    $R->postAdmin('/save-edit-admin-user'               , 'CAdminAjax#save_edit_admin_user');
    $R->postAdmin('/close-admin-user-sessions'          , 'CAdminAjax#close_admin_user_sessions');
    $R->postAdmin('/delete-admin-user'                  , 'CAdminAjax#delete_admin_user');

    $R->postAdmin('/ftpu-get-dir'                       , 'CAdminAjax#ftpu_get_dir');
    $R->postAdmin('/ftpu-compare'                       , 'CAdminAjax#ftpu_compare');
    $R->postAdmin('/ftpu-upload'                        , 'CAdminAjax#ftpu_upload');
    $R->postAdmin('/ftpu-upload-all'                    , 'CAdminAjax#ftpu_upload_all');
    $R->postAdmin('/ftpu-create-folder'                 , 'CAdminAjax#ftpu_create_folder');

?>