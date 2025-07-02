
/**
 * @author Diego Martín
 * @copyright Hive®
 * @version 1.0
 * @lastUpdated 2025
 */

var ADMIN_TAX = {
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
            id_tax_zone: parseInt($('#input-id-tax-zone').val()),
            id_continent: id_continent,
            page: page
        };
        $.ajax({
            url: ADMIN_PATH + '/get-tax-zone-countries',
            data: obj,
            success: function(data) {
                if(data.get_tax_zone_countries.response == 'ok') {
                    $('#tax-zone-countries').html(data.get_tax_zone_countries.html);
                    $('#tax-zone-countries-pager').html(data.get_tax_zone_countries.pager);
                    $('#tax-zone-countries-pager .btn').off().on('click', function() {
                        let loadPage = $(this).data('page');
                        self.getZoneCountries(id_continent, loadPage);
                    });
                    self.checkAllInput('#tax-zone-countries');
                    $('#tax-zone-countries input[type="checkbox"]').off().on('click', function() {
                        self.checkAllInput('#tax-zone-countries');
                    });
                }
            }
        });
    },
    getZoneProvinces: function(id_country, page = 1) {
        var self = this;
        var obj = {
            id_tax_zone: parseInt($('#input-id-tax-zone').val()),
            id_country: id_country,
            page: page
        };
        $.ajax({
            url: ADMIN_PATH + '/get-tax-zone-provinces',
            data: obj,
            success: function(data) {
                if(data.get_tax_zone_provinces.response == 'ok') {
                    $('#tax-zone-provinces').html(data.get_tax_zone_provinces.html);
                    $('#tax-zone-provinces-pager').html(data.get_tax_zone_provinces.pager);
                    $('#tax-zone-provinces-pager .btn').off().on('click', function() {
                        let loadPage = $(this).data('page');
                        self.getZoneProvinces(id_country, loadPage);
                    });
                    self.checkAllInput('#tax-zone-provinces');
                    $('#tax-zone-provinces input[type="checkbox"]').off().on('click', function() {
                        self.checkAllInput('#tax-zone-provinces');
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
        if($('body#admin-new-tax-type-page').length == 1) {
            $('#btn-save-new-tax-type').on("click", function() {
                var btn = $(this);
                var obj = {
                    name: $('#input-name').val().trim(),
                    id_state: parseInt($('#select-state').val())
                };
                var form = UTILS.validateForm('#form-new-tax-type');
                if(!btn.hasClass('disabled') && form.response == true) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-new-tax-type',
                        data: obj,
                        success: function(data) {
                            if(data.save_new_tax_type.response == 'ok') {
                                btn.addClass('btn-ok');
                                window.location.href = ADMIN_PATH + '/tax-types?new';
                            } else {s
                                UTILS.showInfo('Error', data.save_new_tax_type.message);
                                btn.removeClass('disabled');
                            }
                        }
                    });
                }
            });
        }
    },
    editEvents: function() {
        if($('body#admin-edit-tax-type-page').length == 1) {
            $('#btn-delete-tax-type').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_tax_type: parseInt($('#input-id-tax-type').val()),
                }
                if(!btn.hasClass('disabled')) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/delete-tax-type',
                        data: obj,
                        success: function(data) {
                            if(data.delete_tax_type.response == 'ok') {
                                window.location.href = ADMIN_PATH + '/tax-types?delete';
                            }
                        }
                    });
                }
            });
            $('#btn-save-edit-tax-type').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_tax_type: parseInt($('#input-id-tax-type').val()),
                    name: $('#input-name').val().trim(),
                    id_state: parseInt($('#select-state').val()),
                    zones: []
                };
                $('#tax-zones .tax-zone').each(function() {
                    let active = ($(this).find('input[type="checkbox"]').prop('checked')) ? 1 : 0;
                    var zone = {
                        id_tax_zone: $(this).data('id-tax-zone'),
                        active: active,
                        percent: $(this).find('input[type="text"]').val().trim()
                    }
                    obj.zones.push(zone);
                });
                var form = UTILS.validateForm('#form-edit-tax-type');
                if(!btn.hasClass('disabled') && form.response == true) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-edit-tax-type',
                        data: obj,
                        success: function(data) {
                            if(data.save_edit_tax_type.response == 'ok') {
                                UTILS.showInfo('Correct!', data.save_edit_tax_type.message);
                            } else {
                                UTILS.showInfo('Error', data.save_edit_tax_type.message);
                            }
                            btn.removeClass('disabled');
                        }
                    });
                }
            });
        }
    },
    newZoneEvents: function() {
        if($('body#admin-new-tax-zone-page').length == 1) {
            $('#btn-save-new-tax-zone').on("click", function() {
                var btn = $(this);
                var obj = {
                    name: $('#input-name').val().trim(),
                    id_state: parseInt($('#select-state').val())
                }
                var form = UTILS.validateForm('#form-new-tax-zone');
                if(!btn.hasClass('disabled') && form.response == true) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-new-tax-zone',
                        data: obj,
                        success: function(data) {
                            if(data.save_new_tax_zone.response == 'ok') {
                                btn.addClass('btn-ok');
                                window.location.href = ADMIN_PATH + '/tax-zones?new';
                            } else {
                                UTILS.showInfo('Error', data.save_new_tax_zone.message);
                                btn.removeClass('disabled');
                            }
                        }
                    });
                }
            });
        }
    },
    editZoneEvents: function() {
        if($('body#admin-edit-tax-zone-page').length == 1) {
            var self = this;
            this.getZoneCountries(0);
            this.getZoneProvinces(0);        
            this.checkAllInput('#tax-zone-continents');
            $('#tax-zone-continents input[type="checkbox"]').on('click', function() {
                self.checkAllInput('#tax-zone-continents');
            });
            // I create the events for the Select all buttons
            $('#btn-select-all-continents').on("click", function() {
                var check = $(this).prop('checked');
                $('#tax-zone-continents input').each(function() {
                    $(this).prop('checked', check);
                });
            });
            $('#btn-select-all-countries').on("click", function() {
                var check = $(this).prop('checked');
                $('#tax-zone-countries input').each(function() {
                    $(this).prop('checked', check);
                });
            });
            $('#btn-select-all-provinces').on("click", function() {
                var check = $(this).prop('checked');
                $('#tax-zone-provinces input').each(function() {
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
            $('#btn-delete-tax-zone').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_tax_zone: parseInt($('#input-id-tax-zone').val())
                }
                if(!btn.hasClass('disabled')) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/delete-tax-zone',
                        data: obj,
                        success: function(data) {
                            if(data.delete_tax_zone.response == 'ok') {
                                window.location.href = ADMIN_PATH + '/tax-zones?delete';
                            }
                        }
                    });
                }
            });
            $('#btn-save-edit-tax-zone').on("click", function() {
                var btn = $(this);
                var obj = {
                    id_tax_zone: parseInt($('#input-id-tax-zone').val()),
                    name: $('#input-name').val().trim(),
                    id_state: parseInt($('#select-state').val()),
                    continents: [],
                    continents_add: [],
                    countries: [],
                    countries_add: [],
                    provinces: [],
                    provinces_add: []
                }
                $('#tax-zone-continents input').each(function() {
                    let value = parseInt($(this).val());
                    if(value != 0) {
                        obj.continents.push(value);
                        if($(this).prop('checked')) {
                            obj.continents_add.push(value);
                        }    
                    }
                });
                $('#tax-zone-countries input').each(function() {
                    let value = parseInt($(this).val());
                    if(value != 0) {
                        obj.countries.push(value);
                        if($(this).prop('checked')) {
                            obj.countries_add.push(value);
                        }
                    }
                });
                $('#tax-zone-provinces input').each(function() {
                    let value = parseInt($(this).val());
                    if(value != 0) {
                        obj.provinces.push(value);
                        if($(this).prop('checked')) {
                            obj.provinces_add.push(value);
                        }
                    }
                });
                var form = UTILS.validateForm('#form-edit-tax-zone');
                if(!btn.hasClass('disabled') && form.response == true) {
                    btn.addClass('disabled');
                    $.ajax({
                        url: ADMIN_PATH + '/save-edit-tax-zone',
                        data: obj,
                        success: function(data) {
                            if(data.save_edit_tax_zone.response == 'ok') {
                                UTILS.showInfo('Correct!', data.save_edit_tax_zone.message);
                            } else {
                                UTILS.showInfo('Error', data.save_edit_tax_zone.message);
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
    ADMIN_TAX.init();
});