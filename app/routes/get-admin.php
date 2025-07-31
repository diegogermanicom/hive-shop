<?php

    /**
     * @author Diego Martín
     * @copyright Hive®
     * @version 1.0
     * @lastUpdated 2025
     */

    // Admin Get
    $R->setController('CAdmin');

    $R->get('')                                 ->call_admin('root');
    $R->get('/')                                ->call_admin('root');
    $R->get('/login')                           ->call_admin('login');
    $R->get('/logout')                          ->call_admin('logout');
    $R->get('/home')                            ->call_admin('home');
    $R->get('/products')                        ->call_admin('products');
    $R->get('/products-custom-routes')          ->call_admin('products_custom_routes');
    $R->get('/new-product')                     ->call_admin('new_product');
    $R->get('/edit-product')                    ->call_admin('edit_product');
    $R->get('/categories')                      ->call_admin('categories');
    $R->get('/categories-custom-routes')        ->call_admin('categories_custom_routes');
    $R->get('/new-category')                    ->call_admin('new_category');
    $R->get('/edit-category')                   ->call_admin('edit_category');
    $R->get('/attributes')                      ->call_admin('attributes');
    $R->get('/new-attribute')                   ->call_admin('new_attribute');
    $R->get('/edit-attribute')                  ->call_admin('edit_attribute');
    $R->get('/images')                          ->call_admin('images');
    $R->get('/stats')                           ->call_admin('stats');
    $R->get('/languages')                       ->call_admin('languages');
    $R->get('/new-language')                    ->call_admin('new_language');
    $R->get('/edit-language')                   ->call_admin('edit_language');
    $R->get('/users')                           ->call_admin('users');
    $R->get('/edit-user')                       ->call_admin('edit_user');
    $R->get('/users-admin')                     ->call_admin('users_admin');
    $R->get('/edit-user-admin')                 ->call_admin('edit_user_admin');
    $R->get('/new-admin-user')                  ->call_admin('new_admin_user');
    $R->get('/carts')                           ->call_admin('carts');
    $R->get('/view-cart')                       ->call_admin('view_cart');
    $R->get('/orders')                          ->call_admin('orders');
    $R->get('/order')                           ->call_admin('order');
    // Codes
    $R->get('/codes')                           ->call_admin('codes');
    $R->get('/new-code')                        ->call_admin('new_code');
    $R->get('/edit-code')                       ->call_admin('edit_code');
    // Tax
    $R->get('/tax-types')                       ->call_admin('tax_types');
    $R->get('/new-tax-type')                    ->call_admin('new_tax_type');
    $R->get('/edit-tax-type')                   ->call_admin('edit_tax_type');
    $R->get('/tax-zones')                       ->call_admin('tax_zones');
    $R->get('/new-tax-zone')                    ->call_admin('new_tax_zone');
    $R->get('/edit-tax-zone')                   ->call_admin('edit_tax_zone');
    // Shipment
    $R->get('/shipments')                       ->call_admin('shipments');
    $R->get('/new-shipping-method')             ->call_admin('new_shipping_method');
    $R->get('/edit-shipping-method')            ->call_admin('edit_shipping_method');
    $R->get('/shipping-zones')                  ->call_admin('shipping_zones');
    $R->get('/new-shipping-zone')               ->call_admin('new_shipping_zone');
    $R->get('/edit-shipping-zone')              ->call_admin('edit_shipping_zone');
    // Payment
    $R->get('/payments')                        ->call_admin('payments');
    $R->get('/new-payment-method')              ->call_admin('new_payment_method');
    $R->get('/edit-payment-method')             ->call_admin('edit_payment_method');
    $R->get('/payment-zones')                   ->call_admin('payment_zones');
    $R->get('/new-payment-zone')                ->call_admin('new_payment_zone');
    $R->get('/edit-payment-zone')               ->call_admin('edit_payment_zone');
    // Settings
    $R->get('/locations')                       ->call_admin('locations');
    $R->get('/configuration')                   ->call_admin('configuration');
    $R->get('/sitemap')                         ->call_admin('sitemap');
    $R->get('/ftp-upload')                      ->call_admin('ftp_upload');

?>