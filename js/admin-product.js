
/**
 * @author Diego Martín
 * @copyright Hive®
 * @version 1.0
 * @lastUpdated 2025
 */

var ADMIN_PRODUCT = {
    // Init
    init: function() {
        this.productsEvent();
        this.newProductEvents();
        this.editProductEvents();
        this.productsCustomRoutesEvents();
    },
    // Functions not related to page events
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
    getRelated: function(id_product) {
        var self = this;
        var obj = {
            id_product: id_product
        }
        $.ajax({
            url: ADMIN_PATH + '/get-related',
            data: obj,
            success: function(data) {
                if(data.get_related.response == 'ok') {
                    $('#products-related').html(data.get_related.html);
                    self.editProductRelatedEvents();
                }
            }
        });    
    },
    getProductImages: function(id_product) {
        var self = this;
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
                                        self.getProductImages(id_product);
                                        UTILS.showInfo('Correct!', data.save_product_main_image.message);                                
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
                                        self.getProductImages(id_product);
                                        UTILS.showInfo('Correct!', data.save_product_hover_image.message);                                
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
    objSaveEditProduct: function() {
        var obj = {
            id_product: null,
            alias: $('#input-name').val().trim(),
            price: $('#input-price').val().trim(),
            weight: $('#input-weight').val().trim(),
            id_tax_type: parseInt($('#select-tax').val()),
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
    // Events
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
    newProductEvents: function() {
        if($('body#admin-new-product-page').length == 1) {
            var self = this;
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
                self.refreshCategoryList(id_main);
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
                                self.loadNewProductImagen(this.files[i]);
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
                var obj = self.objSaveEditProduct();
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
                                UTILS.showInfo('Error', data.save_new_product.message);
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
            var self = this;
            let id_product = parseInt($('#input-id-product').val());
            self.getRelated(id_product);
            self.getProductImages(id_product);
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
            self.refreshCategoryList(id_main);
            $("#category-list-2 .list-item").on("click", function() {
                if($(this).hasClass('active')) {
                    $(this).removeClass('active');
                } else {
                    $(this).addClass('active');
                }
                let id_main = parseInt($('input[name="radio-category-list-1"]:checked').val());
                self.refreshCategoryList(id_main);
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
                            if(data.delete_product_server_image.response == 'ok') {
                                let id_product = parseInt($('#input-id-product').val())
                                self.getProductImages(id_product);
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
                                self.getProductImages(obj.id_product);
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
                                self.loadNewProductImagen(this.files[i]);
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
                                self.editProductRelatedEvents();
                                UTILS.closePopup('#popup-add-related');
                            } else {
                                UTILS.showInfo('Uups', data.add_related.message);
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
                                self.getRelated(id_product);
                                UTILS.showInfo('Correct!', data.save_related.message);
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }            
            });    
            $('#btn-save-product').on("click", function() {
                var btn = $(this);
                var obj = self.objSaveEditProduct();
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
                                UTILS.showInfo('Correct!', data.save_edit_product.message);
                                let id_product = parseInt($('#input-id-product').val());
                                self.getProductImages(id_product);
                            } else {
                                UTILS.showInfo('Error', data.save_edit_product.message);
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
    }
}

$(window).ready(function() {
    ADMIN_PRODUCT.init();
});