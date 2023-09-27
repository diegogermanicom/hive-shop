/*
* Author: Diego Martin
* Copyright: Hive®
* Version: 1.0
* Last Update: 2023
*/   

var _konamiCodePosition = 0;

$(window).ready(function() {
    assets_events();
    events_custom_tab();
});

$(window).on('keydown', function(e) {
    konamiCode(e.keyCode);
});

function assets_events() {
    $("#back-top").on("click", function() {
        $("html, body").animate({scrollTop:0}, 300, 'swing');
    });
    $(".popup .btn-popup-close").on("click", function() {
        if(!$(this).hasClass('disabled')) {
            $(this).closest('.popup').removeClass('active');
            $('body').removeClass('block');
        }
    });
}

function events_custom_tab() {
    $(".custom-tab .menu > div").off().on("click", function() {
        var id = $(this).attr('id-tab');
        $(this).closest('.custom-tab').find('.menu > div.active').removeClass('active');
        $(this).addClass('active');
        $(this).closest('.custom-tab').find('.content > div.active').removeClass('active');
        $(this).closest('.custom-tab').find('.content > div[id-tab="' + id + '"]').addClass('active');
    });    
}

function show_info(title, text) {
    $('body').addClass('block');
    $('#popup-info .title').html(title);
    $('#popup-info .texto').html(text);
    $('#popup-info').addClass('active');    
}

function show_popup(name) {
    var popup_height = $(name).height();
    var content_height = $(name).find('.content').height();
    if(content_height > popup_height) {
        $(name).addClass('popup-top');
    } else {
        $(name).removeClass('popup-top');        
    }
    $(name).addClass('active');
    $('body').addClass('block');
}

function close_popup(name) {
    $(name).removeClass('active');
    $('body').removeClass('block');
}

function validar(tipo, valor) {
	var filtro;
    valor = valor.toUpperCase();
	switch (tipo) {
		case 'nombre':
		case 'apellidos':
			filtro = /^[A-ZÁÉÍÓÚÄËÏÖÜÀÈÌÒÙÑªº .\-]{2,50}$/;
			break;
		case 'telefono':
			filtro = /^(6[0-9]|7[0-9])\d{7}$/;
			break;
		case 'email':
			valor = valor.toLowerCase();
			filtro = /^[a-z0-9._\-]+@[a-z0-9_\-]+\.(es|com|eu|net|org|info|madrid|com\.es|pt|fr|tv|cat|barcelona|eus|gal|mx|online|app|tienda|blog|io|email|mobi|com\.mx|biz|tech|cloud|page|site|center|futbol|me|abogado|academy|accountant|accountants|actor|africa|agency|airforce|alsace|amsterdam|apartments|app|archi|army|art|asia|associates|attorney|auction|auto|band|bar|bargains|be|beer|best|bet|bid|bike|bingo|bio|biz|black|blue|boston|boutique|brussels|build|builders|business|buzz|bzh|cab|cafe|cam|camera|camp|capetown|capital|car|cards|care|careers|cars|casa|cash|casino|catering|cc|ceo|chat|cheap|church|city|cl|claims|cleaning|clinic|clothing|club|co|coach|codes|coffee|college|cologne|com\.co|com\.es|com\.pe|com\.pt|community|company|computer|condos|construction|consulting|contractors|cooking|cool|country|coupons|credit|creditcard|cricket|cruises|cymru|dance|date|dating|deals|degree|delivery|democrat|dental|dentist|desi|design|dev|diamonds|digital|direct|directory|discount|dog|domains|download|durban|earth|education|email|energy|engineer|engineering|enterprises|equipment|estate|events|exchange|expert|exposed|express|fail|faith|family|fan|fans|farm|fashion|finance|financial|fish|fishing|fit|fitness|flights|florist|football|forsale|foundation|fund|furniture|fyi|gallery|game|garden|gift|gifts|gives|glass|global|gmbh|gold|golf|graphics|gratis|green|gripe|group|guide|guru|haus|healthcare|hiv|hockey|holdings|holiday|horse|hospital|host|house|icu|immo|immobilien|in|industries|info|ink|institute|insure|international|investments|irish|ist|istanbul|jewelry|joburg|kaufen|kim|kitchen|kiwi|koeln|la|land|law|lawyer|lease|legal|lgbt|life|lighting|limited|limo|live|loan|loans|lol|london|love|ltd|ltda|luxury|maison|management|market|marketing|mba|me|media|memorial|men|menu|miami|moda|moe|mom|money|mortgage|movie|nagoya|name|navy|net\.pe|network|news|ninja|nl|nom\.es|nom\.pe|okinawa|one|onl|org\.es|org\.mx|org\.pe|organic|page|paris|partners|parts|party|pe|pet|photo|photography|photos|pictures|pink|pizza|place|plumbing|plus|poker|press|pro|productions|promo|properties|pub|qpon|quebec|racing|recipes|red|rehab|reise|reisen|rent|rentals|repair|report|republican|rest|restaurant|review|reviews|rip|rocks|rodeo|run|ryukyu|saarland|sale|salon|sarl|school|schule|science|scot|services|shoes|shop|shopping|show|singles|ski|soccer|social|software|solar|solutions|space|srl|store|stream|studio|style|supplies|supply|support|surf|surgery|systems|tax|taxi|team|technology|tel|tennis|theater|tips|tires|tirol|today|tokyo|tools|top|tours|town|toys|trade|training|travel|tube|university|uno|vacations|vegas|ventures|vet|viajes|video|villas|vin|vip|vision|vlaanderen|vodka|voyage|wales|watch|webcam|website|wedding|wiki|win|wine|work|works|world|ws|wtf|xyz|yoga|yokohama|zone)$/;
			break;
		case 'cp':
			filtro = /^(?:0[1-9]\d{3}|[1-4]\d{4}|5[0-9]\d{3})$/;
			break;
		case 'slug':
			filtro = /^[A-Z][0-9A-Z\-]{1,90}$/;
			break;
		case 'price':
			filtro = /^([0-9]{1,9})$|^([0-9]+\,+[0-9]{1,2})$/;
			break;
		case 'product':
			filtro = /^[0-9A-ZÁÉÍÓÚÄËÏÖÜÀÈÌÒÙÑªº .\-]{2,50}$/;
			break;
        case 'hexadecimal':
			filtro = /^#[0-9A-F]{2,7}$/;
            break;
        case 'no-cero':
            if(parseInt(valor) != 0) {
                valor = 1;
            }
            filtro = /^[1]$/;
            break;
        case 'code':
			filtro = /^[0-9A-Z]{1,20}$/;
            break;
        case 'date':
			filtro = /^([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))$/;
            break;
        case 'number':
			filtro = /^[0-9]+$/;
            break;        
	}
	return filtro.test(valor);
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function konamiCode(k) {
    var konamiCode = ['up', 'up', 'down', 'down', 'left', 'right', 'left', 'right', 'b', 'a'];
    var allowedKeys = { 37: 'left', 38: 'up', 39: 'right', 40: 'down', 65: 'a', 66: 'b' };
    if(allowedKeys[k] == konamiCode[_konamiCodePosition]) {
        _konamiCodePosition++;
        if(_konamiCodePosition == konamiCode.length) {
            console.log('Konami Code!');
            var audio = new Audio(PUBLIC_PATH + '/audio/konami-code.mp3');
            audio.play();
            _konamiCodePosition = 0;
        }
    } else {
        _konamiCodePosition = 0;
    }
}