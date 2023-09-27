/*
* Author: Diego Martin
* Copyright: Hive®
* Version: 1.0
* Last Update: 2023
*/   

var _loading = false;

$(window).ready(function() {
    init();
    scroll();
    events();
    events_product();
    events_checkout();
});

$(window).scroll(function() {
    scroll();
});

function init() {
    $.ajaxSetup({
    	type: 'POST',
    	dataType: "json",
    	error: function() {
    	    show_info('Error', 'An unexpected error has occurred.<br>Reload the page to try again.');
            _loading = false;
    	}
    });
    $('#hive-slider-example').slick({
        dots: true,
        autoplay: true,
        autoplaySpeed: 2000,
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1
    });
}

function scroll() {
    var scroll = $(window).scrollTop();
    var alto = $("#popup-loading").height();
	if((alto + scroll) >= alto + 350) {
		$("header").addClass("float");
	} else {
		$("header").removeClass("float");
	}
    if(scroll > (alto / 2)) {
		$("#back-top").fadeIn();
	} else {
		$("#back-top").fadeOut();
	}
	$('.animate').each(function() {
	    var active = false;
        if(($(this).offset().top + $(this).height()) <= (alto + scroll) && ($(this).offset().top + $(this).height()) >= scroll && !$(this).hasClass('active')) {
            active = true;
        }
        if(($(this).offset().top + 10) <= (alto + scroll) && ($(this).offset().top + 10) >= scroll && !$(this).hasClass('active')) {
            active = true;
        }
        if(($(this).offset().top + $(this).height()) > (alto + scroll) && $(this).offset().top < scroll) {
            active = true;
        }
        if(active) {
            $(this).addClass("active");
        }
	});
}

function events() {
    $('#btn-show-cart').on('click', function() {
        get_popup_cart();
    });
    $('#btn-close-cart').on('click', function() {
        $('#popup-cart').removeClass('active');
    });    
    $("#btn-change-color-mode > input").on("click", function() {
        var self = this;
        if(!$(this).hasClass('disabled')) {
            $(this).addClass('disabled');
            $(this).prop('disabled', true);
            _loading = true;
            var obj = {
                mode: ''
            }
            if($('#btn-change-color-mode > input:checked').val() != undefined) {
                $('body').addClass('dark-mode');
                obj.mode = 'dark-mode';
            } else {
                $('body').removeClass('dark-mode');
                obj.mode = 'light-mode';
            }
            $.ajax({
                url: PUBLIC_PATH + '/choose-color-mode',
                data: obj,
                success: function(data) {
                    $(self).prop('disabled', false);
                    $(self).removeClass('disabled');
                    _loading = false;
                }
            });    
        }
    });
    
    $("#btn-acepta-cookies").on("click", function() {
        var self = this;
        var obj = {
            dato: true
        }
        if(!$(this).hasClass('disabled')) {
            $(this).addClass('disabled');
            _loading = true;
            $.ajax({
                url: PUBLIC_PATH + '/set-cookies',
                data: obj,
                success: function(data) {
                    if(data.cookie.response == 'ok') {
                        $('#popup-cookies').removeClass('active');
                    } else {
                        $(self).removeClass('disabled');
                    }
                    _loading = false;
                }
            });
        }
    });

    $("#btn-send-newsletter").on("click", function() {
        var self = this;
        var obj = {
            email: $('#input-send-newsletter').val().trim()
        }
        $('.newsletter-content *').removeClass('error');
        if(!validar('email', obj.email)) {
            $('#input-send-newsletter').addClass('error');
        }
        if($('#checkbox-send-newsletter:checked').val() == undefined) {
            $('#checkbox-send-newsletter').addClass('error');
        }
        if(!$(this).hasClass('disabled') && $('.newsletter-content .error').length == 0) {
            $(this).addClass('disabled');
            _loading = true;
            $.ajax({
                url: PUBLIC_PATH + '/save-newsletter',
                data: obj,
                success: function(data) {
                    if(data.newsletter.response == 'ok') {
                        show_info(data.newsletter.title, data.newsletter.description);
                    } else {
                        $(self).removeClass('disabled');
                    }
                    _loading = false;
                }
            });    
        }
    });

    $("#btn-send-login").on("click", function() {
        var self = this;
        var obj = {
            email: $('#input-login-email').val().trim(),
            pass: $('#input-login-pass').val().trim(),
            remember: ($('#checkbox-login-remember:checked').val() == undefined) ? 0 : 1,
            checkout: $('#input-checkout').val()
        }
        $('.login-content *').removeClass('error');
        if(!validar('email', obj.email)) {
            $('#input-login-email').addClass('error');
        }
        if(obj.pass == '' || obj.pass.length < 8) {
            $('#input-login-pass').addClass('error');
        }
        if(!$(this).hasClass('disabled') && $('.login-content .error').length == 0) {
            $(this).addClass('disabled');
            _loading = true;
            $.ajax({
                url: PUBLIC_PATH + '/login-send',
                data: obj,
                success: function(data) {
                    if(data.login.response == 'ok') {
                        window.location.href = data.login.url;
                    } else {
                        show_info('Uups', data.login.mensaje);
                        $(self).removeClass('disabled');
                        _loading = false;
                    }
                }
            });    
        }
    });

    $("#btn-send-register").on("click", function() {
        var self = this;
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
        if(!validar('email', obj.email)) {
            $('#input-register-email').addClass('error');
        }
        if(!validar('nombre', obj.name)) {
            $('#input-register-name').addClass('error');
        }
        if(!validar('nombre', obj.lastname)) {
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
        if(!$(this).hasClass('disabled') && $('.register-content .error').length == 0) {
            $(this).addClass('disabled');
            _loading = true;
            $.ajax({
                url: PUBLIC_PATH + '/register-send',
                data: obj,
                success: function(data) {
                    if(data.register.response == 'ok') {
                        window.location.href = data.register.url;
                    } else {
                        show_info('Uups', data.register.mensaje);
                        $(self).removeClass('disabled');
                        _loading = false;
                    }
                }
            });    
        }
    });
    
    $("#select-choose-language").on("change", function() {
        var self = this;
        var obj = {
            language: $(this).val(),
            route: ROUTE,
            language_route: $("option:selected", this).attr("route")
        }
        if(!$(this).hasClass('disabled')) {
            $(this).addClass('disabled');
            $(this).prop('disabled', true);
            _loading = true;
            $.ajax({
                url: PUBLIC_PATH + '/choose-language',
                data: obj,
                success: function(data) {
                    if(data.language.response == 'ok') {
                        if(obj.language_route == '') {
                            window.location.href = data.language.route;
                        } else {
                            window.location.href = data.language.language_route;
                        }
                    } else {
                        $(self).prop('disabled', false);
                        $(self).removeClass('disabled');
                        _loading = false;
                    }
                }
            });    
        }
    });
}

function events_product() {
    if($('body#product-page').length != 0) {
        get_product_related();
        $('.content-attributes .item:not(.disabled)').on('click', function() {
            if(!$(this).hasClass('active')) {
                var btn = $(this);
                btn.closest('.content-attribute').find('.item.active').removeClass('active');
                btn.addClass('active');
                if(!$('.content-attributes').hasClass('disabled')) {
                    get_product_related();
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
                _loading = true;
                $.ajax({
                    url: PUBLIC_PATH + '/add-cart',
                    data: obj,
                    success: function(data) {
                        if(data.add_cart.response == 'ok') {
                            get_popup_cart();
                        }
                        btn.removeClass('disabled');
                        _loading = false;
                    }
                });    
            }    
        });
        $('#btn-notify-stock').on('click', function() {
            var btn = $(this);
            var obj = {
                id_product: $('#input-id-product').val(),
                id_product_related: $('#input-id-product-related').val()
            }
            if(!btn.hasClass('disabled')) {
                btn.addClass('disabled');
                _loading = true;
                $.ajax({
                    url: PUBLIC_PATH + '/notify-stock',
                    data: obj,
                    success: function(data) {
                        if(data.notify_stock.response == 'ok') {
                            if(data.notify_stock.popup == true) {
                                $('#input-notify-stock-name').val('');
                                $('#input-notify-stock-email').val('');
                                $('#popup-notify-stock *').removeClass('error');
                                show_popup('#popup-notify-stock');
                            } else {
                                show_popup('#popup-notify-stock-info');
                            }
                        }
                        btn.removeClass('disabled');
                        _loading = false;
                    }
                });    
            }    
        });
        $('#btn-send-notify-stock').on('click', function() {
            var btn = $(this);
            var obj = {
                id_product: $('#input-id-product').val(),
                id_product_related: $('#input-id-product-related').val(),
                name: $('#input-notify-stock-name').val().trim(),
                email: $('#input-notify-stock-email').val().trim()
            }
            $('#popup-notify-stock *').removeClass('error');
            if(!validar('nombre', obj.name)) {
                $('#input-notify-stock-name').addClass('error');
            }
            if(!validar('email', obj.email)) {
                $('#input-notify-stock-email').addClass('error');
            }
            if(!btn.hasClass('disabled') && $('#popup-notify-stock .error').length == 0) {
                btn.addClass('disabled');
                _loading = true;
                $.ajax({
                    url: PUBLIC_PATH + '/send-notify-stock',
                    data: obj,
                    success: function(data) {
                        if(data.send_notify_stock.response == 'ok') {
                            close_popup('#popup-notify-stock');
                            show_info('Correct!', data.send_notify_stock.mensaje);
                        }
                        btn.removeClass('disabled');
                        _loading = false;
                    }
                });    
            }    
        });
    }
}

function events_checkout() {
    if($('body#checkout-page').length != 0) {
        get_address();
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
        $('#btn-create-address').on('click', function() {
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
                telephone: $('#input-new-address-telephone').val().trim()
            }
            console.log(obj);
            $('#popup-new-address *').removeClass('error');
            if(!validar('nombre', obj.name)) {
                $('#input-new-address-name').addClass('error');
            }
            if(!validar('apellidos', obj.lastname)) {
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
                _loading = true;
                $.ajax({
                    url: PUBLIC_PATH + '/save-new-address',
                    data: obj,
                    success: function(data) {
                        if(data.save_new_address.response == 'ok') {
                            close_popup('#popup-new-address');
                        }
                        btn.removeClass('disabled');
                        _loading = false;
                    }
                });
            }
        });
    }
}

function get_product_related() {
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
            scroll();
        }
    });
}

function get_popup_cart() {
    $.ajax({
        url: PUBLIC_PATH + '/get-popup-cart',
        data: {},
        success: function(data) {
            if(data.get_popup_cart.response == 'ok') {
                $('#label-popup-cart-total').html(data.get_popup_cart.total);
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
                                    get_popup_cart();
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
                    if(!validar('number', obj.amount)) {
                        btn.addClass('error');
                    }
                    if(!btn.hasClass('disabled') && !btn.hasClass('error')) {
                        btn.addClass('disabled');
                        $.ajax({
                            url: PUBLIC_PATH + '/change-product-amount',
                            data: obj,
                            success: function(data) {
                                if(data.change_product_amount.response == 'ok') {
                                    get_popup_cart();
                                    btn.removeClass('disabled');
                                }
                            }
                        });
                    }
                });
                $('#popup-cart').addClass('active');
            }
        }
    });    
}

function get_address() {
    $.ajax({
        url: PUBLIC_PATH + '/get-address',
        data: {},
        success: function(data) {
            if(data.get_address.response == 'ok') {
                $('#address-list').html(data.get_address.html);
                $('#btn-create-new-address').on('click', function() {
                    $('input').val('');
                    show_popup('#popup-new-address');
                });
            }
        }
    });
}