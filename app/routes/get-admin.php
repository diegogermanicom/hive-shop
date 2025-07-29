<?php

    /**
     * @author Diego Martín
     * @copyright Hive®
     * @version 1.0
     * @lastUpdated 2025
     */

    // Admin Get
    $R->setRoot(ADMIN_PATH);
    $R->setController('CAdmin');
    $R->setIndex(false);

    $R->get('')                                 ->call('root')                      ->add('admin-empty-root');
    $R->get('/')                                ->call('root')                      ->add('admin-bar-root');
    $R->get('/login')                           ->call('login')                     ->add('admin-login');
    $R->get('/logout')                          ->call('logout')                    ->add('admin-logout');
    $R->get('/home')                            ->call('home')                      ->add('admin-home');
    $R->get('/products')                        ->call('products')                  ->add('admin-products');
    $R->get('/products-custom-routes')          ->call('products_custom_routes')    ->add('products-custom-routes');
    $R->get('/new-product')                     ->call('new_product')               ->add('new-product');
    $R->get('/edit-product')                    ->call('edit_product')              ->add('edit-product');
    $R->get('/categories')                      ->call('categories')                ->add('categories');
    $R->get('/categories-custom-routes')        ->call('categories_custom_routes')  ->add('categories-custom-routes');
    $R->get('/new-category')                    ->call('new_category')              ->add('new-category');
    $R->get('/edit-category')                   ->call('edit_category')             ->add('edit-category');
    $R->get('/attributes')                      ->call('attributes')                ->add('attributes');
    $R->get('/new-attribute')                   ->call('new_attribute')             ->add('new-attribute');
    $R->get('/edit-attribute')                  ->call('edit_attribute')            ->add('edit-attribute');
    $R->get('/images')                          ->call('images')                    ->add('images');
    $R->get('/stats')                           ->call('stats')                     ->add('stats');
    $R->get('/languages')                       ->call('languages')                 ->add('languages');
    $R->get('/new-language')                    ->call('new_language')              ->add('new-language');
    $R->get('/edit-language')                   ->call('edit_language')             ->add('edit-language');
    $R->get('/users')                           ->call('users')                     ->add('users');
    $R->get('/edit-user')                       ->call('edit_user')                 ->add('edit-user');
    $R->get('/users-admin')                     ->call('users_admin')               ->add('users-admin');
    $R->get('/edit-user-admin')                 ->call('edit_user_admin')           ->add('edit-user-admin');
    $R->get('/new-admin-user')                  ->call('new_admin_user')            ->add('new-admin-user');
    $R->get('/carts')                           ->call('carts')                     ->add('carts');
    $R->get('/view-cart')                       ->call('view_cart')                 ->add('view-cart');
    $R->get('/orders')                          ->call('orders')                    ->add('orders');
    $R->get('/order')                           ->call('order')                     ->add('order');
    // Codes
    $R->get('/codes')                           ->call('codes')                     ->add('codes');
    $R->get('/new-code')                        ->call('new_code')                  ->add('new-code');
    $R->get('/edit-code')                       ->call('edit_code')                 ->add('edit-code');
    // Tax
    $R->get('/tax-types')                       ->call('tax_types')                 ->add('tax-types');
    $R->get('/new-tax-type')                    ->call('new_tax_type')              ->add('new-tax-type');
    $R->get('/edit-tax-type')                   ->call('edit_tax_type')             ->add('edit-tax-type');
    $R->get('/tax-zones')                       ->call('tax_zones')                 ->add('tax-zones');
    $R->get('/new-tax-zone')                    ->call('new_tax_zone')              ->add('new-tax-zone');
    $R->get('/edit-tax-zone')                   ->call('edit_tax_zone')             ->add('edit-tax-zone');
    // Shipment
    $R->get('/shipments')                       ->call('shipments')                 ->add('shipments');
    $R->get('/new-shipping-method')             ->call('new_shipping_method')       ->add('new-shipping-method');
    $R->get('/edit-shipping-method')            ->call('edit_shipping_method')      ->add('edit-shipping-method');
    $R->get('/shipping-zones')                  ->call('shipping_zones')            ->add('shipping-zones');
    $R->get('/new-shipping-zone')               ->call('new_shipping_zone')         ->add('new-shipping-zone');
    $R->get('/edit-shipping-zone')              ->call('edit_shipping_zone')        ->add('edit-shipping-zone');
    // Payment
    $R->get('/payments')                        ->call('payments')                  ->add('payments');
    $R->get('/new-payment-method')              ->call('new_payment_method')        ->add('new-payment-method');
    $R->get('/edit-payment-method')             ->call('edit_payment_method')       ->add('edit-payment-method');
    $R->get('/payment-zones')                   ->call('payment_zones')             ->add('payment-zones');
    $R->get('/new-payment-zone')                ->call('new_payment_zone')          ->add('new-payment-zone');
    $R->get('/edit-payment-zone')               ->call('edit_payment_zone')         ->add('edit-payment-zone');
    // Settings
    $R->get('/locations')                       ->call('locations')                 ->add('locations');
    $R->get('/configuration')                   ->call('configuration')             ->add('configuration');
    $R->get('/sitemap')                         ->call('sitemap')                   ->add('sitemap');
    $R->get('/ftp-upload')                      ->call('ftp_upload')                ->add('ftp-upload');

?>