<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2023
     */

    // Admin Get
    $R->getAdmin(''                                 , 'CAdmin#root');
    $R->getAdmin('/'                                , 'CAdmin#root');
    $R->getAdmin('/login'                           , 'CAdmin#login');
    $R->getAdmin('/logout'                          , 'CAdmin#logout');
    $R->getAdmin('/home'                            , 'CAdmin#home');
    $R->getAdmin('/products'                        , 'CAdmin#products');
    $R->getAdmin('/products-custom-routes'          , 'CAdmin#products_custom_routes');
    $R->getAdmin('/new-product'                     , 'CAdmin#new_product');
    $R->getAdmin('/edit-product'                    , 'CAdmin#edit_product');
    $R->getAdmin('/categories'                      , 'CAdmin#categories');
    $R->getAdmin('/categories-custom-routes'        , 'CAdmin#categories_custom_routes');
    $R->getAdmin('/new-category'                    , 'CAdmin#new_category');
    $R->getAdmin('/edit-category'                   , 'CAdmin#edit_category');
    $R->getAdmin('/attributes'                      , 'CAdmin#attributes');
    $R->getAdmin('/new-attribute'                   , 'CAdmin#new_attribute');
    $R->getAdmin('/edit-attribute'                  , 'CAdmin#edit_attribute');
    $R->getAdmin('/images'                          , 'CAdmin#images');
    $R->getAdmin('/codes'                           , 'CAdmin#codes');
    $R->getAdmin('/new-code'                        , 'CAdmin#new_code');
    $R->getAdmin('/edit-code'                       , 'CAdmin#edit_code');
    $R->getAdmin('/stats'                           , 'CAdmin#stats');
    $R->getAdmin('/languages'                       , 'CAdmin#languages');
    $R->getAdmin('/language'                        , 'CAdmin#language');
    $R->getAdmin('/users'                           , 'CAdmin#users');
    $R->getAdmin('/edit-user'                       , 'CAdmin#edit_user');
    $R->getAdmin('/users-admin'                     , 'CAdmin#users_admin');
    $R->getAdmin('/edit-user-admin'                 , 'CAdmin#edit_user_admin');
    $R->getAdmin('/new-admin-user'                  , 'CAdmin#new_admin_user');
    $R->getAdmin('/carts'                           , 'CAdmin#carts');
    $R->getAdmin('/view-cart'                       , 'CAdmin#view_cart');
    $R->getAdmin('/orders'                          , 'CAdmin#orders');
    $R->getAdmin('/order'                           , 'CAdmin#order');
    $R->getAdmin('/ftp-upload'                      , 'CAdmin#ftp_upload');

?>