<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2025
     */

    // Admin Get
    $R->setRoot(ADMIN_PATH);
    $R->setController('CAdmin');
    $R->get(
        [''                                 , 'root'],
        ['/'                                , 'root'],
        ['/login'                           , 'login'],
        ['/logout'                          , 'logout'],
        ['/home'                            , 'home'],
        ['/products'                        , 'products'],
        ['/products-custom-routes'          , 'products_custom_routes'],
        ['/new-product'                     , 'new_product'],
        ['/edit-product'                    , 'edit_product'],
        ['/categories'                      , 'categories'],
        ['/categories-custom-routes'        , 'categories_custom_routes'],
        ['/new-category'                    , 'new_category'],
        ['/edit-category'                   , 'edit_category'],
        ['/attributes'                      , 'attributes'],
        ['/new-attribute'                   , 'new_attribute'],
        ['/edit-attribute'                  , 'edit_attribute'],
        ['/images'                          , 'images'],
        ['/codes'                           , 'codes'],
        ['/new-code'                        , 'new_code'],
        ['/edit-code'                       , 'edit_code'],
        ['/stats'                           , 'stats'],
        ['/languages'                       , 'languages'],
        ['/edit-language'                   , 'edit_language'],
        ['/users'                           , 'users'],
        ['/edit-user'                       , 'edit_user'],
        ['/users-admin'                     , 'users_admin'],
        ['/edit-user-admin'                 , 'edit_user_admin'],
        ['/new-admin-user'                  , 'new_admin_user'],
        ['/carts'                           , 'carts'],
        ['/view-cart'                       , 'view_cart'],
        ['/orders'                          , 'orders'],
        ['/order'                           , 'order'],
        ['/taxes'                           , 'taxes'],
        ['/locations'                       , 'locations'],
        ['/shipments'                       , 'shipments'],
        ['/new-shipping-method'             , 'new_shipping_method'],
        ['/edit-shipping-method'            , 'edit_shipping_method'],
        ['/shipping-zones'                  , 'shipping_zones'],
        ['/new-shipping-zone'               , 'new_shipping_zone'],
        ['/edit-shipping-zone'              , 'edit_shipping_zone'],
        ['/payments'                        , 'payments'],
        ['/new-payment-method'              , 'new_payment_method'],
        ['/edit-payment-method'             , 'edit_payment_method'],
        ['/payment-zones'                   , 'payment_zones'],
        ['/new-payment-zone'                , 'new_payment_zone'],
        ['/edit-payment-zone'               , 'edit_payment_zone'],
        ['/sitemap'                         , 'sitemap'],
        ['/ftp-upload'                      , 'ftp_upload']
    );

?>