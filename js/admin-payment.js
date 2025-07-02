
/**
 * @author Diego Martín
 * @copyright Hive®
 * @version 1.0
 * @lastUpdated 2025
 */

var ADMIN_PAYMENT = {
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
            id_payment_zone: parseInt($('#input-id-payment-zone').val()),
            id_continent: id_continent,
            page: page
        };
        $.ajax({
            url: ADMIN_PATH + '/get-payment-zone-countries',
            data: obj,
            success: function(data) {
                if(data.get_payment_zone_countries.response == 'ok') {
                    $('#payment-zone-countries').html(data.get_payment_zone_countries.html);
                    $('#payment-zone-countries-pager').html(data.get_payment_zone_countries.pager);
                    $('#payment-zone-countries-pager .btn').off().on('click', function() {
                        let loadPage = $(this).data('page');
                        self.getZoneCountries(id_continent, loadPage);
                    });
                    self.checkAllInput('#payment-zone-countries');
                    $('#payment-zone-countries input[type="checkbox"]').off().on('click', function() {
                        self.checkAllInput('#payment-zone-countries');
                    });
                }
            }
        });
    },
    getZoneProvinces: function(id_country, page = 1) {
        var self = this;
        var obj = {
            id_payment_zone: parseInt($('#input-id-payment-zone').val()),
            id_country: id_country,
            page: page
        };
        $.ajax({
            url: ADMIN_PATH + '/get-payment-zone-provinces',
            data: obj,
            success: function(data) {
                if(data.get_payment_zone_provinces.response == 'ok') {
                    $('#payment-zone-provinces').html(data.get_payment_zone_provinces.html);
                    $('#payment-zone-provinces-pager').html(data.get_payment_zone_provinces.pager);
                    $('#payment-zone-provinces-pager .btn').off().on('click', function() {
                        let loadPage = $(this).data('page');
                        self.getZoneProvinces(id_country, loadPage);
                    });
                    self.checkAllInput('#payment-zone-provinces');
                    $('#payment-zone-provinces input[type="checkbox"]').off().on('click', function() {
                        self.checkAllInput('#payment-zone-provinces');
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
    editEvents: function() {
        if($('body#admin-edit-payment-method-page').length == 1) {
            $('#btn-delete-payment').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_payment_method: parseInt($('#input-id-payment-method').val()),
                }
                if(!btn.hasClass('disabled')) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/delete-payment',
                        data: obj,
                        success: function(data) {
                            if(data.delete_payment.response == 'ok') {
                                window.location.href = ADMIN_PATH + '/payments?delete';
                            }
                        }
                    });
                }
            });
            $('#btn-save-edit-payment').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_payment_method: parseInt($('#input-id-payment-method').val()),
                    alias: $('#input-alias').val().trim(),
                    min_value: parseInt($('#input-min-value').val()),
                    max_value: parseInt($('#input-max-value').val()),
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
                $('#payment-method-zones .payment-zone').each(function() {
                    let active = ($(this).find('input[type="checkbox"]').prop('checked')) ? 1 : 0;
                    var zone = {
                        id_payment_zone: $(this).data('id-payment-zone'),
                        active: active
                    }
                    obj.zones.push(zone);
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
    newZoneEvents: function() {
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
    editZoneEvents: function() {
        if($('body#admin-edit-payment-zone-page').length == 1) {
            var self = this;
            this.getZoneCountries(0);
            this.getZoneProvinces(0);        
            this.checkAllInput('#payment-zone-continents');
            $('#payment-zone-continents input[type="checkbox"]').on('click', function() {
                self.checkAllInput('#payment-zone-continents');
            });
            // I create the events for the Select all buttons
            $('#btn-select-all-continents').on("click", function() {
                var check = $(this).prop('checked');
                $('#payment-zone-continents input').each(function() {
                    $(this).prop('checked', check);
                });
            });
            $('#btn-select-all-countries').on("click", function() {
                var check = $(this).prop('checked');
                $('#payment-zone-countries input').each(function() {
                    $(this).prop('checked', check);
                });
            });
            $('#btn-select-all-provinces').on("click", function() {
                var check = $(this).prop('checked');
                $('#payment-zone-provinces input').each(function() {
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
            $('#btn-delete-payment-zone').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_payment_zone: parseInt($('#input-id-payment-zone').val())
                }
                if(!btn.hasClass('disabled')) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/delete-payment-zone',
                        data: obj,
                        success: function(data) {
                            if(data.delete_payment_zone.response == 'ok') {
                                window.location.href = ADMIN_PATH + '/payment-zones?delete';
                            }
                        }
                    });
                }
            });
            $('#btn-save-edit-payment-zone').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_payment_zone: parseInt($('#input-id-payment-zone').val()),
                    name: $('#input-name').val().trim(),
                    id_state: parseInt($('#select-state').val()),
                    continents: [],
                    continents_add: [],
                    countries: [],
                    countries_add: [],
                    provinces: [],
                    provinces_add: []
                }
                $('#payment-zone-continents input').each(function() {
                    let value = parseInt($(this).val());
                    if(value != 0) {
                        obj.continents.push(value);
                        if($(this).prop('checked')) {
                            obj.continents_add.push(value);
                        }    
                    }
                });
                $('#payment-zone-countries input').each(function() {
                    let value = parseInt($(this).val());
                    if(value != 0) {
                        obj.countries.push(value);
                        if($(this).prop('checked')) {
                            obj.countries_add.push(value);
                        }
                    }
                });
                $('#payment-zone-provinces input').each(function() {
                    let value = parseInt($(this).val());
                    if(value != 0) {
                        obj.provinces.push(value);
                        if($(this).prop('checked')) {
                            obj.provinces_add.push(value);
                        }
                    }
                });
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
    }
}

$(window).ready(function() {
    ADMIN_PAYMENT.init();
});