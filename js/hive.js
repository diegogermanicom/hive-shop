/*
* Author: Diego Martin
* Copyright: Hive®
* Version: 1.0
* Last Update: 2024
*/   

var HIVE = {
    // Vars
    blockAjax: false,
    konamiCodePosition: 0,
    // Init
    init: function() {
        this.initAjax();
        this.backTopEvent();
        this.btnPopupCloseEvent();
        this.customTabEvent();
        this.colorModeEvent();
        this.scrollEvent();
        this.customCollapse();
        this.customList();
        this.getCustomListValues();
    },
    // Functions
    initAjax: function() {
        $.ajaxSetup({
            type: 'POST',
            dataType: "json",
            error: function() {
                HIVE.showInfo('Error', 'An unexpected error has occurred.<br>Reload the page to try again.');
                HIVE.blockAjax = false;
            }
        });
    },
    getParameterByName: function(name) {
        // returns "" if it does not find the variable with that name
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    },
    showInfo: function(title, text) {
        $('#popup-info .title').html(title);
        $('#popup-info .text').html(text);
        this.showPopup('#popup-info')
    },
    showPopup: function(name) {
        // Blocks any scrolling of the body tag
        var popup_height = $(name).height();
        var content_height = $(name).find('.content').height();
        if(content_height > popup_height) {
            $(name).addClass('popup-top');
        } else {
            $(name).removeClass('popup-top');        
        }
        $(name).addClass('active');
        $('body').addClass('block');    
    },
    closePopup: function(name) {
        // Unlock body tag scroll
        $(name).removeClass('active');
        $('body').removeClass('block');
    },
    validate: function(type, value) {
        // Returns false if the data is not valid and true if it is valid
        var filter = null;
        if(typeof value !== 'string' && typeof value !== 'number') {
            return false;
        }
        if(typeof value === 'string') {
            value = value.toUpperCase();
        }
        switch(type) {
            case 'name':
            case 'lastname':
                filter = /^[A-ZÁÉÍÓÚÄËÏÖÜÀÈÌÒÙÑçªº .\-]{2,50}$/;
                break;
            case 'telephone':
                filter = /^(6[0-9]|7[0-9])\d{7}$/;
                break;
            case 'email':
                value = value.toLowerCase();
                filter = /^[a-z0-9._\-]+@[a-z0-9_\-]+\.(es|com|eu|net|org|info|madrid|com\.es|pt|fr|tv|cat|barcelona|eus|gal|mx|online|app|tienda|blog|io|email|mobi|com\.mx|biz|tech|cloud|page|site|center|futbol|me|abogado|academy|accountant|accountants|actor|africa|agency|airforce|alsace|amsterdam|apartments|app|archi|army|art|asia|associates|attorney|auction|auto|band|bar|bargains|be|beer|best|bet|bid|bike|bingo|bio|biz|black|blue|boston|boutique|brussels|build|builders|business|buzz|bzh|cab|cafe|cam|camera|camp|capetown|capital|car|cards|care|careers|cars|casa|cash|casino|catering|cc|ceo|chat|cheap|church|city|cl|claims|cleaning|clinic|clothing|club|co|coach|codes|coffee|college|cologne|com\.co|com\.es|com\.pe|com\.pt|community|company|computer|condos|construction|consulting|contractors|cooking|cool|country|coupons|credit|creditcard|cricket|cruises|cymru|dance|date|dating|deals|degree|delivery|democrat|dental|dentist|desi|design|dev|diamonds|digital|direct|directory|discount|dog|domains|download|durban|earth|education|email|energy|engineer|engineering|enterprises|equipment|estate|events|exchange|expert|exposed|express|fail|faith|family|fan|fans|farm|fashion|finance|financial|fish|fishing|fit|fitness|flights|florist|football|forsale|foundation|fund|furniture|fyi|gallery|game|garden|gift|gifts|gives|glass|global|gmbh|gold|golf|graphics|gratis|green|gripe|group|guide|guru|haus|healthcare|hiv|hockey|holdings|holiday|horse|hospital|host|house|icu|immo|immobilien|in|industries|info|ink|institute|insure|international|investments|irish|ist|istanbul|jewelry|joburg|kaufen|kim|kitchen|kiwi|koeln|la|land|law|lawyer|lease|legal|lgbt|life|lighting|limited|limo|live|loan|loans|lol|london|love|ltd|ltda|luxury|maison|management|market|marketing|mba|me|media|memorial|men|menu|miami|moda|moe|mom|money|mortgage|movie|nagoya|name|navy|net\.pe|network|news|ninja|nl|nom\.es|nom\.pe|okinawa|one|onl|org\.es|org\.mx|org\.pe|organic|page|paris|partners|parts|party|pe|pet|photo|photography|photos|pictures|pink|pizza|place|plumbing|plus|poker|press|pro|productions|promo|properties|pub|qpon|quebec|racing|recipes|red|rehab|reise|reisen|rent|rentals|repair|report|republican|rest|restaurant|review|reviews|rip|rocks|rodeo|run|ryukyu|saarland|sale|salon|sarl|school|schule|science|scot|services|shoes|shop|shopping|show|singles|ski|soccer|social|software|solar|solutions|space|srl|store|stream|studio|style|supplies|supply|support|surf|surgery|systems|tax|taxi|team|technology|tel|tennis|theater|tips|tires|tirol|today|tokyo|tools|top|tours|town|toys|trade|training|travel|tube|university|uno|vacations|vegas|ventures|vet|viajes|video|villas|vin|vip|vision|vlaanderen|vodka|voyage|wales|watch|webcam|website|wedding|wiki|win|wine|work|works|world|ws|wtf|xyz|yoga|yokohama|zone)$/;
                break;
            case 'cp':
                filter = /^(?:0[1-9]\d{3}|[1-4]\d{4}|5[0-9]\d{3})$/;
                break;
            case 'slug':
                filter = /^[A-Z][0-9A-Z\-]{1,90}$/;
                break;
            case 'price':
                filter = /^([0-9]{1,9})$|^([0-9]+\,+[0-9]{1,2})$/;
                break;
            case 'weight':
                filter = /^([0-9]{1,9})$|^([0-9]+\,+[0-9]{1,3})$/;
                break;
            case 'price-negative':
                filter = /^-?([0-9]{1,9}|[0-9]+[.,][0-9]{1,2})$/;
                break;
            case 'weight-negative':
                filter = /^-?([0-9]{1,9}|[0-9]+[.,][0-9]{1,3})$/;
                break;
            case 'priceES':
                filter = /^([0-9]{1,9})$|^([0-9]+\.+[0-9]{1,2})$/;
                break;
            case 'hexadecimal':
                filter = /^#[0-9A-F]{2,7}$/;
                break;
            case 'no-cero':
                if(parseInt(value) != 0) {
                    value = 1;
                }
                filter = /^[1]$/;
                break;
            case 'date':
                filter = /^([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))$/;
                break;
            case 'number':
                filter = /^[0-9]+$/;
                break;
            case 'code':
                filter = /^[0-9A-Z]{1,20}$/;
                break;
            case 'min-char-3':
                filter = /^.{3,}$/;
                break;
        }
        if(filter == null) {
            return false;
        } else {
            return filter.test(value);
        }
    },
    validateForm: function(content) {
        // Clean the form of previous errors
        $(content).find('*').removeClass('error');
        // I store the name of the fields that have given an error
        var result = {
            response: true,
            errorFields: []
        }
        $(content).find('input[validate][validate-type], select[validate][validate-type], textarea[validate][validate-type]').each(function() {
            const type = $(this).attr('validate-type');
            var val = $(this).val().trim();
            if(!HIVE.validate(type, val)) {
                $(this).addClass('error');
                result.response = false;
                const name = $(this).attr('validate-name');
                if(name != undefined) {
                    result.errorFields.push(name);
                }
            }
        });
        return result;
    },
    konamiCode: function(key) {
        // Just for fun
        var konamiCode = ['up', 'up', 'down', 'down', 'left', 'right', 'left', 'right', 'b', 'a'];
        var allowedKeys = { 37: 'left', 38: 'up', 39: 'right', 40: 'down', 65: 'a', 66: 'b' };
        if(allowedKeys[key] == konamiCode[this.konamiCodePosition]) {
            this.konamiCodePosition++;
            if(this.konamiCodePosition == konamiCode.length) {
                console.log('Konami Code!');
                var audio = new Audio(PUBLIC_PATH + '/audio/konami-code.mp3');
                audio.play();
                this.konamiCodePosition = 0;
            }
        } else {
            this.konamiCodePosition = 0;
        }
    },
    // Events
    backTopEvent: function() {
        $("#back-top").off().on("click", function() {
            $("html, body").animate({scrollTop:0}, 300, 'swing');
        });
    },
    btnPopupCloseEvent: function() {
        $(".popup .btn-popup-close").off().on("click", function() {
            $(this).closest('.popup').removeClass('active');
            $('body').removeClass('block');
        });
    },
    customTabEvent: function() {
        $(".custom-tab .menu > div").off().on("click", function() {
            var id = $(this).attr('id-tab');
            $(this).closest('.custom-tab').find('.menu > div.active').removeClass('active');
            $(this).addClass('active');
            $(this).closest('.custom-tab').find('.content > div.active').removeClass('active');
            $(this).closest('.custom-tab').find('.content > div[id-tab="' + id + '"]').addClass('active');
        });
    },
    colorModeEvent: function() {
        $("#btn-change-color-mode > input").off().on("click", function() {
            var check = $(this);
            if(!check.hasClass('disabled')) {
                check.addClass('disabled');
                check.prop('disabled', true);
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
                        check.prop('disabled', false);
                        check.removeClass('disabled');
                    }
                });    
            }
        });
    },
    scrollEvent: function() {
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
    },
    customCollapse: function() {
        $('.custom-collapse li').each(function() {
            if($(this).children('.options').length != 0) {
                $(this).children('.label').addClass('has-options');
            }
        });
        $('.custom-collapse li .label.has-options').off().on('click', function() {
            var item = $(this).parent('li');
            var hasActive = item.hasClass('active');
            var collapse = $(this).closest('.custom-collapse');
            if(collapse.hasClass('only-one')) {
                collapse.find('li.active').each(function() {
                    $(this).removeClass('active');
                });
            }
            if(item.children('.options').length != 0) {
                if(hasActive) {
                    item.removeClass('active');
                } else {
                    item.addClass('active');
                }    
            }
        });
    },
    customList: function() {
        $('.custom-list[selectable] > *').each(function() {
            $(this).off().on('click', function() {
                var parent = $(this).parent();
                var max = parent.attr('max-select');
                if(parent.attr('multiple') == undefined) {
                    parent.children().removeClass('active');
                }
                // I activate the element
                if($(this).hasClass('active')) {
                    $(this).removeClass('active');
                } else {
                    if(parent.attr('max-select') != undefined) {
                        var num = parent.children('.active').length;
                        if(num >= parent.attr('max-select')) {
                            return false;
                        }
                    }
                    $(this).addClass('active');
                }
            });
        });
    },
    getCustomListValues: function(element) {
        var values = new Array();
        $(element).children('.active').each(function() {
            let value = $(this).attr('value');
            values.push(value);
        });
        return values;
    }
}

$(window).ready(function() {
    HIVE.init();
});        
$(window).on('keydown', function(e) {
    HIVE.konamiCode(e.keyCode);
});
$(window).scroll(function() {
    HIVE.scrollEvent();
});