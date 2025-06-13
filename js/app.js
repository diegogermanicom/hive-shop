/*
* Author: Diego Martin
* Copyright: Hive®
* Version: 1.0
* Last Update: 2023
*/   

var APP = {
    // Init
    init: function() {
        this.acceptCookiesEvent();
        this.sendNewsletterEvent();
        this.sendLoginEvent();
        this.sendRegisterEvent();
        this.chooseLanguageEvent();
        this.productEvents();
        this.checkoutEvents();
        this.newAddressEvents();
        this.editAddressEvents();
        this.newBillingAddressEvents();
        this.closeCartEvent();
        this.showCartEvent();
        this.editBillingAddressEvents();
    },
    // Functions
    getProductRelated: function() {
        var btns = $('.content-attributes');
        btns.addClass('disabled');
        var obj = {
            id_product: $('#input-id-product').val(),
            id_current_product_relared: $('#input-id-product-related').val(),
            id_attribute_values: []
        }
        btns.find('.item.active').each(function() {
            obj.id_attribute_values.push(parseInt($(this).attr('id-attribute-value')));
        });
        $.ajax({
            url: PUBLIC_PATH + '/get-product-related',
            data: obj,
            success: function(data) {
                let product_related = data.get_product_related.product_related;
                $('#btn-add-to-cart').addClass('hidden');
                $('#btn-notify-stock').addClass('hidden');
                $('#label-discontinued').addClass('hidden');
                if(data.get_product_related.response == 'ok') {
                    $('#input-id-product-related').val(product_related.id_product_related);
                    if(product_related.images != null) {
                        $('.content-images').html(product_related.images.desktop);
                    }
                    $('#label-product-price').html(product_related.price);
                    if(product_related.stock != 0) {
                        $('#btn-add-to-cart').removeClass('hidden');
                    } else {
                        $('#btn-notify-stock').removeClass('hidden');
                    }
                } else {
                    $('#input-id-product-related').val('0');
                    $('.content-images').html('');
                    $('#label-product-price').html('-');
                    $('#label-discontinued').removeClass('hidden');
                }
                btns.removeClass('disabled');
                UTILS.scrollEvent();
            }
        });    
    },
    getPopupCart: function() {
        $.ajax({
            url: PUBLIC_PATH + '/get-popup-cart',
            data: {},
            success: function(data) {
                if(data.get_popup_cart.response == 'ok') {
                    $('#label-popup-cart-total').html(data.get_popup_cart.total);
                    $('.content-codes').html(data.get_popup_cart.html_codes);
                    $('.content-cart').html(data.get_popup_cart.html);
                    $('.btn-popup-cart-continue').attr('href', data.get_popup_cart.button_url);
                    if(data.get_popup_cart.button_display == true) {
                        $('.btn-popup-cart-continue').removeClass('hidden');
                    } else {
                        $('.btn-popup-cart-continue').addClass('hidden');
                    }
                    $('.content-cart').html(data.get_popup_cart.html);
                    $('.btn-remove-cart-product').on('click', function() {
                        var btn = $(this);
                        var obj = {
                            id: btn.closest('.item').attr('id-cart')
                        }
                        if(!btn.hasClass('disabled')) {
                            btn.addClass('disabled');
                            $.ajax({
                                url: PUBLIC_PATH + '/remove-cart-product',
                                data: obj,
                                success: function(data) {
                                    if(data.remove_cart_product.response == 'ok') {
                                        APP.getPopupCart();
                                    }
                                }
                            });
                        }
                    });
                    $('.input-cart-product-amount').on('change', function() {
                        var btn = $(this);
                        var obj = {
                            id: btn.closest('.item').attr('id-cart'),
                            amount: $(this).val().trim()
                        }
                        btn.closest('.item *').removeClass('error');
                        if(!UTILS.validate('number', obj.amount)) {
                            btn.addClass('error');
                        }
                        if(!btn.hasClass('disabled') && !btn.hasClass('error')) {
                            btn.addClass('disabled');
                            $.ajax({
                                url: PUBLIC_PATH + '/change-product-amount',
                                data: obj,
                                success: function(data) {
                                    if(data.change_product_amount.response == 'ok') {
                                        APP.getPopupCart();
                                    }
                                }
                            });
                        }
                    });
                    $('#popup-cart').addClass('active');
                }
            }
        });
    },
    getAddresses: function() {
        $('#address-list').html('<div class="text-center w-100"><i class="fa-solid fa-gear fa-spin"></i></div>');
        $.ajax({
            url: PUBLIC_PATH + '/get-addresses',
            data: {},
            success: function(data) {
                if(data.get_addresses.response == 'ok') {
                    $('#address-list').html(data.get_addresses.html);
                    APP.getShippingMethods();
                    $('#address-list .btn-select-address').on('click', function() {
                        $('#address-list .item').removeClass('active');
                        $('#address-list .item .btn-select-address').removeClass('hidden');
                        $(this).closest('.item').addClass('active');
                        $(this).closest('.item .btn-select-address').addClass('hidden');
                    });
                    $('#address-list .btn-edit-address').on('click', function() {
                        var btn = $(this);
                        var obj = {
                            id_user_address: btn.closest('.item').attr('id-user-address')
                        }
                        if(!btn.hasClass('disabled')) {
                            btn.addClass('disabled');
                            $('#popup-edit-address').attr('id-user-address', obj.id_user_address);
                            $('#checkbox-edit-address-main').prop("checked", false);
                            $('#popup-edit-address *').removeClass('error');
                            $.ajax({
                                url: PUBLIC_PATH + '/get-address',
                                data: obj,
                                success: function(data) {
                                    if(data.get_address.response == 'ok') {
                                        $('#input-edit-address-country').html(data.get_address.countries);
                                        $('#input-edit-address-province').html(data.get_address.provinces);
                                        $('#input-edit-address-name').val(data.get_address.address.name);
                                        $('#input-edit-address-lastname').val(data.get_address.address.lastname);
                                        $('#input-edit-address-continent').val(data.get_address.address.id_continent);
                                        $('#input-edit-address-country').val(data.get_address.address.id_country);
                                        $('#input-edit-address-province').val(data.get_address.address.id_province);
                                        $('#input-edit-address-address').val(data.get_address.address.address);
                                        $('#input-edit-address-location').val(data.get_address.address.location);
                                        $('#input-edit-address-postal-code').val(data.get_address.address.postal_code);
                                        $('#input-edit-address-telephone').val(data.get_address.address.telephone);
                                        if(data.get_address.address.main_address == 1) {
                                            $('.edit-address-main-content').addClass('hidden');
                                        } else {
                                            $('.edit-address-main-content').removeClass('hidden');
                                        }
                                        UTILS.showPopup('#popup-edit-address');
                                    }
                                    btn.removeClass('disabled');
                                }
                            });
                        }
                    });
                    $('#address-list .btn-delete-address').on('click', function() {
                        var btn = $(this);
                        var obj = {
                            id_user_address: btn.closest('.item').attr('id-user-address')
                        }
                        if(!btn.hasClass('disabled')) {
                            btn.addClass('disabled');
                            $.ajax({
                                url: PUBLIC_PATH + '/delete-address',
                                data: obj,
                                success: function(data) {
                                    btn.closest('.item').remove();
                                }
                            });
                        }
                    });
                }
            }
        });    
    },
    getBillingAddresses: function() {
        $('#billing-list').html('<div class="text-center w-100"><i class="fa-solid fa-gear fa-spin"></i></div>');
        $.ajax({
            url: PUBLIC_PATH + '/get-billing-addresses',
            data: {},
            success: function(data) {
                if(data.get_billing_addresses.response == 'ok') {
                    $('#billing-list').html(data.get_billing_addresses.html);
                    $('#billing-list .btn-select-address').on('click', function() {
                        $('#billing-list .item').removeClass('active');
                        $('#billing-list .item .btn-select-address').removeClass('hidden');
                        $(this).closest('.item').addClass('active');
                        $(this).closest('.item .btn-select-address').addClass('hidden');
                    });
                    $('#billing-list .btn-edit-address').on('click', function() {
                        var btn = $(this);
                        var obj = {
                            id_user_billing_address: btn.closest('.item').attr('id-user-billing-address')
                        }
                        if(!btn.hasClass('disabled')) {
                            btn.addClass('disabled');
                            $('#popup-edit-billing-address').attr('id-user-billing-address', obj.id_user_billing_address);
                            $('#checkbox-edit-billing-address-main').prop("checked", false);
                            $('#popup-edit-billing-address *').removeClass('error');
                            $.ajax({
                                url: PUBLIC_PATH + '/get-billing-address',
                                data: obj,
                                success: function(data) {
                                    if(data.get_billing_address.response == 'ok') {
                                        $('#input-edit-billing-address-country').html(data.get_billing_address.countries);
                                        $('#input-edit-billing-address-province').html(data.get_billing_address.provinces);
                                        $('#input-edit-billing-address-name').val(data.get_billing_address.address.name);
                                        $('#input-edit-billing-address-lastname').val(data.get_billing_address.address.lastname);
                                        $('#input-edit-billing-address-continent').val(data.get_billing_address.address.id_continent);
                                        $('#input-edit-billing-address-country').val(data.get_billing_address.address.id_country);
                                        $('#input-edit-billing-address-province').val(data.get_billing_address.address.id_province);
                                        $('#input-edit-billing-address-address').val(data.get_billing_address.address.address);
                                        $('#input-edit-billing-address-location').val(data.get_billing_address.address.location);
                                        $('#input-edit-billing-address-postal-code').val(data.get_billing_address.address.postal_code);
                                        $('#input-edit-billing-address-telephone').val(data.get_billing_address.address.telephone);
                                        if(data.get_billing_address.address.main_address == 1) {
                                            $('.edit-billing-address-main-content').addClass('hidden');
                                        } else {
                                            $('.edit-billing-address-main-content').removeClass('hidden');
                                        }
                                        UTILS.showPopup('#popup-edit-billing-address');
                                    }
                                    btn.removeClass('disabled');
                                }
                            });
                        }
                    });
                    $('#billing-list .btn-delete-address').on('click', function() {
                        var btn = $(this);
                        var obj = {
                            id_user_billing_address: btn.closest('.item').attr('id-user-billing-address')
                        }
                        if(!btn.hasClass('disabled')) {
                            btn.addClass('disabled');
                            $.ajax({
                                url: PUBLIC_PATH + '/delete-billing-address',
                                data: obj,
                                success: function(data) {
                                    btn.closest('.item').remove();
                                }
                            });
                        }
                    });
                }
            }
        });    
    },
    getShippingMethods: function() {
        $('#shipping-methods-list').html('<div class="text-center w-100"><i class="fa-solid fa-gear fa-spin"></i></div>');
        $.ajax({
            url: PUBLIC_PATH + '/get-shipping-methods',
            data: {},
            success: function(data) {
                if(data.get_shipping_methods.response == 'ok') {
                    $('#shipping-methods-list').html(data.get_shipping_methods.html);
                }
            }
        });
    },
    // Events
    acceptCookiesEvent: function() {
        $("#btn-acepta-cookies").off().on("click", function() {
            var btn = $(this);
            var obj = {
                dato: true
            }
            if(!btn.hasClass('disabled')) {
                btn.addClass('disabled');
                $.ajax({
                    url: PUBLIC_PATH + '/set-cookies',
                    data: obj,
                    success: function(data) {
                        if(data.cookie.response == 'ok') {
                            btn.addClass('btn-ok');
                            $('#popup-cookies').removeClass('active');
                        } else {
                            UTILS.showInfo('Error', 'An unexpected error has occurred.<br>Reload the page to try again.');
                            btn.removeClass('disabled');
                        }
                    }
                });
            }
        });
    },
    sendNewsletterEvent: function() {
        $("#btn-send-newsletter").off().on("click", function() {
            var btn = $(this);
            var obj = {
                email: $('#input-send-newsletter').val().trim()
            }
            $('.newsletter-content *').removeClass('error');
            if(!UTILS.validate('email', obj.email)) {
                $('#input-send-newsletter').addClass('error');
            }
            if($('#checkbox-send-newsletter:checked').val() == undefined) {
                $('#checkbox-send-newsletter').addClass('error');
            }
            if(!btn.hasClass('disabled') && $('.newsletter-content .error').length == 0) {
                btn.addClass('disabled');
                $.ajax({
                    url: PUBLIC_PATH + '/save-newsletter',
                    data: obj,
                    success: function(data) {
                        if(data.newsletter.response == 'ok') {
                            btn.addClass('btn-ok');
                            UTILS.showInfo(data.newsletter.title, data.newsletter.description);
                        } else {
                            UTILS.showInfo('Error', 'An unexpected error has occurred.<br>Reload the page to try again.');
                            btn.removeClass('disabled');
                        }
                    }
                });    
            }
        });    
    },
    sendLoginEvent: function() {
        $("#btn-send-login").on("click", function() {
            var btn = $(this);
            var obj = {
                email: $('#input-login-email').val().trim(),
                pass: $('#input-login-pass').val().trim(),
                remember: ($('#checkbox-login-remember:checked').val() == undefined) ? 0 : 1,
                checkout: $('#input-checkout').val()
            }
            $('.login-content *').removeClass('error');
            if(!UTILS.validate('email', obj.email)) {
                $('#input-login-email').addClass('error');
            }
            if(obj.pass == '' || obj.pass.length < 8) {
                $('#input-login-pass').addClass('error');
            }
            if(!btn.hasClass('disabled') && $('.login-content .error').length == 0) {
                btn.addClass('disabled');
                $.ajax({
                    url: PUBLIC_PATH + '/login-send',
                    data: obj,
                    success: function(data) {
                        if(data.login.response == 'ok') {
                            btn.addClass('btn-ok');
                            window.location.href = data.login.url;
                        } else {
                            UTILS.showInfo('Uups', data.login.mensaje);
                            btn.removeClass('disabled');
                        }
                    }
                });    
            }
        });    
    },
    sendRegisterEvent: function() {
        $("#btn-send-register").on("click", function() {
            var btn = $(this);
            var obj = {
                email: $('#input-register-email').val().trim(),
                name: $('#input-register-name').val().trim(),
                lastname: $('#input-register-lastname').val().trim(),
                pass1: $('#input-register-pass-1').val().trim(),
                pass2: $('#input-register-pass-2').val().trim(),
                newsletter: ($('#checkbox-register-newsletter:checked').val() == undefined) ? 0 : 1,
                checkout: $('#input-checkout').val()
            }
            $('.register-content *').removeClass('error');
            if(!UTILS.validate('email', obj.email)) {
                $('#input-register-email').addClass('error');
            }
            if(!UTILS.validate('name', obj.name)) {
                $('#input-register-name').addClass('error');
            }
            if(!UTILS.validate('lastname', obj.lastname)) {
                $('#input-register-lastname').addClass('error');
            }
            if(obj.pass1 == '' || obj.pass1.length < 8) {
                $('#input-register-pass-1').addClass('error');
            }
            if(obj.pass2 == '' || obj.pass2.length < 8) {
                $('#input-register-pass-2').addClass('error');
            }
            if(obj.pass1 != obj.pass2) {
                $('#input-register-pass-2').addClass('error');
            }
            if($('#checkbox-register-accept:checked').val() == undefined) {
                $('#checkbox-register-accept').addClass('error');
            }
            if(!btn.hasClass('disabled') && $('.register-content .error').length == 0) {
                btn.addClass('disabled');
                $.ajax({
                    url: PUBLIC_PATH + '/register-send',
                    data: obj,
                    success: function(data) {
                        if(data.register.response == 'ok') {
                            btn.addClass('btn-ok');
                            window.location.href = data.register.url;
                        } else {
                            UTILS.showInfo('Uups', data.register.mensaje);
                            btn.removeClass('disabled');
                        }
                    }
                });    
            }
        });    
    },
    chooseLanguageEvent: function() {
        $("#select-choose-language").on("change", function() {
            var select = $(this);
            var route = $("option:selected", this).attr("route");
            var obj = {
                language: $(this).val()
            }
            if(!select.hasClass('disabled')) {
                select.addClass('disabled');
                select.prop('disabled', true);
                $.ajax({
                    url: PUBLIC_PATH + '/choose-language',
                    data: obj,
                    success: function(data) {
                        if(data.language.response == 'ok') {
                            window.location.href = route;
                        } else {
                            select.prop('disabled', false);
                            select.removeClass('disabled');
                        }
                    }
                });    
            }
        });    
    },
    productEvents: function() {
        if($('body#product-page').length != 0) {
            APP.getProductRelated();
            $('.content-attributes .item:not(.disabled)').on('click', function() {
                if(!$(this).hasClass('active')) {
                    var btn = $(this);
                    btn.closest('.content-attribute').find('.item.active').removeClass('active');
                    btn.addClass('active');
                    if(!$('.content-attributes').hasClass('disabled')) {
                        APP.getProductRelated();
                    }
                }
            });
            $('#btn-add-to-cart').on('click', function() {
                var btn = $(this);
                var obj = {
                    id_product: $('#input-id-product').val(),
                    id_product_related: $('#input-id-product-related').val(),
                    id_category: $('#input-id-category').val()
                }
                if(!btn.hasClass('disabled')) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: PUBLIC_PATH + '/add-cart',
                        data: obj,
                        success: function(data) {
                            if(data.add_cart.response == 'ok') {
                                APP.getPopupCart();
                            }
                            btn.removeClass('disabled');
                        }
                    });    
                }    
            });
            $('#btn-notify-stock').on('click', function() {
                var btn = $(this);
                var obj = {
                    id_product: $('#input-id-product').val(),
                    id_product_related: $('#input-id-product-related').val(),
                    id_category: $('#input-id-category').val()
                }
                if(!btn.hasClass('disabled')) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: PUBLIC_PATH + '/notify-stock',
                        data: obj,
                        success: function(data) {
                            if(data.notify_stock.response == 'ok') {
                                if(data.notify_stock.popup == true) {
                                    $('#input-notify-stock-name').val('');
                                    $('#input-notify-stock-email').val('');
                                    $('#popup-notify-stock *').removeClass('error');
                                    UTILS.showPopup('#popup-notify-stock');
                                } else {
                                    UTILS.showPopup('#popup-notify-stock-info');
                                }
                            }
                            btn.removeClass('disabled');
                        }
                    });    
                }    
            });
            $('#btn-send-notify-stock').on('click', function() {
                var btn = $(this);
                var obj = {
                    id_product: $('#input-id-product').val(),
                    id_product_related: $('#input-id-product-related').val(),
                    id_category: $('#input-id-category').val(),
                    name: $('#input-notify-stock-name').val().trim(),
                    email: $('#input-notify-stock-email').val().trim()
                }
                $('#popup-notify-stock *').removeClass('error');
                if(!UTILS.validate('name', obj.name)) {
                    $('#input-notify-stock-name').addClass('error');
                }
                if(!UTILS.validate('email', obj.email)) {
                    $('#input-notify-stock-email').addClass('error');
                }
                if(!btn.hasClass('disabled') && $('#popup-notify-stock .error').length == 0) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: PUBLIC_PATH + '/send-notify-stock',
                        data: obj,
                        success: function(data) {
                            if(data.send_notify_stock.response == 'ok') {
                                UTILS.closePopup('#popup-notify-stock');
                                UTILS.showInfo('Correct!', data.send_notify_stock.mensaje);
                            }
                            btn.removeClass('disabled');
                        }
                    });    
                }    
            });
        }    
    },
    checkoutEvents: function() {
        if($('body#checkout-page').length != 0) {
            APP.getAddresses();
            $('#btn-popup-new-address').on('click', function() {
                $('#popup-new-address input').val('');
                $('#popup-new-address select').val(0);
                $('#input-new-address-country').html('');
                $('#input-new-address-province').html('');
                $('#checkbox-new-address-main').prop("checked", false);
                $('#popup-new-address *').removeClass('error');
                UTILS.showPopup('#popup-new-address');
            });
            $('#btn-popup-new-billing-address').on('click', function() {
                $('#popup-new-billing-address input').val('');
                $('#popup-new-billing-address select').val(0);
                $('#input-new-billing-address-country').html('');
                $('#input-new-billing-address-province').html('');
                $('#checkbox-new-billing-address-main').prop("checked", false);
                $('#popup-new-billing-address *').removeClass('error');
                UTILS.showPopup('#popup-new-billing-address');
            });
            $('#btn-save-new-address').on('click', function() {
                var btn = $(this);
                var obj = {
                    name: $('#input-new-address-name').val().trim(),
                    lastname: $('#input-new-address-lastname').val().trim(),
                    id_continent: $('#input-new-address-continent').val(),
                    id_country: $('#input-new-address-country').val(),
                    id_province: $('#input-new-address-province').val(),
                    address: $('#input-new-address-address').val().trim(),
                    location: $('#input-new-address-location').val().trim(),
                    postal_code: $('#input-new-address-postal-code').val().trim(),
                    telephone: $('#input-new-address-telephone').val().trim(),
                    main: ($('#checkbox-new-address-main:checked').val() == undefined) ? 0 : 1
                }
                $('#popup-new-address *').removeClass('error');
                if(!UTILS.validate('name', obj.name)) {
                    $('#input-new-address-name').addClass('error');
                }
                if(!UTILS.validate('lastname', obj.lastname)) {
                    $('#input-new-address-lastname').addClass('error');
                }
                if(obj.id_country == null) {
                    $('#input-new-address-country').addClass('error');
                }
                if(obj.id_province == null) {
                    $('#input-new-address-province').addClass('error');
                }
                if(!btn.hasClass('disabled') && $('#popup-new-address .error').length == 0) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: PUBLIC_PATH + '/save-new-address',
                        data: obj,
                        success: function(data) {
                            if(data.save_new_address.response == 'ok') {
                                APP.getAddresses();
                                UTILS.closePopup('#popup-new-address');
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
            $('#btn-edit-address').on('click', function() {
                var btn = $(this);
                var obj = {
                    id_user_address: $('#popup-edit-address').attr('id-user-address'),
                    name: $('#input-edit-address-name').val().trim(),
                    lastname: $('#input-edit-address-lastname').val().trim(),
                    id_continent: $('#input-edit-address-continent').val(),
                    id_country: $('#input-edit-address-country').val(),
                    id_province: $('#input-edit-address-province').val(),
                    address: $('#input-edit-address-address').val().trim(),
                    location: $('#input-edit-address-location').val().trim(),
                    postal_code: $('#input-edit-address-postal-code').val().trim(),
                    telephone: $('#input-edit-address-telephone').val().trim(),
                    main: ($('#checkbox-edit-address-main:checked').val() == undefined) ? 0 : 1
                }
                $('#popup-edit-address *').removeClass('error');
                if(!UTILS.validate('name', obj.name)) {
                    $('#input-edit-address-name').addClass('error');
                }
                if(!UTILS.validate('lastname', obj.lastname)) {
                    $('#input-edit-address-lastname').addClass('error');
                }
                if(obj.id_country == null) {
                    $('#input-edit-address-country').addClass('error');
                }
                if(obj.id_province == null) {
                    $('#input-edit-address-province').addClass('error');
                }
                if(!btn.hasClass('disabled') && $('#popup-edit-address .error').length == 0) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: PUBLIC_PATH + '/save-edit-address',
                        data: obj,
                        success: function(data) {
                            if(data.save_edit_address.response == 'ok') {
                                APP.getAddresses();
                                UTILS.closePopup('#popup-edit-address');
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
            $('#btn-new-billing-address').on('click', function() {
                var btn = $(this);
                var obj = {
                    name: $('#input-new-billing-address-name').val().trim(),
                    lastname: $('#input-new-billing-address-lastname').val().trim(),
                    id_continent: $('#input-new-billing-address-continent').val(),
                    id_country: $('#input-new-billing-address-country').val(),
                    id_province: $('#input-new-billing-address-province').val(),
                    address: $('#input-new-billing-address-address').val().trim(),
                    location: $('#input-new-billing-address-location').val().trim(),
                    postal_code: $('#input-new-billing-address-postal-code').val().trim(),
                    telephone: $('#input-new-billing-address-telephone').val().trim(),
                    main: ($('#checkbox-new-billing-address-main:checked').val() == undefined) ? 0 : 1
                }
                $('#popup-new-billing-address *').removeClass('error');
                if(!UTILS.validate('name', obj.name)) {
                    $('#input-new-billing-address-name').addClass('error');
                }
                if(!UTILS.validate('lastname', obj.lastname)) {
                    $('#input-new-billing-address-lastname').addClass('error');
                }
                if(obj.id_country == null) {
                    $('#input-new-billing-address-country').addClass('error');
                }
                if(obj.id_province == null) {
                    $('#input-new-billing-address-province').addClass('error');
                }
                if(!btn.hasClass('disabled') && $('#popup-new-billing-address .error').length == 0) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: PUBLIC_PATH + '/save-new-billing-address',
                        data: obj,
                        success: function(data) {
                            if(data.save_new_billing_address.response == 'ok') {
                                APP.getBillingAddresses();
                                UTILS.closePopup('#popup-new-billing-address');
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
            $('#btn-edit-billing-address').on('click', function() {
                var btn = $(this);
                var obj = {
                    id_user_billing_address: $('#popup-edit-billing-address').attr('id-user-billing-address'),
                    name: $('#input-edit-billing-address-name').val().trim(),
                    lastname: $('#input-edit-billing-address-lastname').val().trim(),
                    id_continent: $('#input-edit-billing-address-continent').val(),
                    id_country: $('#input-edit-billing-address-country').val(),
                    id_province: $('#input-edit-billing-address-province').val(),
                    address: $('#input-edit-billing-address-address').val().trim(),
                    location: $('#input-edit-billing-address-location').val().trim(),
                    postal_code: $('#input-edit-billing-address-postal-code').val().trim(),
                    telephone: $('#input-edit-billing-address-telephone').val().trim(),
                    main: ($('#checkbox-edit-billing-address-main:checked').val() == undefined) ? 0 : 1
                }
                $('#popup-edit-billing-address *').removeClass('error');
                if(!UTILS.validate('name', obj.name)) {
                    $('#input-edit-billing-address-name').addClass('error');
                }
                if(!UTILS.validate('lastname', obj.lastname)) {
                    $('#input-edit-billing-address-lastname').addClass('error');
                }
                if(obj.id_country == null) {
                    $('#input-edit-billing-address-country').addClass('error');
                }
                if(obj.id_province == null) {
                    $('#input-edit-billing-address-province').addClass('error');
                }
                if(!btn.hasClass('disabled') && $('#popup-edit-billing-address .error').length == 0) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: PUBLIC_PATH + '/save-edit-billing-address',
                        data: obj,
                        success: function(data) {
                            if(data.save_edit_billing_address.response == 'ok') {
                                APP.getBillingAddresses();
                                UTILS.closePopup('#popup-edit-billing-address');
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
            $('#input-check-billing').on('click', function() {
                let check = ($('#input-check-billing:checked').val() == undefined) ? false : true;
                if(check == true) {
                    $('#billing-content').addClass('hidden');
                } else {
                    $('#billing-content').removeClass('hidden');
                    APP.getBillingAddresses();
                }
            });
            $('#input-checkout-code').on('click', function() {
                let check = ($('#input-checkout-code:checked').val() == undefined) ? false : true;
                if(check == true) {
                    $('#code-content').removeClass('hidden');
                } else {
                    $('#code-content').addClass('hidden');
                }
            });
            $('#btn-apply-code').on('click', function() {
                var btn = $(this);
                var obj = {
                    code: $('#input-code').val().trim()
                }
                if(!btn.hasClass('disabled')) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: PUBLIC_PATH + '/apply-code',
                        data: obj,
                        success: function(data) {
                            if(data.apply_code.response == 'ok') {
                            }
                            UTILS.showInfo(data.apply_code.title, data.apply_code.description);
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
            $('#btn-checkout-payment').on('click', function() {
                var btn = $(this);
                var obj = {
                    id_user_address: null,
                    id_user_billing_address: null,
                    comment: $('#textarea-comment').val().trim(),
                    payment_method: $('.input-radio-payment').val()
                }
                // I check that you have an address selected
                if($('#address-list .item.active').length == 1) {
                    obj.id_user_address = parseInt($('#address-list .item.active').attr('id-user-address'));
                } else {
                    UTILS.showInfo(APP_DATA.shippingAddressErrorTitle, APP_DATA.shippingAddressErrorText);
                    return;
                }
                let check_billing = ($('#input-check-billing').val() == undefined) ? false : true;
                if(check_billing == true) {
                    // I check that you have an billing address selected
                    if($('#billing-list .item.active').length == 1) {
                        obj.id_user_billing_address = parseInt($('#billing-list .item.active').attr('id-user-billing-address'));
                    } else {
                        UTILS.showInfo(APP_DATA.shippingAddressErrorTitle, APP_DATA.shippingAddressErrorText);
                        return;    
                    }
                }
                if(!btn.hasClass('disabled')) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: PUBLIC_PATH + '/save-order-to-cart',
                        data: obj,
                        success: function(data) {
                            if(data.save_order_to_cart.response == 'ok') {
                                btn.addClass('btn-ok');
                            } else {
                                btn.removeClass('disabled');
                            }
                        }
                    });
                }
            });
        }    
    },
    newAddressEvents: function() {
        // Controls address and billing address popup events
        $('#input-new-address-continent').on('change', function() {
            var obj = {
                id_continent: $(this).val()
            };
            $.ajax({
                url: PUBLIC_PATH + '/get-countries-list',
                data: obj,
                success: function(data) {
                    if(data.get_countries_list.response == 'ok') {
                        $('#input-new-address-country').html(data.get_countries_list.html);
                        $('#input-new-address-province').html('');
                    }
                }
            });
        });
        $('#input-new-address-country').on('change', function() {
            var obj = {
                id_country: $(this).val()
            };
            $.ajax({
                url: PUBLIC_PATH + '/get-provinces-list',
                data: obj,
                success: function(data) {
                    if(data.get_provinces_list.response == 'ok') {
                        $('#input-new-address-province').html(data.get_provinces_list.html);
                    }
                }
            });
        });
    },
    editAddressEvents: function() {
        $('#input-edit-address-continent').on('change', function() {
            var obj = {
                id_continent: $(this).val()
            };
            $.ajax({
                url: PUBLIC_PATH + '/get-countries-list',
                data: obj,
                success: function(data) {
                    if(data.get_countries_list.response == 'ok') {
                        $('#input-edit-address-country').html(data.get_countries_list.html);
                        $('#input-edit-address-province').html('');
                    }
                }
            });
        });
        $('#input-edit-address-country').on('change', function() {
            var obj = {
                id_country: $(this).val()
            };
            $.ajax({
                url: PUBLIC_PATH + '/get-provinces-list',
                data: obj,
                success: function(data) {
                    if(data.get_provinces_list.response == 'ok') {
                        $('#input-edit-address-province').html(data.get_provinces_list.html);
                    }
                }
            });
        });    
    },
    newBillingAddressEvents: function() {
        $('#input-new-billing-address-continent').on('change', function() {
            var obj = {
                id_continent: $(this).val()
            };
            $.ajax({
                url: PUBLIC_PATH + '/get-countries-list',
                data: obj,
                success: function(data) {
                    if(data.get_countries_list.response == 'ok') {
                        $('#input-new-billing-address-country').html(data.get_countries_list.html);
                        $('#input-new-billing-address-province').html('');
                    }
                }
            });
        });
        $('#input-new-billing-address-country').on('change', function() {
            var obj = {
                id_country: $(this).val()
            };
            $.ajax({
                url: PUBLIC_PATH + '/get-provinces-list',
                data: obj,
                success: function(data) {
                    if(data.get_provinces_list.response == 'ok') {
                        $('#input-new-billing-address-province').html(data.get_provinces_list.html);
                    }
                }
            });
        });    
    },
    editBillingAddressEvents: function() {
        $('#input-edit-billing-address-continent').on('change', function() {
            var obj = {
                id_continent: $(this).val()
            };
            $.ajax({
                url: PUBLIC_PATH + '/get-countries-list',
                data: obj,
                success: function(data) {
                    if(data.get_countries_list.response == 'ok') {
                        $('#input-edit-billing-address-country').html(data.get_countries_list.html);
                        $('#input-edit-billing-address-province').html('');
                    }
                }
            });
        });
        $('#input-edit-billing-address-country').on('change', function() {
            var obj = {
                id_country: $(this).val()
            };
            $.ajax({
                url: PUBLIC_PATH + '/get-provinces-list',
                data: obj,
                success: function(data) {
                    if(data.get_provinces_list.response == 'ok') {
                        $('#input-edit-billing-address-province').html(data.get_provinces_list.html);
                    }
                }
            });
        });    
    },
    closeCartEvent: function() {
        $('#btn-show-cart').on('click', function() {
            APP.getPopupCart();
        });    
    },
    showCartEvent: function() {
        $('#btn-close-cart').on('click', function() {
            $('#popup-cart').removeClass('active');
        });
    }
}

$(window).ready(function() {
    APP.init();
});
