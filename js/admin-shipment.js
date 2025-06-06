/*
* Author: Diego Martin
* Copyright: Hive®
* Version: 1.0
* Last Update: 2024
*/   

var ADMIN_SHIPMENT = {
    // Init
    init: function() {
        this.newShipmentEvent();
        this.editShipmentEvent();
        this.newShippingZoneEvent();
        this.editShippingZoneEvent();
    },
    // Functions not related to page events
    getShippingZoneCountries: function(id_continent) {
        var obj = {
            id_shipping_zone: parseInt($('#input-id-shipping-zone').val()),
            id_continent: id_continent
        };
        $.ajax({
            url: ADMIN_PATH + '/get-shipping-zone-countries',
            data: obj,
            success: function(data) {
                if(data.get_shipping_zone_countries.response == 'ok') {
                    $('#shipping-zone-countries').html(data.get_shipping_zone_countries.html);
                }
            }
        });
    },
    getShippingZoneProvinces: function(id_country) {
        var obj = {
            id_shipping_zone: parseInt($('#input-id-shipping-zone').val()),
            id_country: id_country
        };
        $.ajax({
            url: ADMIN_PATH + '/get-shipping-zone-provinces',
            data: obj,
            success: function(data) {
                if(data.get_shipping_zone_provinces.response == 'ok') {
                    $('#shipping-zone-provinces').html(data.get_shipping_zone_provinces.html);
                }
            }
        });
    },
    // Events
    newShipmentEvent: function() {
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
    editShipmentEvent: function() {
        if($('body#admin-edit-shipping-method-page').length == 1) {
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
    newShippingZoneEvent: function() {
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
    editShippingZoneEvent: function() {
        if($('body#admin-edit-shipping-zone-page').length == 1) {
            this.getShippingZoneCountries(null);
            this.getShippingZoneProvinces(null);
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
                ADMIN_SHIPMENT.getShippingZoneCountries(id_continent);
            });
            $('#select-provinces-countries').on("change", function() {
                let id_country = parseInt($(this).val());
                ADMIN_SHIPMENT.getShippingZoneProvinces(id_country);
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