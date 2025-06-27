/*
* Author: Diego Martin
* Copyright: Hive®
* Version: 1.0
* Last Update: 2024
*/   

var ADMIN_SHIPMENT = {
    // Init
    init: function() {
        this.newEvents();
        this.editEvents();
        this.newZoneEvents();
        this.editZoneEvents();
    },
    // Functions not related to page events
    getZoneCountries: function(id_continent, page = 1) {
        var self = this;
        var obj = {
            id_shipping_zone: parseInt($('#input-id-shipping-zone').val()),
            id_continent: id_continent,
            page: page
        };
        $.ajax({
            url: ADMIN_PATH + '/get-shipping-zone-countries',
            data: obj,
            success: function(data) {
                if(data.get_shipping_zone_countries.response == 'ok') {
                    $('#shipping-zone-countries').html(data.get_shipping_zone_countries.html);
                    $('#shipping-zone-countries-pager').html(data.get_shipping_zone_countries.pager);
                    $('#shipping-zone-countries-pager .btn').off().on('click', function() {
                        let loadPage = $(this).data('page');
                        self.getZoneCountries(id_continent, loadPage);
                    });
                    self.checkAllInput('#shipping-zone-countries');
                    $('#shipping-zone-countries input[type="checkbox"]').off().on('click', function() {
                        self.checkAllInput('#shipping-zone-countries');
                    });
                }
            }
        });
    },
    getZoneProvinces: function(id_country, page = 1) {
        var self = this;
        var obj = {
            id_shipping_zone: parseInt($('#input-id-shipping-zone').val()),
            id_country: id_country,
            page: page
        };
        $.ajax({
            url: ADMIN_PATH + '/get-shipping-zone-provinces',
            data: obj,
            success: function(data) {
                if(data.get_shipping_zone_provinces.response == 'ok') {
                    $('#shipping-zone-provinces').html(data.get_shipping_zone_provinces.html);
                    $('#shipping-zone-provinces-pager').html(data.get_shipping_zone_provinces.pager);
                    $('#shipping-zone-provinces-pager .btn').off().on('click', function() {
                        let loadPage = $(this).data('page');
                        self.getZoneProvinces(id_country, loadPage);
                    });
                    self.checkAllInput('#shipping-zone-provinces');
                    $('#shipping-zone-provinces input[type="checkbox"]').off().on('click', function() {
                        self.checkAllInput('#shipping-zone-provinces');
                    });
                }
            }
        });
    },
    checkAllInput: function(name) {
        // I check if everything is selected to mark the Select all check box.
        var parent = $(name).closest('div[id-tab]');
        var allChecked = true;
        $(name + ' input[type="checkbox"]').each(function() {
            if(!$(this).is(':checked')) {
                allChecked = false;
            }
        });
        parent.find('.btn-select-all').prop('checked', allChecked);
    },
    // Events
    newEvents: function() {
        if($('body#admin-new-shipping-method-page').length == 1) {
            $('#btn-save-new-shipment').on("click", function() {
                var btn = $(this);
                var obj = {
                    alias: $('#input-alias').val().trim(),
                    min_value: parseInt($('#input-min-value').val()),
                    max_value: parseInt($('#input-max-value').val()),
                    min_weight: parseInt($('#input-min-weight').val()),
                    max_weight: parseInt($('#input-max-weight').val()),
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
                var form = UTILS.validateForm('#form-new-shipment');
                if(!btn.hasClass('disabled') && form.response == true) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-new-shipment',
                        data: obj,
                        success: function(data) {
                            if(data.save_new_shipment.response == 'ok') {
                                btn.addClass('btn-ok');
                                window.location.href = ADMIN_PATH + '/shipments?new';
                            } else {s
                                UTILS.showInfo('Error', data.save_new_shipment.message);
                                btn.removeClass('disabled');
                            }
                        }
                    });
                }
            });
        }
    },
    editEvents: function() {
        if($('body#admin-edit-shipping-method-page').length == 1) {
            $('#btn-delete-shipment').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_shipping_method: parseInt($('#input-id-shipping-method').val()),
                }
                if(!btn.hasClass('disabled')) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/delete-shipment',
                        data: obj,
                        success: function(data) {
                            if(data.delete_shipment.response == 'ok') {
                                window.location.href = ADMIN_PATH + '/shipments?delete';
                            }
                        }
                    });
                }
            });
            $('#btn-save-edit-shipment').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_shipping_method: parseInt($('#input-id-shipping-method').val()),
                    alias: $('#input-alias').val().trim(),
                    min_value: parseInt($('#input-min-value').val()),
                    max_value: parseInt($('#input-max-value').val()),
                    min_weight: parseInt($('#input-min-weight').val()),
                    max_weight: parseInt($('#input-max-weight').val()),
                    id_state: parseInt($('#select-state').val()),
                    languages: [],
                    zones: []
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
                $('#shipping-method-zones .shipping-zone').each(function() {
                    let active = ($(this).find('input[type="checkbox"]').prop('checked')) ? 1 : 0;
                    var zone = {
                        id_shipping_zone: $(this).data('id-shipping-zone'),
                        active: active,
                        prices: []
                    }
                    $(this).find('input[type="text"]').each(function() {
                        let price = {
                            id_shipping_method_weight: $(this).data('id-shipping-method-weight'),
                            price: $(this).val().trim()
                        }
                        zone.prices.push(price);
                    });
                    obj.zones.push(zone);
                });
                var form = UTILS.validateForm('#form-edit-shipment');
                if(!btn.hasClass('disabled') && form.response == true) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-edit-shipment',
                        data: obj,
                        success: function(data) {
                            if(data.save_edit_shipment.response == 'ok') {
                                UTILS.showInfo('Correct!', data.save_edit_shipment.message);
                            } else {
                                UTILS.showInfo('Error', data.save_edit_shipment.message);
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
        }
    },
    newZoneEvents: function() {
        if($('body#admin-new-shipping-zone-page').length == 1) {
            $('#btn-save-new-shipping-zone').on("click", function() {
                var btn = $(this);
                var obj = {
                    name: $('#input-name').val().trim(),
                    id_state: parseInt($('#select-state').val())
                }
                var form = UTILS.validateForm('#form-new-shipping-zone');
                if(!btn.hasClass('disabled') && form.response == true) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-new-shipping-zone',
                        data: obj,
                        success: function(data) {
                            if(data.save_new_shipping_zone.response == 'ok') {
                                btn.addClass('btn-ok');
                                window.location.href = ADMIN_PATH + '/shipping-zones?new';
                            } else {
                                UTILS.showInfo('Error', data.save_new_shipping_zone.message);
                                btn.removeClass('disabled');
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
        }
    },
    editZoneEvents: function() {
        if($('body#admin-edit-shipping-zone-page').length == 1) {
            var self = this;
            this.getZoneCountries(0);
            this.getZoneProvinces(0);
            this.checkAllInput('#shipping-zone-continents');
            $('#shipping-zone-continents input[type="checkbox"]').on('click', function() {
                self.checkAllInput('#shipping-zone-continents');
            });
            // I create the events for the Select all buttons
            $('#btn-select-all-continents').on("click", function() {
                var check = $(this).prop('checked');
                $('#shipping-zone-continents input').each(function() {
                    $(this).prop('checked', check);
                });
            });
            $('#btn-select-all-countries').on("click", function() {
                var check = $(this).prop('checked');
                $('#shipping-zone-countries input').each(function() {
                    $(this).prop('checked', check);
                });
            });
            $('#btn-select-all-provinces').on("click", function() {
                var check = $(this).prop('checked');
                $('#shipping-zone-provinces input').each(function() {
                    $(this).prop('checked', check);
                });
            });
            $('#select-countries-continents').on("change", function() {
                let id_continent = parseInt($(this).val());
                self.getZoneCountries(id_continent);
            });
            $('#select-provinces-countries').on("change", function() {
                let id_country = parseInt($(this).val());
                self.getZoneProvinces(id_country);
            });
            $('#btn-delete-shipping-zone').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_shipping_zone: parseInt($('#input-id-shipping-zone').val())
                }
                if(!btn.hasClass('disabled')) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/delete-shipping-zone',
                        data: obj,
                        success: function(data) {
                            if(data.delete_shipping_zone.response == 'ok') {
                                window.location.href = ADMIN_PATH + '/shipping-zones?delete';
                            }
                        }
                    });
                }
            });
            $('#btn-save-edit-shipping-zone').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_shipping_zone: parseInt($('#input-id-shipping-zone').val()),
                    name: $('#input-name').val().trim(),
                    id_state: parseInt($('#select-state').val()),
                    continents: [],
                    continents_add: [],
                    countries: [],
                    countries_add: [],
                    provinces: [],
                    provinces_add: []
                }
                $('#shipping-zone-continents input').each(function() {
                    let value = parseInt($(this).val());
                    if(value != 0) {
                        obj.continents.push(value);
                        if($(this).prop('checked')) {
                            obj.continents_add.push(value);
                        }    
                    }
                });
                $('#shipping-zone-countries input').each(function() {
                    let value = parseInt($(this).val());
                    if(value != 0) {
                        obj.countries.push(value);
                        if($(this).prop('checked')) {
                            obj.countries_add.push(value);
                        }
                    }
                });
                $('#shipping-zone-provinces input').each(function() {
                    let value = parseInt($(this).val());
                    if(value != 0) {
                        obj.provinces.push(value);
                        if($(this).prop('checked')) {
                            obj.provinces_add.push(value);
                        }
                    }
                });
                var form = UTILS.validateForm('#form-edit-shipping-zone');
                if(!btn.hasClass('disabled') && form.response == true) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-edit-shipping-zone',
                        data: obj,
                        success: function(data) {
                            if(data.save_edit_shipping_zone.response == 'ok') {
                                UTILS.showInfo('Correct!', data.save_edit_shipping_zone.message);
                            } else {
                                UTILS.showInfo('Error', data.save_edit_shipping_zone.message);
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
    ADMIN_SHIPMENT.init();
});