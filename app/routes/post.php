<?php

    /**
     * @author Diego Martín
     * @copyright Hive®
     * @version 1.0
     * @lastUpdated 2025
     */

    // App Post
    $R->setController('CAppAjax');

    $R->post('/choose-language')                ->call('choose_language')           ->add('choose-language');
    $R->post('/set-cookies')                    ->call('set_cookies')               ->add('set-cookies');
    $R->post('/save-newsletter')                ->call('save_newsletter')           ->add('save-newsletter');
    $R->post('/login-send')                     ->call('login_send')                ->add('login-send');
    $R->post('/register-send')                  ->call('register_send')             ->add('register-send');
    $R->post('/choose-color-mode')              ->call('choose_color_mode')         ->add('choose-color-mode');
    $R->post('/get-product-related')            ->call('get_product_related')       ->add('get-product-related');
    $R->post('/add-cart')                       ->call('add_cart')                  ->add('add-cart');
    $R->post('/notify-stock')                   ->call('notify_stock')              ->add('notify-stock');
    $R->post('/send-notify-stock')              ->call('send_notify_stock')         ->add('send-notify-stock');
    $R->post('/get-popup-cart')                 ->call('get_popup_cart')            ->add('get-popup-cart');
    $R->post('/remove-cart-product')            ->call('remove_cart_product')       ->add('remove-cart-product');
    $R->post('/change-product-amount')          ->call('change_product_amount')     ->add('change-product-amount');
    $R->post('/get-addresses')                  ->call('get_addresses')             ->add('get-addresses');
    $R->post('/save-new-address')               ->call('save_new_address')          ->add('save-new-address');
    $R->post('/get-address')                    ->call('get_address')               ->add('get-address');
    $R->post('/delete-address')                 ->call('delete_address')            ->add('delete-address');
    $R->post('/save-edit-address')              ->call('save_edit_address')         ->add('save-edit-address');
    $R->post('/get-billing-addresses')          ->call('get_billing_addresses')     ->add('get-billing-addresses');
    $R->post('/save-new-billing-address')       ->call('save_new_billing_address')  ->add('save-new-billing-address');
    $R->post('/get-billing-address')            ->call('get_billing_address')       ->add('get-billing-address');
    $R->post('/delete-billing-address')         ->call('delete_billing_address')    ->add('delete-billing-address');
    $R->post('/save-edit-billing-address')      ->call('save_edit_billing_address') ->add('save-edit-billing-address');
    $R->post('/get-shipping-methods')           ->call('get_shipping_methods')      ->add('get-shipping-methods');
    $R->post('/get-payment-methods')            ->call('get_payment_methods')       ->add('get-payment-methods');
    $R->post('/apply-code')                     ->call('apply_code')                ->add('apply-code');
    $R->post('/save-order-to-cart')             ->call('save_order_to_cart')        ->add('save-order-to-cart');
    $R->post('/get-countries-list')             ->call('get_countries_list')        ->add('get-countries-list');
    $R->post('/get-provinces-list')             ->call('get_provinces_list')        ->add('get-provinces-list');
    
?>