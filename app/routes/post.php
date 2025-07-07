<?php

    /**
     * @author Diego Martín
     * @copyright Hive®
     * @version 1.0
     * @lastUpdated 2025
     */

    // App Post
    $R->setController('CAppAjax');
    $R->post(
        ['/choose-language'                 , 'choose_language'],
        ['/set-cookies'                     , 'set_cookies'],
        ['/save-newsletter'                 , 'save_newsletter'],
        ['/login-send'                      , 'login_send'],
        ['/register-send'                   , 'register_send'],
        ['/choose-color-mode'               , 'choose_color_mode'],
        ['/get-product-related'             , 'get_product_related'],
        ['/add-cart'                        , 'add_cart'],
        ['/notify-stock'                    , 'notify_stock'],
        ['/send-notify-stock'               , 'send_notify_stock'],
        ['/get-popup-cart'                  , 'get_popup_cart'],
        ['/remove-cart-product'             , 'remove_cart_product'],
        ['/change-product-amount'           , 'change_product_amount'],
        ['/get-addresses'                   , 'get_addresses'],
        ['/save-new-address'                , 'save_new_address'],
        ['/get-address'                     , 'get_address'],
        ['/delete-address'                  , 'delete_address'],
        ['/save-edit-address'               , 'save_edit_address'],
        ['/get-billing-addresses'           , 'get_billing_addresses'],
        ['/save-new-billing-address'        , 'save_new_billing_address'],
        ['/get-billing-address'             , 'get_billing_address'],
        ['/delete-billing-address'          , 'delete_billing_address'],
        ['/save-edit-billing-address'       , 'save_edit_billing_address'],
        ['/get-shipping-methods'            , 'get_shipping_methods'],
        ['/get-payment-methods'             , 'get_payment_methods'],
        ['/apply-code'                      , 'apply_code'],
        ['/save-order-to-cart'              , 'save_order_to_cart'],
        ['/get-countries-list'              , 'get_countries_list'],
        ['/get-provinces-list'              , 'get_provinces_list']
    );
    
?>