<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2025
     */

    // Admin Post
    $R->setRoot(ADMIN_PATH);
    $R->setController('CAdminAjax');
    $R->post(
        ['/send-login'                      , 'send_login'],
        ['/create-new-sitemap'              , 'create_new_sitemap'],        
        ['/save-new-product'                , 'save_new_product'],
        ['/get-product-images'              , 'get_product_images'],
        ['/save-product-main-image'         , 'save_product_main_image'],
        ['/save-product-hover-image'        , 'save_product_hover_image'],
        ['/delete-product-image'            , 'delete_product_image'],
        ['/delete-server-image'             , 'delete_server_image'],
        ['/save-edit-product'               , 'save_edit_product'],
        ['/get-add-images'                  , 'get_add_images'],
        ['/get-related'                     , 'get_related'],
        ['/get-add-related'                 , 'get_add_related'],
        ['/add-related'                     , 'add_related'],
        ['/get-edit-related'                , 'get_edit_related'],
        ['/save-related'                    , 'save_related'],
        ['/delete-related'                  , 'delete_related'],
        ['/delete-product'                  , 'delete_product'],
        ['/delete-product-custom-route'     , 'delete_product_custom_route'],
        ['/get-product-categories-list'     , 'get_product_categories_list'],
        ['/save-new-product-custom-route'   , 'save_new_product_custom_route'],
        ['/save-new-category'               , 'save_new_category'],
        ['/save-edit-category'              , 'save_edit_category'],
        ['/delete-category'                 , 'delete_category'],
        ['/delete-category-custom-route'    , 'delete_category_custom_route'],
        ['/save-new-category-custom-route'  , 'save_new_category_custom_route'],
        ['/save-new-attribute'              , 'save_new_attribute'],
        ['/save-edit-attribute'             , 'save_edit_attribute'],
        ['/get-attribute-values'            , 'get_attribute_values'],
        ['/get-attribute-value-properties'  , 'get_attribute_value_properties'],
        ['/save-attribute-value-properties' , 'save_attribute_value_properties'],
        ['/delete-attribute'                , 'delete_attribute'],
        ['/save-new-code'                   , 'save_new_code'],
        ['/save-edit-code'                  , 'save_edit_code'],
        ['/delete-code'                     , 'delete_code'],
        ['/add-code-rule'                   , 'add_code_rule'],
        ['/save-code-rule'                  , 'save_code_rule'],
        ['/delete-code-rule'                , 'delete_code_rule'],
        ['/get-code-rule'                   , 'get_code_rule'],
        ['/get-code-rules'                  , 'get_code_rules'],
        ['/get-code-rule-elements-list'     , 'get_code_rule_elements_list'],
        ['/save-new-shipment'               , 'save_new_shipment'],
        ['/save-edit-shipment'              , 'save_edit_shipment'],
        ['/save-new-shipping-zone'          , 'save_new_shipping_zone'],
        ['/save-edit-shipping-zone'         , 'save_edit_shipping_zone'],
        ['/get-shipping-zone-countries'     , 'get_shipping_zone_countries'],
        ['/get-shipping-zone-provinces'     , 'get_shipping_zone_providnces'],
        ['/save-new-payment'                , 'save_new_payment'],
        ['/save-edit-payment'               , 'save_edit_payment'],
        ['/save-new-payment-zone'           , 'save_new_payment_zone'],
        ['/save-edit-payment-zone'          , 'save_edit_payment_zone'],
        ['/save-edit-user'                  , 'save_edit_user'],
        ['/get-user-addresses'              , 'get_user_addresses'],
        ['/get-user-address'                , 'get_user_address'],
        ['/get-countries-list'              , 'get_countries_list'],
        ['/get-provinces-list'              , 'get_provinces_list'],
        ['/save-edit-user-address'          , 'save_edit_user_address'],
        ['/delete-user-address'             , 'delete_user_address'],
        ['/close-user-sessions'             , 'close_user_sessions'],
        ['/send-validation-email'           , 'send_validation_email'],
        ['/delete-user'                     , 'delete_user'],
        ['/save-new-admin-user'             , 'save_new_admin_user'],
        ['/save-edit-admin-user'            , 'save_edit_admin_user'],
        ['/close-admin-user-sessions'       , 'close_admin_user_sessions'],
        ['/delete-admin-user'               , 'delete_admin_user'],
        ['/ftpu-get-dir'                    , 'ftpu_get_dir'],
        ['/ftpu-compare'                    , 'ftpu_compare'],
        ['/ftpu-upload'                     , 'ftpu_upload'],
        ['/ftpu-upload-all'                 , 'ftpu_upload_all'],
        ['/ftpu-create-folder'              , 'ftpu_create_folder']
    );

?>