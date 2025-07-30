<?php

    /**
     * @author Diego Martín
     * @copyright Hive®
     * @version 1.0
     * @lastUpdated 2025
     */

    // App Get
    $R->setController('CApp');

    // Routes in English
    $R->setLanguage('en');

    $R->get('')                                 ->call('root')                      ->add('empty-root');
    $R->get('/')                                ->call('root')                      ->add('/');
    $R->get('/page-404')                        ->call('page_404')                  ->add('page-404', false);
    $R->get('/home')                            ->call('home')                      ->add('home');
    $R->get('/privacy-policy')                  ->call('privacy_policy')            ->add('privacy-policy');
    $R->get('/cookie-policy')                   ->call('cookie_policy')             ->add('cookie-policy');
    $R->get('/access')                          ->call('access')                    ->add('access');
    $R->get('/register')                        ->call('register')                  ->add('register');
    $R->get('/cart')                            ->call('cart')                      ->add('cart');
    $R->get('/checkout')                        ->call('checkout')                  ->add('checkout');
    $R->get('/save-checkout-successful')        ->call('save_checkout_successful')  ->add('save-checkout-successful', false);
    $R->get('/checkout-successful')             ->call('checkout_successful')       ->add('checkout-successful', false);
    $R->get('/checkout-failed')                 ->call('checkout_failed')           ->add('checkout-failed', false);
    $R->get('/checkout-bank-transfer')          ->call('checkout_bank_transfer')    ->add('checkout-bank-transfer', false);
    $R->get('/checkout-cash-delivery')          ->call('checkout_cash_delivery')    ->add('checkout-cash-delivery', false);
    $R->get('/service-down')                    ->call('service_down')              ->add('service-down', false);
    $R->get('/validate-email')                  ->call('validate_email')            ->add('validate-email');
    $R->get('/my-account')                      ->call('my_account')                ->add('my-account');
    $R->get('/logout')                          ->call('logout')                    ->add('logout', false);

    // Routes in Spanish
    $R->setLanguage('es');

    $R->get('')                                 ->call('root')                      ->add('empty-root');
    $R->get('/')                                ->call('root')                      ->add('/');
    $R->get('/pagina-404')                      ->call('page_404')                  ->add('page-404', false);
    $R->get('/inicio')                          ->call('home')                      ->add('home');
    $R->get('/politica-de-privacidad')          ->call('privacy_policy')            ->add('privacy-policy');
    $R->get('/politica-de-cookies')             ->call('cookie_policy')             ->add('cookie-policy');
    $R->get('/acceso')                          ->call('access')                    ->add('access');
    $R->get('/registro')                        ->call('register')                  ->add('register');
    $R->get('/carrito')                         ->call('cart')                      ->add('cart');
    $R->get('/tramitar-pedido')                 ->call('checkout')                  ->add('checkout');
    $R->get('/guardar-tramitacion-correcta')    ->call('save_checkout_successful')  ->add('save-checkout-successful', false);
    $R->get('/tramitacion-correcta')            ->call('checkout_successful')       ->add('checkout-successful', false);
    $R->get('/tramitacion-fallida')             ->call('checkout_failed')           ->add('checkout-failed', false);
    $R->get('/tramitacion-transferencia')       ->call('checkout_bank_transfer')    ->add('checkout-bank-transfer', false);
    $R->get('/tramitacion-contrareembolso')     ->call('checkout_cash_delivery')    ->add('checkout-cash-delivery', false);
    $R->get('/servicio-caido')                  ->call('service_down')              ->add('service-down', false);
    $R->get('/validar-email')                   ->call('validate_email')            ->add('validate-email');
    $R->get('/mi-cuenta')                       ->call('my_account')                ->add('my-account');
    $R->get('/desconectar')                     ->call('logout')                    ->add('logout', false);

?>