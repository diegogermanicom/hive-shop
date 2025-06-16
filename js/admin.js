/*
* Author: Diego Martin
* Copyright: Hive®
* Version: 1.0
* Last Update: 2024
*/   

var ADMIN = {
    // Init
    init: function() {
        this.menuLeftEvents();
        this.sendLoginEvent();
        this.sitemapEvent();
        this.productsEvent();
        this.productsCustomRoutesEvents();
        this.newProductEvents();
        this.editProductEvents();
        this.categoriesEvent();
        this.categoriesCustomRoutesEvents();
        this.newCategoryEvent();
        this.editCategoryEvent();
        this.attributesEvent();     
        this.newAttributeEvent();
        this.editAttributeEvents();
        this.imagesEvent();
        this.codesEvent();
        this.newCodeEvent();
        this.editCodeEvent();
        this.newPaymentEvent();
        this.editPaymentEvent();
        this.newPaymentZoneEvent();
        this.editPaymentZoneEvent();
        this.usersEvent();
        this.editUserEvents();
        this.adminUsersEvent();
        this.newAdminUserEvent();
        this.editAdminUserEvents();
    },
    // Functions not related to page events
    editProductRelatedEvents: function() {
        // Delete a related product
        $('.btn-delete-related').off().on("click", function() {
            var btn = $(this);
            var obj = {
                id_product_related: parseInt(btn.attr('id-product-related'))
            }
            if(!btn.hasClass('disabled')) {
                btn.addClass('disabled');
                $.ajax({
                    url: ADMIN_PATH + '/delete-related',
                    data: obj,
                    success: function(data) {
                        if(data.delete_related.response == 'ok') {
                            // If it is the last related, I delete the table
                            if($('#products-related tbody > tr').length == 1) {
                                $('#products-related').html('No related products');
                            } else {
                                if(data.delete_related.id_main != null) {
                                    $('#products-related input[value="' + data.delete_related.id_main + '"]').prop("checked", true);
                                }
                                btn.closest('tr').remove();
                            }
                        } else {
                            UTILS.showInfo('Uups', 'An unexpected error has occurred.<br>Reload the page to try again.');
                        }
                        btn.removeClass('disabled');
                    }
                });
            }
        });
        // Opens the edit popup of a related product
        $('.btn-edit-related').on("click", function() {
            var btn = $(this);
            var obj = {
                id_product_related: parseInt(btn.attr('id-product-related'))
            }
            if(!btn.hasClass('disabled')) {
                btn.addClass('disabled');
                $.ajax({
                    url: ADMIN_PATH + '/get-edit-related',
                    data: obj,
                    success: function(data) {
                        if(data.get_edit_related.response == 'ok') {
                            $('#popup-edit-related .content-related').html(data.get_edit_related.html);
                            $('#btn-save-edit-related').attr('id-product-related', obj.id_product_related);
                            $('#popup-edit-related .content-images .item-image').on("click", function() {
                                if($(this).hasClass('selected')) {
                                    $(this).removeClass('selected');
                                } else {
                                    $(this).addClass('selected');
                                }
                            });
                            UTILS.showPopup('#popup-edit-related');
                        }
                        btn.removeClass('disabled');
                    }
                });
            }            
        });
    },
    newEditAttributeEvents: function() {
        // Enable drag functionality
        $('#attribute-values-list').sortable({
            group: 'list',
            animation: 200,
            ghostClass: 'ghost'
        });
        $('#select-type').on('change', function() {
            let type = $('#select-type').val();
            $('#input-value-alias').val('');
            $('#input-property-color').val('');
            $('#upload-image').attr('image-name', '');
            $('#upload-image').css('background-image', '');
            $('.content-new-value *').removeClass('error');
            $('#value-property-color').addClass('hidden');
            $('#value-property-image').addClass('hidden');
            if(type == 2) {
                $('#value-property-color').removeClass('hidden');
            } else if(type == 3) {
                $('#value-property-image').removeClass('hidden');
            }
            $('#attribute-values-list').html('');
        });
        $('#input-value-property-image').on('change', function() {
            var file = this.files[0];
            let types = ['image/jpg', 'image/jpeg', 'image/png'];
            if(this.files) {
                if(types.includes(file.type)) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        if(event.total <= 1000000) { // Size of file
                            $('#upload-image').attr('image-name', file.name);
                            $('#upload-image').css('background-image', 'url(' + event.target.result + ')');
                        } else {
                            UTILS.showInfo('Error', 'Image size cannot be larger than 1 mb.');
                        }
                    }
                    reader.readAsDataURL(file);
                }
                this.value = '';
            }
        });            
        $('#btn-add-attribute-value').on("click", function() {
            let type = $('#select-type').val();
            let obj = {
                alias: $('#input-value-alias').val().trim(),
                property: ''
            }
            if(type == 2) {
                obj.property = $('#input-value-property-color').val().trim();
            } else if(type == 3) {
                obj.property = {
                    data: $('#upload-image').css('background-image'),
                    name: $('#upload-image').attr('image-name')
                };
                obj.property.data = obj.property.data.substring(5);
                obj.property.data = obj.property.data.substring(0, obj.property.data.length - 2);
            }
            $('.content-new-value *').removeClass('error');
            if(obj.alias == '') {
                $('#input-value-alias').addClass('error');
            }
            if(type == 2 && !UTILS.validate('hexadecimal', obj.property)) {
                $('#input-value-property-color').addClass('error');
            } else if(type == 3 && obj.property.name == '') {
                $('#upload-image').addClass('error');
            }
            if($('.content-new-value .error').length == 0) {
                // I draw the fields according to the type of value
                let html = '';
                html += '<div class="list-item" value="' + obj.alias + '" id-attribute-value="0">' + obj.alias;
                if(type == 2) {
                    html += ' <div class="value-color" value="'+ obj.property + '" style="background-color: '+ obj.property + ';">'+ obj.property + '</div>';
                } else if(type == 3) {
                    html += '<div class="new-value-list-image" style="background-image: url(' + obj.property.data + ')" title="'+ obj.property.name + '"></div>';
                }
                html +=     '<div class="btn-delete-value"><i class="fa-solid fa-trash-can"></i></div>';
                html +=     '<div class="btn-edit-value disabled"><i class="fa-solid fa-pencil"></i></div>';
                html += '</div>';
                $('#input-value-alias').val('');
                $('#input-value-property-color').val('');
                $('#upload-image').attr('image-name', '');
                $('#upload-image').css('background-image', '');
                $('#attribute-values-list').append(html);
                $('.btn-delete-value').off().on("click", function() {
                    $(this).closest('.list-item').remove();
                });
            }
        });
    },
    getProductImages: function(id_product) {
        var obj = {
            id_product: id_product
        }
        $.ajax({
            url: ADMIN_PATH + '/get-product-images',
            data: obj,
            success: function(data) {
                if(data.get_product_images.response == 'ok') {
                    $('#upload-images').html(data.get_product_images.html);
                    $('#upload-images .item-image .btn-item-image-delete').on('click', function() {
                        let parent = $(this).closest('.item-image');            
                        $('#popup-delete-image').attr('id-product-image', parent.attr('id-product-image'));
                        $('#popup-delete-image').attr('id-image', parent.attr('id-image'));
                        UTILS.showPopup('#popup-delete-image');
                    });
                    $('#upload-images .btn-item-image-main').on('click', function() {
                        var btn = $(this);
                        let parent = $(this).closest('.item-image');            
                        var obj = {
                            id_product: id_product,
                            id_product_image: parseInt(parent.attr('id-product-image'))
                        }
                        if(!btn.hasClass('disabled')) {
                            // I disable all buttons of this type
                            $('#upload-images .btn-item-image-main').addClass('disabled');
                            $.ajax({
                                url: ADMIN_PATH + '/save-product-main-image',
                                data: obj,
                                success: function(data) {
                                    if(data.save_product_main_image.response == 'ok') {
                                        ADMIN.getProductImages(id_product);
                                        UTILS.showInfo('Correct!', data.save_product_main_image.mensaje);                                
                                    }
                                    $('#upload-images .btn-item-image-main').removeClass('disabled');
                                }
                            });    
                        }
                    });
                    $('#upload-images .btn-item-image-hover').on('click', function() {
                        var btn = $(this);
                        let parent = $(this).closest('.item-image');            
                        var obj = {
                            id_product: id_product,
                            id_product_image: parseInt(parent.attr('id-product-image'))
                        }
                        if(!btn.hasClass('disabled')) {
                            $('#upload-images .btn-item-image-hover').addClass('disabled');
                            $.ajax({
                                url: ADMIN_PATH + '/save-product-hover-image',
                                data: obj,
                                success: function(data) {
                                    if(data.save_product_hover_image.response == 'ok') {
                                        ADMIN.getProductImages(id_product);
                                        UTILS.showInfo('Correct!', data.save_product_hover_image.mensaje);                                
                                    }
                                    $('#upload-images .btn-item-image-hover').removeClass('disabled');
                                }
                            });
                        }
                    });
                }
            }
        });    
    },
    getAttributeValues: function(id_attribute) {
        var obj = {
            id_attribute: id_attribute,
            type: $('#select-type').val()
        }
        $.ajax({
            url: ADMIN_PATH + '/get-attribute-values',
            data: obj,
            success: function(data) {
                if(data.get_attribute_values.response == 'ok') {
                    $('#attribute-values-list').html('');
                    $('#attribute-values-list').html(data.get_attribute_values.html)
                    $('.btn-delete-value').off().on("click", function() {
                        $(this).closest('.list-item').remove();
                    });
                    $('.btn-edit-value').on("click", function() {
                        var id_attribute_value = $(this).closest('.list-item').attr('id-attribute-value');
                        var obj_values = {
                            id_attribute_value: id_attribute_value
                        }
                        $.ajax({
                            url: ADMIN_PATH + '/get-attribute-value-properties',
                            data: obj_values,
                            success: function(data) {
                                if(data.get_attribute_value_properties.response == 'ok') {
                                    $('#popup-attribute-value-properties').attr('id-attribute-value', id_attribute_value)
                                    $('#input-edit-value-alias').val(data.get_attribute_value_properties.alias);
                                    $('#value-properties-content').html(data.get_attribute_value_properties.html);
                                    UTILS.customTabEvent();
                                    UTILS.showPopup('#popup-attribute-value-properties');
                                } else {
                                    UTILS.showInfo('Error', data.get_attribute_value_properties.mensaje);
                                }
                            }
                        });
                    });
                }
            }
        });    
    },
    getRelated: function(id_product) {
        var obj = {
            id_product: id_product
        }
        $.ajax({
            url: ADMIN_PATH + '/get-related',
            data: obj,
            success: function(data) {
                if(data.get_related.response == 'ok') {
                    $('#products-related').html(data.get_related.html);
                    ADMIN.editProductRelatedEvents();
                }
            }
        });    
    },
    getUserAddresses: function(id_user) {
        var obj = {
            id_user: id_user
        };
        $.ajax({
            url: ADMIN_PATH + '/get-user-addresses',
            data: obj,
            success: function(data) {
                if(data.get_user_addresses.response == 'ok') {
                    $('#content-addresses').html(data.get_user_addresses.html);
                    $('.btn-edit-user-address').on("click", function() {
                        var btn = $(this);
                        var obj = {
                            id_user_address: parseInt(btn.attr('id-user-address'))
                        };
                        if(!btn.hasClass('disabled')) {
                            btn.addClass('disabled');
                            // I collect the address data
                            $.ajax({
                                url: ADMIN_PATH + '/get-user-address',
                                data: obj,
                                success: function(data) {
                                    if(data.get_user_address.response == 'ok') {
                                        let address = data.get_user_address.address;
                                        $('#btn-save-edit-address').attr('id-user-address', address.id_user_address);
                                        $('#input-edit-address-continent').val(address.id_continent);
                                        $('#input-edit-address-country').html(data.get_user_address.countries_html);
                                        $('#input-edit-address-province').html(data.get_user_address.provinces_html);
                                        $('#input-edit-address-address').val(address.address);
                                        $('#input-edit-address-location').val(address.location);
                                        $('#input-edit-address-postal-code').val(address.postal_code);
                                        $('#input-edit-address-telephone').val(address.telephone);
                                        UTILS.showPopup('#popup-edit-address');
                                    }
                                    btn.removeClass('disabled');
                                }
                            });
                        }
                    });
                    $('#input-edit-address-continent').on('change', function() {
                        var obj = {
                            id_continent: parseInt($(this).val())
                        };
                        $('#input-edit-address-country').html('');
                        $.ajax({
                            url: ADMIN_PATH + '/get-countries-list',
                            data: obj,
                            success: function(data) {
                                if(data.get_countries_list.response == 'ok') {
                                    $('#input-edit-address-country').html(data.get_countries_list.html);
                                }
                            }
                        });    
                    });
                    $('#input-edit-address-country').on('change', function() {
                        var obj = {
                            id_country: parseInt($(this).val())
                        };
                        $('#input-edit-address-province').html('');
                        $.ajax({
                            url: ADMIN_PATH + '/get-provinces-list',
                            data: obj,
                            success: function(data) {
                                if(data.get_provinces_list.response == 'ok') {
                                    $('#input-edit-address-province').html(data.get_provinces_list.html);
                                }
                            }
                        });    
                    });
                    $('.btn-delete-user-address').on("click", function() {
                        var btn = $(this);
                        var obj = {
                            id_user_address: parseInt(btn.attr('id-user-address'))
                        };
                        if(!btn.hasClass('disabled')) {
                            btn.addClass('disabled');
                            $.ajax({
                                url: ADMIN_PATH + '/delete-user-address',
                                data: obj,
                                success: function(data) {
                                    if(data.delete_user_address.response == 'ok') {
                                        btn.closest('tr').remove();
                                    } else {
                                        UTILS.showInfo('Uups', 'An unexpected error has occurred.<br>Reload the page to try again.');
                                    }
                                    btn.removeClass('disabled');
                                }
                            });
                        }
                    });        
                }
            }
        });    
    },
    refreshCategoryList: function(id_main) {
        var html = '';
        $('#category-list-2 .list-item.active').each(function() {
            let id_category = parseInt($(this).attr('value'));
            if(id_category == id_main) {
                checked = ' checked';
            } else {
                checked = '';
            }
            html += '<div class="list-item no-hover no-click" value="' + id_category + '">' + $(this).html();
            html +=     '<label class="radio" for="category-' + id_category + '"><input type="radio" name="radio-category-list-1" id="category-' + id_category + '" value="' + id_category + '"' + checked + '><span class="checkmark"></span></label>';
            html += '</div>';
        });
        $('#category-list-1').html(html);    
    },
    loadNewProductImagen: function(image) {
        var name = image.name;
        let reader = new FileReader();
        reader.onload = function(event) {
            if(event.total <= 2000000) { // Size of file
                let img_html = '<div class="item-image new-image" style="background-image: url(' + event.target.result + ');" image-name="' + name + '">';
                img_html +=     '<div class="item-image-buttons">';
                img_html +=         '<div class="btn-item-image-delete"><i class="fa-solid fa-trash-can"></i> Delete</div>';
                img_html +=     '</div>';
                img_html += '</div>';
                $('#upload-images').append(img_html);
                $('#upload-images .new-image .btn-item-image-delete').off().on('click', function() {
                    $(this).closest('.item-image').remove();
                });
            } else {
                UTILS.showInfo('Error', 'Image size cannot be larger than 5 mb.');
            }
        }
        reader.readAsDataURL(image);    
    },
    objSaveEditProduct: function() {
        var obj = {
            id_product: null,
            alias: $('#input-name').val().trim(),
            price: $('#input-price').val().trim(),
            weight: $('#input-weight').val().trim(),
            id_view: parseInt($('#select-view').val()),
            id_state: parseInt($('#select-state').val()),
            categories: [],
            main_category: 0,
            attributes: [],
            properties: [],
            meta_data: [],
            images: [],
            id_related_main: null
        }
        $('#category-list-1 .list-item').each(function() {
            obj.categories.push(parseInt($(this).attr('value')));
        });
        obj.main_category = parseInt($('input[name="radio-category-list-1"]:checked').val());
        $('#attribute-list-1 .list-item').each(function() {
            obj.attributes.push(parseInt($(this).attr('value')));
        });
        $('#properties .menu > div').each(function() {
            let id_tab = $(this).attr('id-tab');
            let content = $(this).closest('#properties').find('.content > div[id-tab="' + id_tab + '"]');
            let data = {
                'id_lang': parseInt($(this).attr('id-lang')),
                'name': $(content).find('.input-language-name').val().trim(),
                'slug': $(content).find('.input-language-slug').val().trim(),
                'description': $(content).find('.textarea-language-description').val().trim()
            }
            obj.properties.push(data);
        });
        $('#meta_data .menu > div').each(function() {
            let id_tab = $(this).attr('id-tab');
            let content = $(this).closest('#meta_data').find('.content > div[id-tab="' + id_tab + '"]');
            let data = {
                'id_lang': parseInt($(this).attr('id-lang')),
                'meta_title': $(content).find('.input-language-meta-title').val().trim(),
                'meta_keywords': $(content).find('.input-language-meta-keywords').val().trim(),
                'meta_description': $(content).find('.textarea-language-meta-description').val().trim()
            }
            obj.meta_data.push(data);
        });
        $('#upload-images .item-image').each(function() {
            if($(this).hasClass('new-image')) {
                if($(this).attr('id-image') == undefined) {
                    let data = $(this).css('background-image');
                    data = data.substring(5);
                    let image = {
                        'type': 'explorer',
                        'data': data.substring(0, data.length - 2),
                        'name': $(this).attr('image-name')
                    }
                    obj.images.push(image);
                } else {
                    let image = {
                        type: 'server',
                        id_image: $(this).attr('id-image')
                    }
                    obj.images.push(image);
                }
            } else {
                let image = {
                    type: 'product',
                    id_product_image: $(this).attr('id-product-image')
                }
                obj.images.push(image);
            }
        });
        return obj;    
    },
    objSaveEditCategory: function() {
        var obj = {
            id_category: null,
            alias: $('#input-alias').val().trim(),
            id_parent: parseInt($('#select-id-parent').val()),
            id_view: parseInt($('#select-view').val()),
            id_state: parseInt($('#select-state').val()),
            properties: [],
            meta_data: []
        }
        $('#properties .menu > div').each(function() {
            let id_tab = $(this).attr('id-tab');
            let content = $(this).closest('#properties').find('.content > div[id-tab="' + id_tab + '"]');
            let data = {
                'id_lang': parseInt($(this).attr('id-lang')),
                'name': $(content).find('.input-language-name').val().trim(),
                'slug': $(content).find('.input-language-slug').val().trim(),
                'description': $(content).find('.textarea-language-description').val().trim()
            }
            obj.properties.push(data);
        });
        $('#meta_data .menu > div').each(function() {
            let id_tab = $(this).attr('id-tab');
            let content = $(this).closest('#meta_data').find('.content > div[id-tab="' + id_tab + '"]');
            let data = {
                'id_lang': parseInt($(this).attr('id-lang')),
                'meta_title': $(content).find('.input-language-meta-title').val().trim(),
                'meta_keywords': $(content).find('.input-language-meta-keywords').val().trim(),
                'meta_description': $(content).find('.textarea-language-meta-description').val().trim()
            }
            obj.meta_data.push(data);
        });
        return obj;    
    },
    objSaveEditAttribute: function() {
        var obj = {
            id_attribute: null,
            alias: $('#input-alias').val().trim(),
            type: parseInt($('#select-type').val()),
            view: parseInt($('#select-view').val()),
            properties: [],
            values: []
        }
        $('#properties .menu > div').each(function() {
            let id_tab = $(this).attr('id-tab');
            let content = $(this).closest('#properties').find('.content > div[id-tab="' + id_tab + '"]');
            let data = {
                'id_lang': parseInt($(this).attr('id-lang')),
                'name': $(content).find('.input-language-name').val().trim(),
                'description': $(content).find('.textarea-language-description').val().trim()
            }
            obj.properties.push(data);
        });
        var value_priority = 1;
        $('#attribute-values-list > div.list-item').each(function() {
            let data = {
                id_attribute_value: parseInt($(this).attr('id-attribute-value')),
                alias: $(this).attr('value'),
                value: '',
                image_name: '',
                priority: value_priority
            }
            if(obj.type == 2) {
                data.value = $(this).find('.value-color').attr('value');
            } else if(obj.type == 3) {
                data.value = $(this).find('.new-value-list-image').css('background-image');
                data.value = data.value.substring(5);
                data.value = data.value.substring(0, data.value.length - 2);
                data.image_name = $(this).find('.new-value-list-image').attr('title');
            }
            obj.values.push(data);
            value_priority++;
        });
        return obj;    
    },
    objSaveValidCode: function() {
        var obj = {
            id_code: null,
            name: $('#input-name').val().trim(),
            code: $('#input-code').val().trim(),
            available: $('#input-available').val().trim(),
            per_user: $('#input-per-user').val().trim(),
            id_state: parseInt($('#select-state').val()),
            type: parseInt($('#select-type').val()),
            amount: $('#input-amount').val().trim(),
            registered: parseInt($('#select-registered').val()),
            exclude: parseInt($('#select-exclude').val()),
            minimum: $('#input-minimum').val().trim(),
            start_date: $('#input-start-date').val().trim(),
            end_date: $('#input-end-date').val().trim(),
            compatible: parseInt($('#select-compatible').val()),
            free_shipping: parseInt($('#select-free-shipping').val())
        }
        $('.content-save-code *').removeClass('error');
        if(!UTILS.validate('min-char-3', obj.name)) {
            $('#input-name').addClass('error');
        }
        if(!UTILS.validate('code', obj.code)) {
            $('#input-code').addClass('error');
        }
        if(!UTILS.validate('number', obj.available)) {
            $('#input-available').addClass('error');
        } else {
            obj.available = parseInt(obj.available);
        }
        if(!UTILS.validate('number', obj.per_user)) {
            $('#input-per-user').addClass('error');
        } else {
            obj.per_user = parseInt(obj.per_user);
        }
        if(!UTILS.validate('price', obj.amount)) {
            $('#input-amount').addClass('error');
        }
        if(!UTILS.validate('price', obj.minimum)) {
            $('#input-minimum').addClass('error');
        }
        if(!UTILS.validate('date', obj.start_date)) {
            $('#input-start-date').addClass('error');
        }
        if(!UTILS.validate('date', obj.end_date)) {
            $('#input-end-date').addClass('error');
        }
        return obj;    
    },
    // Events
    menuLeftEvents: function() {
        $('#btn-hide-menu-left').on('click', function() {
            $(this).removeClass('active');
            $('#btn-show-menu-left').addClass('active');
            $('#menu-left').removeClass('active');
            $('#container-admin').addClass('expand');
        });
        $('#btn-show-menu-left').on('click', function() {
            $(this).removeClass('active');
            $('#btn-hide-menu-left').addClass('active');
            $('#menu-left').addClass('active');
            $('#container-admin').removeClass('expand');
        });    
    },
    sendLoginEvent: function() {
        if($('body#admin-login-page').length != 0) {
            $("#btn-send-login").on("click", function() {
                var btn = $(this);
                var obj = {
                    email: $('#input-email').val().trim(),
                    pass: $('#input-pass').val().trim(),
                    remember: ($('#checkbox-admin-remember:checked').val() == undefined) ? 0 : 1
                }
                $('.login-content *').removeClass('error');
                if(obj.email == '') {
                    $('#input-email').addClass('error');
                }
                if(obj.pass == '') {
                    $('#input-pass').addClass('error');
                }
                if(!btn.hasClass('disabled') && $('.login-content .error').length == 0) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/send-login',
                        data: obj,
                        success: function(data) {
                            if(data.login.response == 'ok') {
                                btn.addClass('btn-ok');
                                window.location.href = ADMIN_PATH + '/home?login';
                            } else {
                                UTILS.showInfo('Uups', data.login.mensaje);
                                btn.removeClass('disabled');
                            }
                        }
                    });
                }
            });
        }    
    },
    sitemapEvent: function() {
        if($('body#admin-sitemap').length != 0) {
            $("#btn-create-sitemap").off().on("click", function() {
                var btn = $(this);
                if(!btn.hasClass('disabled')) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/create-new-sitemap',
                        data: {},
                        success: function(data) {
                            if(data.sitemap.response == 'ok') {
                                btn.addClass('btn-ok');
                                UTILS.showInfo(data.sitemap.title, data.sitemap.message);
                            } else {
                                btn.removeClass('disabled');
                                UTILS.showInfo('Uups', data.sitemap.message);
                            }
                        }
                    });
                }
            });
        }
    },
    productsEvent: function() {
        if($('body#admin-products-page').length == 1) {
            $('.btn-delete-product').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_product: parseInt(btn.attr('id-product'))
                }
                if(!btn.hasClass('disabled')) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/delete-product',
                        data: obj,
                        success: function(data) {
                            if(data.delete_product.response == 'ok') {
                                // If it is the last product, I delete the table
                                if($('#products-content tbody > tr').length == 1) {
                                    $('#products-content').html('No products');
                                } else {
                                    btn.closest('tr').remove();
                                }
                            } else {
                                UTILS.showInfo('Uups', 'An unexpected error has occurred.<br>Reload the page to try again.');
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
        }    
    },
    productsCustomRoutesEvents: function() {
        if($('body#admin-products-custom-routes-page').length == 1) {
            $('#open-popup-new-product-custom-route').on("click", function() {
                $('#products-list').val(0);
                $('#categories-list').html('');
                $('#categories-list').addClass('hidden');
                $('#languages-content input').val('');
                $('#languages-content').addClass('hidden');
                UTILS.showPopup('#popup-new-product-custom-route');
            });
            $('#products-list').on("change", function() {
                var btn = $(this);
                var obj = {
                    id_product: parseInt($('#products-list').val())
                }
                if(!btn.hasClass('disabled')) {
                    btn.addClass('disabled');
                    $('#categories-list').html('');
                    $.ajax({
                        url: ADMIN_PATH + '/get-product-categories-list',
                        data: obj,
                        success: function(data) {
                            if(data.get_product_categories_list.response == 'ok') {
                                let html = '<option value="0">Select a category...</option>';
                                html += data.get_product_categories_list.html;
                                $('#categories-list').html(html);
                                $('#categories-list').removeClass('hidden');
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
            $('#categories-list').on("change", function() {
                $('#languages-content').removeClass('hidden');
            });
            $('#btn-save-new-product-custom-route').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_product: parseInt($('#products-list').val()),
                    id_category: parseInt($('#categories-list').val()),
                    routes: []
                }
                $('#popup-new-product-custom-route *').removeClass('error');
                $('#languages-content .item').each(function() {
                    let temp = {
                        id_language: parseInt($(this).attr('id-language')),
                        route: $(this).find('input').val().trim()
                    };
                    // If the route is empty, I don't validate it
                    if(!UTILS.validate('slug', temp.route) || temp.route == '') {
                        $(this).find('input').addClass('error');
                    }
                    obj.routes.push(temp);
                });
                if(obj.id_product == 0) {
                    $('#products-list').addClass('error');
                }
                if(obj.id_category == 0) {
                    $('#categories-list').addClass('error');
                }
                if(!btn.hasClass('disabled') && $('#popup-new-product-custom-route .error').length == 0) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-new-product-custom-route',
                        data: obj,
                        success: function(data) {
                            if(data.save_new_product_custom_route.response == 'ok') {
                                btn.addClass('btn-ok');
                                window.location.href = ADMIN_PATH + '/products-custom-routes?new';
                            } else {
                                btn.removeClass('disabled');
                            }
                        }
                    });
                }
            });
            $('.btn-delete-product-custom-route').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_product_custom_route: parseInt(btn.attr('id-product-custom-route'))
                }
                if(!btn.hasClass('disabled')) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/delete-product-custom-route',
                        data: obj,
                        success: function(data) {
                            if(data.delete_product_custom_route.response == 'ok') {
                                // If it is the last product, I delete the table
                                if($('#products-custom-routes-content tbody > tr').length == 1) {
                                    $('#products-custom-routes-content').html('No products custom routes');
                                } else {
                                    btn.closest('tr').remove();
                                }
                            } else {
                                UTILS.showInfo('Uups', 'An unexpected error has occurred.<br>Reload the page to try again.');
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
        }    
    },
    newProductEvents: function() {
        if($('body#admin-new-product-page').length == 1) {
            // Enable drag functionality
            $('#attribute-list-1').sortable({
                group: 'list',
                animation: 200,
                ghostClass: 'ghost'
            });            
            $('#upload-images').sortable({
                group: 'list',
                animation: 200,
                ghostClass: 'ghost'
            });            
            $("#category-list-2 .list-item").on("click", function() {
                if($(this).hasClass('active')) {
                    $(this).removeClass('active');
                } else {
                    $(this).addClass('active');
                }
                let id_main = parseInt($('input[name="radio-category-list-1"]:checked').val());
                ADMIN.refreshCategoryList(id_main);
            });
            $("#attribute-list-2 .list-item").on("click", function() {
                let id_attibute = $(this).attr('value');
                if($(this).hasClass('active')) {
                    $(this).removeClass('active');
                    $('#attribute-list-1 .list-item[value="' + id_attibute + '"]').remove();
                } else {
                    let html = '<div class="list-item no-hover" value="' + id_attibute + '">' + $(this).html() + '</div>';
                    $('#attribute-list-1').append(html);
                    $(this).addClass('active');
                }
            });
            // Event when images are selected from the browser
            $("#input-file").on("change", function() {
                let types = ['image/jpg', 'image/jpeg', 'image/png'];
                if(this.files) {
                    if(this.files.length <= 10) {
                        for(i = 0; i < this.files.length; i++) {
                            if(types.includes(this.files[i].type)) {
                                ADMIN.loadNewProductImagen(this.files[i]);
                            }
                        }
                    } else {
                        UTILS.showInfo('Uups', 'You can only upload a maximum of 10 files.');
                    }
                    this.value = '';
                }
            });
            $("#btn-save-new-product").on("click", function() {
                var btn = $(this);
                var obj = ADMIN.objSaveEditProduct();
                // Validation
                $('.content-new-product *').removeClass('error');
                if(!UTILS.validate('min-char-3', obj.alias)) {
                    $('#input-name').addClass('error');
                }
                if(!UTILS.validate('price', obj.price) || obj.price == 0) {
                    $('#input-price').addClass('error');
                }
                if(!UTILS.validate('weight', obj.weight) || obj.weight == 0) {
                    $('#input-weight').addClass('error');
                }
                if(obj.categories.length == 0) {
                    $('#category-list-1').addClass('error');
                }
                if(!Number.isInteger(obj.main_category)) {
                    $('#category-list-1').addClass('error');
                }
                if(!btn.hasClass('disabled') && $('.content-new-product .error').length == 0) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-new-product',
                        data: obj,
                        success: function(data) {
                            if(data.save_new_product.response == 'ok') {
                                btn.addClass('btn-ok');
                                window.location.href = ADMIN_PATH + '/products?new';
                            } else {
                                UTILS.showInfo('Error', data.save_new_product.mensaje);
                                btn.removeClass('disabled');
                            }
                        }
                    });
                }
            });
        }    
    },
    editProductEvents: function() {
        if($('body#admin-edit-product-page').length == 1) {
            let id_product = parseInt($('#input-id-product').val());
            ADMIN.getRelated(id_product);
            ADMIN.getProductImages(id_product);
            // Enable drag functionality
            $('#attribute-list-1').sortable({
                group: 'list',
                animation: 200,
                ghostClass: 'ghost'
            });
            $('#upload-images').sortable({
                group: 'list',
                animation: 200,
                ghostClass: 'ghost'
            });            
            // I paint the list of product categories
            let id_main = parseInt($('#category-list-2 .list-item.main').attr('value'));
            $('#category-list-2 .list-item.main').removeClass('main');
            ADMIN.refreshCategoryList(id_main);
            $("#category-list-2 .list-item").on("click", function() {
                if($(this).hasClass('active')) {
                    $(this).removeClass('active');
                } else {
                    $(this).addClass('active');
                }
                let id_main = parseInt($('input[name="radio-category-list-1"]:checked').val());
                ADMIN.refreshCategoryList(id_main);
            });
            $("#attribute-list-2 .list-item").on("click", function() {
                let id_attibute = parseInt($(this).attr('value'));
                if($(this).hasClass('active')) {
                    $(this).removeClass('active');
                    $('#attribute-list-1 .list-item[value="' + id_attibute + '"]').remove();
                } else {
                    let html = '<div class="list-item no-hover" value="' + id_attibute + '">' + $(this).html() + '</div>';
                    $('#attribute-list-1').append(html);
                    $(this).addClass('active');
                }
            });
            $('#btn-delete-from-server-image').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_image: parseInt(btn.closest('.popup').attr('id-image')),
                }
                if(!btn.hasClass('disabled')) {
                    btn.closest('.popup').find('.btn').addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/delete-server-image',
                        data: obj,
                        success: function(data) {
                            if(data.delete_server_image.response == 'ok') {
                                let id_product = parseInt($('#input-id-product').val())
                                ADMIN.getProductImages(id_product);
                                UTILS.closePopup('#popup-delete-image');
                            } else {
                                UTILS.showInfo('Uups', 'An unexpected error has occurred.<br>Reload the page to try again.');
                            }
                            btn.closest('.popup').find('.btn').removeClass('disabled');
                        }
                    });
                }
            });
            $('#btn-delete-just-product-image').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_product_image: parseInt(btn.closest('.popup').attr('id-product-image')),
                    id_product: parseInt($('#input-id-product').val())
                }
                if(!btn.hasClass('disabled')) {
                    btn.closest('.popup').find('.btn').addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/delete-product-image',
                        data: obj,
                        success: function(data) {
                            if(data.delete_product_image.response == 'ok') {
                                ADMIN.getProductImages(obj.id_product);
                                UTILS.closePopup('#popup-delete-image');
                            } else {
                                UTILS.showInfo('Uups', 'An unexpected error has occurred.<br>Reload the page to try again.');
                            }
                            btn.closest('.popup').find('.btn').removeClass('disabled');
                        }
                    });
                }
            });
            // Loads all the images from the server, excluding the product ones, and opens the popup
            $('#btn-open-popup-add-image').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_product: parseInt($('#input-id-product').val())
                }
                if(!btn.hasClass('disabled')) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/get-add-images',
                        data: obj,
                        success: function(data) {
                            if(data.images.response == 'ok') {
                                $('#popup-add-image .content-images').html(data.images.html);
                                $('#popup-add-image .item-image .image').on("click", function() {
                                    if($(this).parent().hasClass('selected')) {
                                        $(this).parent().removeClass('selected');
                                    } else {
                                        $(this).parent().addClass('selected');
                                    }
                                });
                                UTILS.showPopup('#popup-add-image');
                            } else {
                                UTILS.showInfo('Uups', 'An unexpected error has occurred.<br>Reload the page to try again.');
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
            // Add the selected images to the product form
            $('#btn-add-images').on("click", function() {
                if($('#popup-add-image .item-image.selected').length != 0) {
                    var html = '';
                    $('#popup-add-image .item-image.selected').each(function() {
                        let id_image = parseInt($(this).attr('id-image'));
                        html += '<div class="item-image new-image" style="background-image: url(' + $(this).attr('url') + ');" id-image="' + id_image + '">';
                        html +=     '<div class="item-image-buttons">';
                        html +=         '<div class="btn-item-image-delete"><i class="fa-solid fa-trash-can"></i> Delete</div>';
                        html +=     '</div>';
                        html += '</div>';
                    });
                    $('#upload-images').append(html);
                    $('#upload-images .new-image .btn-item-image-delete').off().on('click', function() {
                        $(this).closest('.item-image').remove();
                    });
                    UTILS.closePopup('#popup-add-image');
                }
            });
            // Event when images are selected from the browser
            $("#input-file").on("change", function() {
                let types = ['image/jpg', 'image/jpeg', 'image/png'];
                if(this.files) {
                    if(this.files.length <= 10) {
                        for(i = 0; i < this.files.length; i++) {
                            if(types.includes(this.files[i].type)) {
                                ADMIN.loadNewProductImagen(this.files[i]);
                            }
                        }
                    } else {
                        UTILS.showInfo('Uups', 'You can only upload a maximum of 10 files.');
                    }
                    this.value = '';
                }
            });
            $('#btn-open-popup-add-related').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_product: parseInt($('#input-id-product').val())
                }
                if(!btn.hasClass('disabled')) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/get-add-related',
                        data: obj,
                        success: function(data) {
                            if(data.get_add_related.response == 'ok') {
                                $('#popup-add-related .content-related').html(data.get_add_related.html);
                                $('#popup-add-related .content-images .item-image').on("click", function() {
                                    if($(this).hasClass('selected')) {
                                        $(this).removeClass('selected');
                                    } else {
                                        $(this).addClass('selected');
                                    }
                                });
                                UTILS.showPopup('#popup-add-related');
                            } else {
                                UTILS.showInfo('Uups', 'An unexpected error has occurred.<br>Reload the page to try again.');
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
            $('#btn-add-related').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_product: parseInt($('#input-id-product').val()),
                    attributes: [],
                    stock: $('#input-add-related-stock').val().trim(),
                    price_change: $('#input-add-related-price-change').val().trim(),
                    weight_change: $('#input-add-related-weight-change').val().trim(),
                    id_state: parseInt($('#select-add-related-state').val()),
                    images: []
                }
                $('#popup-add-related .content-attributes .item-attribute').each(function() {
                    let attribute = {
                        id_attribute: parseInt($(this).attr('id-attribute')),
                        id_value: parseInt($(this).find('select').val())
                    }
                    obj.attributes.push(attribute);
                });
                $('#popup-add-related .content-images .item-image.selected').each(function() {
                    obj.images.push($(this).attr('id-product-image'));
                });
                if(!UTILS.validate('number', obj.stock)) {
                    $('#input-add-related-stock').addClass('error');
                } else {
                    obj.stock = parseInt(obj.stock);
                }
                if(!UTILS.validate('price-negative', obj.price_change)) {
                    $('#input-add-related-price-change').addClass('error');
                }
                if(!UTILS.validate('weight-negative', obj.weight_change)) {
                    $('#input-add-related-weight-change').addClass('error');
                }
                if(!btn.hasClass('disabled')) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/add-related',
                        data: obj,
                        success: function(data) {
                            if(data.add_related.response == 'ok') {
                                if($('#products-related table').length != 0) {
                                    $('#products-related tbody').append(data.add_related.html);
                                } else {
                                    $('#products-related').html(data.add_related.html);
                                }
                                ADMIN.editProductRelatedEvents();
                                UTILS.closePopup('#popup-add-related');
                            } else {
                                UTILS.showInfo('Uups', data.add_related.mensaje);
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
            $('#btn-save-edit-related').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_product_related: parseInt(btn.attr('id-product-related')),
                    stock: $('#input-edit-related-stock').val().trim(),
                    price_change: $('#input-edit-related-price-change').val().trim(),
                    weight_change: $('#input-edit-related-weight-change').val().trim(),
                    id_state: parseInt($('#select-edit-related-state').val()),
                    offer: $('#input-edit-related-offer').val().trim(),
                    offer_start: $('#input-edit-related-offer-start-date').val().trim(),
                    offer_end: $('#input-edit-related-offer-end-date').val().trim(),
                    images: []
                }
                $('#popup-edit-related .content-images .item-image.selected').each(function() {
                    obj.images.push($(this).attr('id-product-image'));
                });
                $('#popup-edit-related *').removeClass('error');
                if(!UTILS.validate('number', obj.stock)) {
                    $('#input-edit-related-stock').addClass('error');
                } else {
                    obj.stock = parseInt(obj.stock);
                }
                if(!UTILS.validate('price-negative', obj.price_change)) {
                    $('#input-edit-related-price-change').addClass('error');
                }
                if(!UTILS.validate('weight-negative', obj.weight_change)) {
                    $('#input-edit-related-weight-change').addClass('error');
                }
                if(!UTILS.validate('price', obj.offer)) {
                    $('#input-edit-related-offer').addClass('error');
                }
                if(obj.offer != 0) {
                    if(!UTILS.validate('date', obj.offer_start)) {
                        $('#input-edit-related-offer-start-date').addClass('error');
                    }
                    if(!UTILS.validate('date', obj.offer_end)) {
                        $('#input-edit-related-offer-end-date').addClass('error');                        
                    }
                }
                if(!btn.hasClass('disabled') && $('#popup-edit-related .error').length == 0) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-related',
                        data: obj,
                        success: function(data) {
                            if(data.save_related.response == 'ok') {
                                let id_product = parseInt($('#input-id-product').val());
                                ADMIN.getRelated(id_product);
                                UTILS.showInfo('Correct!', data.save_related.mensaje);
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }            
            });    
            $('#btn-save-product').on("click", function() {
                var btn = $(this);
                var obj = ADMIN.objSaveEditProduct();
                obj.id_product = parseInt($('#input-id-product').val());
                if($('input[name="input-related-main"]:checked').val() != undefined) {
                    obj.id_related_main = parseInt($('input[name="input-related-main"]:checked').val());
                }
                // Validation
                $('.content-edit-product *').removeClass('error');
                if(!UTILS.validate('min-char-3', obj.alias)) {
                    $('#input-name').addClass('error');
                }
                if(!UTILS.validate('price', obj.price) || obj.price == 0) {
                    $('#input-price').addClass('error');
                }
                if(!UTILS.validate('weight', obj.weight) || obj.weight == 0) {
                    $('#input-weight').addClass('error');
                }
                if(obj.categories.length == 0) {
                    $('#category-list-1').addClass('error');
                }
                if(!Number.isInteger(obj.main_category)) {
                    $('#category-list-1').addClass('error');
                }
                if(!btn.hasClass('disabled') && $('.content-edit-product .error').length == 0) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-edit-product',
                        data: obj,
                        success: function(data) {
                            if(data.save_edit_product.response == 'ok') {
                                UTILS.showInfo('Correct!', data.save_edit_product.mensaje);
                                let id_product = parseInt($('#input-id-product').val());
                                ADMIN.getProductImages(id_product);
                            } else {
                                UTILS.showInfo('Error', data.save_edit_product.mensaje);
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
        }    
    },
    categoriesEvent: function() {
        if($('body#admin-categories-page').length == 1) {
            $('.btn-delete-category').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_category: parseInt(btn.attr('id-category'))
                }
                if(!btn.hasClass('disabled')) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/delete-category',
                        data: obj,
                        success: function(data) {
                            if(data.delete_category.response == 'ok') {
                                // If it is the last category, I delete the table
                                if($('#categories-content tbody > tr').length == 1) {
                                    $('#categories-content').html('No categories');
                                } else {
                                    btn.closest('tr').remove();
                                }
                            } else {
                                UTILS.showInfo('Uups', 'An unexpected error has occurred.<br>Reload the page to try again.');
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
        }    
    },
    categoriesCustomRoutesEvents: function() {
        if($('body#admin-categories-custom-routes-page').length == 1) {
            $('#open-popup-new-category-custom-route').on("click", function() {
                $('#categories-list').val(0);
                $('#languages-content input').val('');
                $('#languages-content').addClass('hidden');
                UTILS.showPopup('#popup-new-category-custom-route');
            });
            $('#categories-list').on("change", function() {
                $('#languages-content').removeClass('hidden');
            });
            $('#btn-save-new-category-custom-route').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_category: parseInt($('#categories-list').val()),
                    routes: []
                }
                $('#popup-new-category-custom-route *').removeClass('error');
                $('#languages-content .item').each(function() {
                    let temp = {
                        id_language: parseInt($(this).attr('id-language')),
                        route: $(this).find('input').val().trim()
                    };
                    // If the route is empty, I don't validate it
                    if(!UTILS.validate('slug', temp.route) || temp.route == '') {
                        $(this).find('input').addClass('error');
                    }
                    obj.routes.push(temp);
                });
                if(obj.id_category == 0) {
                    $('#categories-list').addClass('error');
                }
                if(!btn.hasClass('disabled') && $('#popup-new-category-custom-route .error').length == 0) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-new-category-custom-route',
                        data: obj,
                        success: function(data) {
                            if(data.save_new_category_custom_route.response == 'ok') {
                                btn.addClass('btn-ok');
                                window.location.href = ADMIN_PATH + '/categories-custom-routes?new';
                            } else {
                                btn.removeClass('disabled');
                            }
                        }
                    });
                }
            });        
            $('.btn-delete-category-custom-route').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_category_custom_route: parseInt(btn.attr('id-category-custom-route'))
                }
                if(!btn.hasClass('disabled')) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/delete-category-custom-route',
                        data: obj,
                        success: function(data) {
                            if(data.delete_category_custom_route.response == 'ok') {
                                // If it is the last category, I delete the table
                                if($('#categories-custom-routes-content tbody > tr').length == 1) {
                                    $('#categories-custom-routes-content').html('No categories custom routes');
                                } else {
                                    btn.closest('tr').remove();
                                }
                            } else {
                                UTILS.showInfo('Uups', 'An unexpected error has occurred.<br>Reload the page to try again.');
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
        }
    },
    newCategoryEvent: function() {
        if($('body#admin-new-category-page').length == 1) {
            $('#btn-save-new-category').on("click", function() {
                var btn = $(this);
                var obj = ADMIN.objSaveEditCategory();
                $('.content-new-category *').removeClass('error');
                if(!UTILS.validate('min-char-3', obj.alias)) {
                    $('#input-alias').addClass('error');
                }
                if(!btn.hasClass('disabled') && $('.content-new-category .error').length == 0) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-new-category',
                        data: obj,
                        success: function(data) {
                            if(data.save_new_category.response == 'ok') {
                                btn.addClass('btn-ok');
                                window.location.href = ADMIN_PATH + '/categories?new';
                            } else {
                                UTILS.showInfo('Error', data.save_new_category.mensaje);
                                btn.removeClass('disabled');
                            }
                        }
                    });
                }
            });
        }    
    },
    editCategoryEvent: function() {
        if($('body#admin-edit-category-page').length == 1) {
            $('#btn-save-category').on("click", function() {
                var btn = $(this);
                var obj = ADMIN.objSaveEditCategory();
                obj.id_category = parseInt($('#input-id-category').val());
                $('.content-edit-category *').removeClass('error');
                if(!UTILS.validate('min-char-3', obj.alias)) {
                    $('#input-alias').addClass('error');
                }
                if(!btn.hasClass('disabled') && $('.content-edit-category .error').length == 0) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-edit-category',
                        data: obj,
                        success: function(data) {
                            if(data.save_edit_category.response == 'ok') {
                                UTILS.showInfo('Correct!', data.save_edit_category.mensaje);
                            } else {
                                UTILS.showInfo('Error', data.save_edit_category.mensaje);
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
        }    
    },
    attributesEvent: function() {
        if($('body#admin-attributes-page').length == 1) {
            $('.btn-delete-attribute').off().on("click", function() {
                var btn = $(this);
                var obj = {
                    id_attribute: parseInt(btn.attr('id-attribute'))
                }
                if(!btn.hasClass('disabled')) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/delete-attribute',
                        data: obj,
                        success: function(data) {
                            if(data.delete_attribute.response == 'ok') {
                                // If it is the last attribute, I delete the table
                                if($('#attributes-content tbody > tr').length == 1) {
                                    $('#attribute').html('No attributes');
                                } else {
                                    btn.closest('tr').remove();
                                }
                            } else {
                                UTILS.showInfo('Uups', 'An unexpected error has occurred.<br>Reload the page to try again.');
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
        }    
    },
    newAttributeEvent: function() {
        if($('body#admin-new-attribute-page').length == 1) {
            ADMIN.newEditAttributeEvents();
            $('#btn-save-new-attribute').on("click", function() {
                var btn = $(this);
                var obj = ADMIN.objSaveEditAttribute();
                $('.content-new-attribute *').removeClass('error');
                if(!UTILS.validate('min-char-3', obj.alias)) {
                    $('#input-alias').addClass('error');
                }
                if(!btn.hasClass('disabled') && $('.content-new-attribute .error').length == 0) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-new-attribute',
                        data: obj,
                        success: function(data) {
                            if(data.save_new_attribute.response == 'ok') {
                                btn.addClass('btn-ok');
                                window.location.href = ADMIN_PATH + '/attributes?new';
                            } else {
                                btn.removeClass('disabled');
                            }
                        }
                    });
                }
            });
        }    
    },
    editAttributeEvents: function() {
        if($('body#admin-edit-attribute-page').length == 1) {
            let id_attribute = parseInt($('#input-id-attribute').val())
            ADMIN.getAttributeValues(id_attribute);
            ADMIN.newEditAttributeEvents();
            // According to the type of attribute, I show the corresponding add value box
            $('#select-type').trigger('change');
            $('#btn-save-attribute').on("click", function() {
                var btn = $(this);
                var obj = ADMIN.objSaveEditAttribute();
                obj.id_attribute = parseInt($('#input-id-attribute').val());
                $('.content-edit-attribute *').removeClass('error');
                if(!UTILS.validate('min-char-3', obj.alias)) {
                    $('#input-alias').addClass('error');
                }
                if(!btn.hasClass('disabled') && $('.content-edit-attribute .error').length == 0) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-edit-attribute',
                        data: obj,
                        success: function(data) {
                            if(data.save_edit_attribute.response == 'ok') {
                                ADMIN.getAttributeValues(obj.id_attribute);
                                UTILS.showInfo('Correct!', data.save_edit_attribute.mensaje);
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
            $('#btn-save-edit-attribute-value').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_attribute_value: parseInt($('#popup-attribute-value-properties').attr('id-attribute-value')),
                    alias: $('#input-edit-value-alias').val().trim(),
                    properties: []
                }
                $('#value-properties-content .menu > div').each(function() {
                    let id_tab = $(this).attr('id-tab');
                    let content = $(this).closest('#value-properties-content').find('.content > div[id-tab="' + id_tab + '"]');
                    let data = {
                        'id_lang': parseInt($(this).attr('id-lang')),
                        'name': $(content).find('.input-value-name').val().trim(),
                        'description': $(content).find('.textarea-value-description').val().trim()
                    }
                    obj.properties.push(data);
                });
                if(!btn.hasClass('disabled')) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-attribute-value-properties',
                        data: obj,
                        success: function(data) {
                            if(data.save_attribute_value_properties.response == 'ok') {
                                let id_attribute = parseInt($('#input-id-attribute').val())
                                ADMIN.getAttributeValues(id_attribute);
                                UTILS.closePopup('#popup-attribute-value-properties');
                                UTILS.showInfo('Correct!', data.save_attribute_value_properties.mensaje);
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
        }    
    },
    imagesEvent: function() {
        if($('body#admin-images-page').length == 1) {
            $('.btn-delete-image').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_image: parseInt($(this).attr('id-image'))
                }
                if(!btn.hasClass('disabled')) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/delete-server-image',
                        data: obj,
                        success: function(data) {
                            if(data.delete_server_image.response == 'ok') {
                                btn.closest('tr').remove();
                            } else {
                                UTILS.showInfo('Uups', 'An unexpected error has occurred.<br>Reload the page to try again.');
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
        }    
    },
    codesEvent: function() {
        if($('body#admin-codes-page').length == 1) {
            $('.btn-delete-code').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_code: parseInt(btn.attr('id-code'))
                }
                if(!btn.hasClass('disabled')) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/delete-code',
                        data: obj,
                        success: function(data) {
                            if(data.delete_code.response == 'ok') {
                                btn.closest('tr').remove();
                            } else {
                                UTILS.showInfo('Uups', 'An unexpected error has occurred.<br>Reload the page to try again.');
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
        }    
    },
    newCodeEvent: function() {
        if($('body#admin-new-code-page').length == 1) {
            // I pick up the current date for date fields
            let now = new Date();
            let year = now.getFullYear();
            let month = now.getMonth() + 1;
            if(month < 10) month = '0' + month;
            let day = now.getDate();
            if(day < 10) day = '0' + day;
            $('#input-start-date').val(year + '-' + month + '-' + day);
            $('#input-end-date').val((year + 1) + '-' + month + '-' + day);
            // Save event
            $('#btn-save-new-code').on("click", function() {
                var btn = $(this);
                var obj = ADMIN.objSaveValidCode();
                if(!btn.hasClass('disabled') && $('.content-save-code .error').length == 0) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-new-code',
                        data: obj,
                        success: function(data) {
                            if(data.save_new_code.response == 'ok') {
                                btn.addClass('btn-ok');
                                window.location.href = ADMIN_PATH + '/codes?new';
                            } else {
                                UTILS.showInfo('Uups', 'An unexpected error has occurred.<br>Reload the page to try again.');
                                btn.removeClass('disabled');
                            }
                        }
                    });
                }
            });
        }    
    },
    editCodeRuleEvents: function() {
        $('.btn-delete-code-rule').off().on("click", function() {
            var btn = $(this);
            var obj = {
                id_code_rule: parseInt(btn.attr('id-code-rule'))
            }
            if(!btn.hasClass('disabled')) {
                btn.addClass('disabled');
                $.ajax({
                    url: ADMIN_PATH + '/delete-code-rule',
                    data: obj,
                    success: function(data) {
                        if(data.delete_code_rule.response == 'ok') {
                            // If it is the last related, I delete the table
                            if($('#code-rules tbody > tr').length == 1) {
                                $('#code-rules').html('No code rules');
                            } else {
                                btn.closest('tr').remove();
                            }                            
                        } else {
                            UTILS.showInfo('Uups', 'An unexpected error has occurred.<br>Reload the page to try again.');
                        }
                        btn.removeClass('disabled');
                    }
                });
            }
        });
        $('.btn-edit-code-rule').off().on("click", function() {
            var btn = $(this);
            var obj = {
                id_code_rule: parseInt(btn.attr('id-code-rule'))
            }
            if(!btn.hasClass('disabled')) {
                btn.addClass('disabled');
                $.ajax({
                    url: ADMIN_PATH + '/get-code-rule',
                    data: obj,
                    success: function(data) {
                        if(data.get_code_rule.response == 'ok') {
                            $('#btn-save-code-rule').attr('id-code-rule', data.get_code_rule.rule.id_code_rule);
                            $('#select-edit-code-rule-type').val(data.get_code_rule.rule.id_code_rule_type);
                            $('#select-edit-code-rule-add-type').val(data.get_code_rule.rule.id_code_rule_add_type);
                            $('#edit-code-rule-elements-list').html(data.get_code_rule.html_elements);
                            $('#edit-code-rule-elements-added').html(data.get_code_rule.html_selected);
                            ADMIN.editCodeRuleElementsEvent();
                            UTILS.showPopup('#popup-edit-code-rule');
                        } else {
                            UTILS.showInfo('Uups', 'An unexpected error has occurred.<br>Reload the page to try again.');
                        }
                        btn.removeClass('disabled');
                    }
                });
            }
        });
    },
    editCodeEvent: function() {
        if($('body#admin-edit-code-page').length == 1) {
            let id_code = parseInt($('#input-id-code').val());
            ADMIN.getCodeRules(id_code);
            $('#btn-save-edit-code').on("click", function() {
                var btn = $(this);
                var obj = ADMIN.objSaveValidCode();
                obj.id_code = id_code;
                if(!btn.hasClass('disabled') && $('.content-save-code .error').length == 0) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-edit-code',
                        data: obj,
                        success: function(data) {
                            if(data.save_edit_code.response == 'ok') {
                                UTILS.showInfo('Correct!', data.save_edit_code.mensaje);
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
            $('#btn-open-popup-new-code-rule').on("click", function() {
                $('#select-add-code-rule-type').val(1);
                $('#select-add-code-rule-add-type').val(1);
                $('#add-code-rule-elements-list').html('');
                $('#add-code-rule-elements-added').html('');
                $('#select-add-code-rule-type').trigger("change");
                UTILS.showPopup('#popup-add-code-rule');
            });
            $('#select-add-code-rule-type').on("change", function() {
                var select = $(this);
                var obj = {
                    id_code_rule_type: parseInt(select.val())
                }
                select.prop("disabled", true);
                $('#add-code-rule-elements-list').html('');
                $('#add-code-rule-elements-added').html('');
                $.ajax({
                    url: ADMIN_PATH + '/get-code-rule-elements-list',
                    data: obj,
                    async: false,
                    success: function(data) {
                        if(data.get_code_rule_elements_list.response == 'ok') {
                            $('#add-code-rule-elements-list').html(data.get_code_rule_elements_list.html);
                            $('#add-code-rule-elements-list .list-item').off().on('click', function() {
                                let id_element = $(this).attr('value');
                                if($(this).hasClass('active')) {
                                    $(this).removeClass('active');
                                    $('#add-code-rule-elements-added .list-item[value="' + id_element + '"]').remove();
                                } else {
                                    let html = '<div class="list-item no-hover" value="' + id_element + '">' + $(this).html() + '</div>';
                                    $('#add-code-rule-elements-added').append(html);
                                    $(this).addClass('active');
                                }                
                            });
                        } else {
                            UTILS.showInfo('Uups', 'An unexpected error has occurred.<br>Reload the page to try again.');
                        }
                        select.prop("disabled", false);
                    }
                });
            });
            $('#btn-add-code-rule').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_code: id_code,
                    id_rule_type: parseInt($('#select-add-code-rule-type').val()),
                    id_rule_add_type: parseInt($('#select-add-code-rule-add-type').val()),
                    elements: []
                }
                $('#add-code-rule-elements-added .list-item').each(function() {
                    let id_element = $(this).attr('value');
                    obj.elements.push(id_element);
                });
                $('#popup-add-code-rule *').removeClass('error');
                // If there are no elements
                if(obj.elements.length == 0) {
                    $('#add-code-rule-elements-added').addClass('error');
                }
                if(!btn.hasClass('disabled') && $('#popup-add-code-rule .error').length == 0) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/add-code-rule',
                        data: obj,
                        success: function(data) {
                            if(data.add_code_rule.response == 'ok') {
                                if($('#code-rules table').length != 0) {
                                    $('#code-rules tbody').append(data.add_code_rule.html);
                                } else {
                                    $('#code-rules').html(data.add_code_rule.html);
                                }
                                ADMIN.editCodeRuleEvents();
                                UTILS.closePopup('#popup-add-code-rule');
                            } else {
                                UTILS.showInfo('Uups', 'An unexpected error has occurred.<br>Reload the page to try again.');
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
            $('#select-edit-code-rule-type').on("change", function() {
                var select = $(this);
                var obj = {
                    id_code_rule_type: parseInt(select.val())
                }
                select.prop("disabled", true);
                $('#edit-code-rule-elements-list').html('');
                $('#edit-code-rule-elements-added').html('');
                $.ajax({
                    url: ADMIN_PATH + '/get-code-rule-elements-list',
                    data: obj,
                    async: false,
                    success: function(data) {
                        if(data.get_code_rule_elements_list.response == 'ok') {
                            $('#edit-code-rule-elements-list').html(data.get_code_rule_elements_list.html);
                            ADMIN.editCodeRuleElementsEvent();
                        } else {
                            UTILS.showInfo('Uups', 'An unexpected error has occurred.<br>Reload the page to try again.');
                        }
                        select.prop("disabled", false);
                    }
                });
            });
            $('#btn-save-code-rule').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_code_rule: btn.attr('id-code-rule'),
                    id_rule_type: parseInt($('#select-edit-code-rule-type').val()),
                    id_rule_add_type: parseInt($('#select-edit-code-rule-add-type').val()),
                    elements: []
                }
                $('#edit-code-rule-elements-added .list-item').each(function() {
                    let id_element = $(this).attr('value');
                    obj.elements.push(id_element);
                });
                $('#popup-edit-code-rule *').removeClass('error');
                // If there are no elements
                if(obj.elements.length == 0) {
                    $('#edit-code-rule-elements-added').addClass('error');
                }
                if(!btn.hasClass('disabled') && $('#popup-edit-code-rule .error').length == 0) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-code-rule',
                        data: obj,
                        success: function(data) {
                            if(data.save_code_rule.response == 'ok') {
                                ADMIN.getCodeRules(id_code);
                                UTILS.showInfo('Correct!', data.save_code_rule.mensaje);
                            } else {
                                UTILS.showInfo('Uups', 'An unexpected error has occurred.<br>Reload the page to try again.');
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
        }    
    },
    getCodeRules: function(id_code) {
        var obj = {
            id_code: id_code
        }
        $.ajax({
            url: ADMIN_PATH + '/get-code-rules',
            data: obj,
            success: function(data) {
                if(data.get_code_rules.response == 'ok') {
                    $('#code-rules').html(data.get_code_rules.html);
                    ADMIN.editCodeRuleEvents();
                }
            }
        });
    },
    editCodeRuleElementsEvent: function() {
        $('#edit-code-rule-elements-list .list-item').off().on('click', function() {
            let id_element = $(this).attr('value');
            if($(this).hasClass('active')) {
                $(this).removeClass('active');
                $('#edit-code-rule-elements-added .list-item[value="' + id_element + '"]').remove();
            } else {
                let html = '<div class="list-item no-hover" value="' + id_element + '">' + $(this).html() + '</div>';
                $('#edit-code-rule-elements-added').append(html);
                $(this).addClass('active');
            }                
        });
    },
    newPaymentEvent: function() {
        if($('body#admin-new-payment-method-page').length == 1) {
            $('#btn-save-new-payment').on("click", function() {
                var btn = $(this);
                var obj = {
                    alias: $('#input-alias').val().trim(),
                    min_value: parseInt($('#input-min-value').val()),
                    max_value: parseInt($('#input-max-value').val()),
                    id_state: parseInt($('#select-state').val()),
                    languages: []
                }
                $('#languages .menu > div').each(function() {
                    let id_tab = $(this).attr('id-tab');
                    let content = $(this).closest('#languages').find('.content > div[id-tab="' + id_tab + '"]');
                    let data = {
                        'id_lang': parseInt($(this).attr('id-lang')),
                        'name': $(content).find('.input-language-name').val().trim()
                    }
                    obj.languages.push(data);
                });
                var form = UTILS.validateForm('#form-new-payment');
                if(!btn.hasClass('disabled') && form.response == true) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-new-payment',
                        data: obj,
                        success: function(data) {
                            if(data.save_new_payment.response == 'ok') {
                                btn.addClass('btn-ok');
                                window.location.href = ADMIN_PATH + '/payments?new';
                            } else {
                                UTILS.showInfo('Error', data.save_new_payment.message);
                                btn.removeClass('disabled');
                            }
                        }
                    });
                }
            });
        }
    },
    editPaymentEvent: function() {
        if($('body#admin-edit-payment-method-page').length == 1) {
            $('#btn-save-edit-payment').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_payment_method: parseInt($('#input-id-payment-method').val()),
                    alias: $('#input-alias').val().trim(),
                    min_value: parseInt($('#input-min-value').val()),
                    max_value: parseInt($('#input-max-value').val()),
                    id_state: parseInt($('#select-state').val()),
                    languages: []
                }
                $('#languages .menu > div').each(function() {
                    let id_tab = $(this).attr('id-tab');
                    let content = $(this).closest('#languages').find('.content > div[id-tab="' + id_tab + '"]');
                    let data = {
                        'id_lang': parseInt($(this).attr('id-lang')),
                        'name': $(content).find('.input-language-name').val().trim()
                    }
                    obj.languages.push(data);
                });
                var form = UTILS.validateForm('#form-edit-payment');
                if(!btn.hasClass('disabled') && form.response == true) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-edit-payment',
                        data: obj,
                        success: function(data) {
                            if(data.save_edit_payment.response == 'ok') {
                                UTILS.showInfo('Correct!', data.save_edit_payment.message);
                            } else {
                                UTILS.showInfo('Error', data.save_edit_payment.message);
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
        }
    },
    newPaymentZoneEvent: function() {
        if($('body#admin-new-payment-zone-page').length == 1) {
            $('#btn-save-new-payment-zone').on("click", function() {
                var btn = $(this);
                var obj = {
                    name: $('#input-name').val().trim(),
                    id_state: parseInt($('#select-state').val())
                }
                var form = UTILS.validateForm('#form-new-payment-zone');
                if(!btn.hasClass('disabled') && form.response == true) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-new-payment-zone',
                        data: obj,
                        success: function(data) {
                            if(data.save_new_payment_zone.response == 'ok') {
                                btn.addClass('btn-ok');
                                window.location.href = ADMIN_PATH + '/payment-zones?new';
                            } else {
                                UTILS.showInfo('Error', data.save_new_payment_zone.message);
                                btn.removeClass('disabled');
                            }
                        }
                    });
                }
            });
        }
    },
    editPaymentZoneEvent: function() {
        if($('body#admin-edit-payment-zone-page').length == 1) {
            $('#btn-save-edit-payment-zone').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_payment_zone: parseInt($('#input-id-payment-zone').val()),
                    name: $('#input-name').val().trim(),
                    id_state: parseInt($('#select-state').val())
                }
                var form = UTILS.validateForm('#form-edit-payment-zone');
                if(!btn.hasClass('disabled') && form.response == true) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-edit-payment-zone',
                        data: obj,
                        success: function(data) {
                            if(data.save_edit_payment_zone.response == 'ok') {
                                UTILS.showInfo('Correct!', data.save_edit_payment_zone.message);
                            } else {
                                UTILS.showInfo('Error', data.save_edit_payment_zone.message);
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
        }
    },
    usersEvent: function() {
        if($('body#admin-users-page').length == 1) {
            $('.btn-delete-user').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_user: parseInt(btn.attr('id-user'))
                }
                if(!btn.hasClass('disabled')) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/delete-user',
                        data: obj,
                        success: function(data) {
                            if(data.delete_user.response == 'ok') {
                                btn.closest('tr').remove();
                            } else {
                                UTILS.showInfo('Uups', 'An unexpected error has occurred.<br>Reload the page to try again.');
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
        }    
    },
    editUserEvents: function() {
        if($('body#admin-edit-user-page').length == 1) {
            ADMIN.getUserAddresses($('#input-id-user').val().trim());
            // Address save event
            $('#btn-save-edit-address').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_user_address: parseInt(btn.attr('id-user-address')),
                    id_continent: parseInt($('#input-edit-address-continent').val()),
                    id_country: parseInt($('#input-edit-address-country').val()),
                    id_province: parseInt($('#input-edit-address-province').val()),
                    address: $('#input-edit-address-address').val().trim(),
                    location: $('#input-edit-address-location').val().trim(),
                    postal_code: $('#input-edit-address-postal-code').val().trim(),
                    telephone: $('#input-edit-address-telephone').val().trim()
                };
                $('.content-edit-address *').removeClass('error');
                if(obj.address == '') {
                    $('#input-edit-address-address').addClass('error');
                }
                if(obj.location == '') {
                    $('#input-edit-address-location').addClass('error');
                }
                if(!UTILS.validate('cp', obj.postal_code)) {
                    $('#input-edit-address-postal-code').addClass('error');
                }
                if(!UTILS.validate('telephone', obj.telephone)) {
                    $('#input-edit-address-telephone').addClass('error');
                }
                if(!btn.hasClass('disabled') && $('.content-edit-address .error').length == 0) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-edit-user-address',
                        data: obj,
                        success: function(data) {
                            if(data.save_edit_user_address.response == 'ok') {
                                UTILS.showInfo('Correct!', data.save_edit_user_address.mensaje);
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
            $('#btn-close-user-sessions').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_user: parseInt($('#input-id-user').val().trim())
                };
                if(!btn.hasClass('disabled')) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/close-user-sessions',
                        data: obj,
                        success: function(data) {
                            if(data.close_user_seassons.response == 'ok') {
                                UTILS.showInfo('Correct!', data.close_user_seassons.mensaje);
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });        
            $('#btn-resend-validation-email').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_user: parseInt($('#input-id-user').val().trim())
                };
                if(!btn.hasClass('disabled')) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/send-validation-email',
                        data: obj,
                        success: function(data) {
                            if(data.send_validation_email.response == 'ok') {
                                let html = '<div class="pb-10">' + data.send_validation_email.mensaje + '</div>';
                                html += '<div><b>Link:</b> ' + data.send_validation_email.link + '</div>';
                                UTILS.showInfo('Correct!', html);
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
            $('#btn-save-edit-user').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_user: parseInt($('#input-id-user').val().trim()),
                    name: $('#input-name').val().trim(),
                    lastname: $('#input-last-name').val().trim(),
                    email: $('#input-email').val().trim(),
                    id_state: parseInt($('#select-state').val())
                };
                // I pick up the main address
                if($('input[name="input-address-main"]:checked').val() != undefined) {
                    obj.id_address_main = parseInt($('input[name="input-address-main"]:checked').val());
                }
                $('.content-edit-user *').removeClass('error');
                if(!UTILS.validate('name', obj.name)) {
                    $('#input-name').addClass('error');
                }
                if(!UTILS.validate('lastname', obj.lastname)) {
                    $('#input-last-name').addClass('error');
                }
                if(!UTILS.validate('email', obj.email)) {
                    $('#input-email').addClass('error');
                }
                if(!btn.hasClass('disabled') && $('.content-edit-user .error').length == 0) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-edit-user',
                        data: obj,
                        success: function(data) {
                            if(data.save_edit_user.response == 'ok') {
                                UTILS.showInfo('Correct!', data.save_edit_user.mensaje);
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
        }    
    },
    adminUsersEvent: function() {
        if($('body#admin-users-admin-page').length == 1) {
            $('.btn-delete-admin').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_admin: parseInt(btn.attr('id-admin'))
                };
                if(!btn.hasClass('disabled')) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/delete-admin-user',
                        data: obj,
                        success: function(data) {
                            if(data.delete_admin_user.response == 'ok') {
                                btn.closest('tr').remove();
                            } else {
                                UTILS.showInfo('Uups', 'An unexpected error has occurred.<br>Reload the page to try again.');
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
        }    
    },
    newAdminUserEvent: function() {
        if($('body#admin-new-admin-user-page').length == 1) {
            $('#btn-save-new-admin-user').on("click", function() {
                var btn = $(this);
                var obj = {
                    name: $('#input-name').val().trim(),
                    lastname: $('#input-last-name').val().trim(),
                    email: $('#input-email').val().trim(),
                    pass1: $('#input-password-1').val().trim(),
                    pass2: $('#input-password-2').val().trim(),
                    id_admin_type: parseInt($('#select-admin-type').val())
                };
                $('.content-new-admin-user *').removeClass('error');
                if(!UTILS.validate('name', obj.name)) {
                    $('#input-name').addClass('error');
                }
                if(!UTILS.validate('lastname', obj.lastname)) {
                    $('#input-last-name').addClass('error');
                }
                if(!UTILS.validate('email', obj.email)) {
                    $('#input-email').addClass('error');
                }
                if(obj.pass1 == '' || obj.pass1.length < 8) {
                    $('#input-password-1').addClass('error');
                }
                if(obj.pass2 == '' || obj.pass2.length < 8) {
                    $('#input-password-2').addClass('error');
                }
                if(obj.pass1 != obj.pass2) {
                    $('#input-password-2').addClass('error');
                }
                if(!btn.hasClass('disabled') && $('.content-new-admin-user .error').length == 0) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-new-admin-user',
                        data: obj,
                        success: function(data) {
                            if(data.save_new_admin_user.response == 'ok') {
                                btn.addClass('btn-ok');
                                window.location.href = ADMIN_PATH + '/users-admin?new';
                            } else {
                                UTILS.showInfo('Error', data.save_new_admin_user.mensaje);
                                btn.removeClass('disabled');
                            }
                        }
                    });
                }
            });
        }    
    },
    editAdminUserEvents: function() {
        if($('body#admin-edit-user-admin-page').length == 1) {
            // To remove autocomplete from the browser
            setTimeout(function() {
                $('#input-password-1').val('');
            }, 1000);
            $('#btn-close-admin-user-sessions').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_admin: parseInt($('#input-id-admin').val())
                };
                if(!btn.hasClass('disabled')) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/close-admin-user-sessions',
                        data: obj,
                        success: function(data) {
                            if(data.close_admin_user_sessions.response == 'ok') {
                                UTILS.showInfo('Correct!', data.close_admin_user_sessions.mensaje);
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
            $('#btn-save-edit-admin-user').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_admin: parseInt($('#input-id-admin').val()),
                    name: $('#input-name').val().trim(),
                    lastname: $('#input-last-name').val().trim(),
                    email: $('#input-email').val().trim(),
                    pass1: $('#input-password-1').val().trim(),
                    pass2: $('#input-password-2').val().trim(),
                    id_admin_type: parseInt($('#select-admin-type').val()),
                    id_state: parseInt($('#select-state').val())
                };
                $('.content-edit-admin-user *').removeClass('error');
                if(!UTILS.validate('name', obj.name)) {
                    $('#input-name').addClass('error');
                }
                if(!UTILS.validate('lastname', obj.lastname)) {
                    $('#input-last-name').addClass('error');
                }
                if(!UTILS.validate('email', obj.email)) {
                    $('#input-email').addClass('error');
                }
                if(obj.pass1 != '' || obj.pass2 != '') {
                    if(obj.pass1.length < 8) {
                        $('#input-password-1').addClass('error');
                    }
                    if(obj.pass2.length < 8) {
                        $('#input-password-2').addClass('error');
                    }
                    if(obj.pass1 != obj.pass2) {
                        $('#input-password-2').addClass('error');
                    }    
                }
                if(!btn.hasClass('disabled') && $('.content-edit-admin-user .error').length == 0) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-edit-admin-user',
                        data: obj,
                        success: function(data) {
                            if(data.save_edit_admin_user.response == 'ok') {
                                UTILS.showInfo('Correct!', data.save_edit_admin_user.mensaje);
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
        }    
    }
}

$(window).ready(function() {
    ADMIN.init();
});