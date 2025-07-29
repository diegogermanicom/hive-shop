    <div id="back-top">
        <i class="fa-solid fa-circle-up"></i>
    </div>
    <div id="popup-loading" class="popup">
        <i class="fas fa-spinner fa-spin"></i>
    </div>
    <div id="popup-info" class="popup">
        <div class="content">
            <div class="title"></div>
            <div class="text"></div>
            <div>
                <div class="btn btn-black w-100 btn-popup-close">Aceptar</div>
            </div>                
        </div>
    </div>
    <div id="popup-cookies"<?php if(!(isset($_COOKIE["acepto_cookies"]))) echo ' class="active"'; ?>>
        <div class="content">
            <div>Este sitio web utiliza cookies propias y de terceros para mejorar nuestros servicios y mostrarle publicidad relacionada con sus preferencias mediante el análisis de sus hábitos de navegación. Si continua navegando por la web da su consentimiento sobre su uso. Puede obtener más información en nuestra <a href="<?= PUBLIC_ROUTE.'/privacy-policy'; ?>">Política de Cookies</a>.</div>
            <div class="text-center pt-20">
                <div id="btn-acepta-cookies" class="btn btn-black">Aceptar</div>
            </div>
        </div>
    </div>
    <div id="popup-cart">
        <div id="btn-close-cart"><i class="fa-solid fa-xmark"></i></div>
        <div class="title">Mi cesta</div>
        <div class="content-codes"></div>
        <div class="content-cart"></div>
        <div class="row pb-20">
            <div class="col-6 pt-8">Total (IVA incluido)</div>
            <div class="col-6 text-right">
                <span id="label-popup-cart-total"></span>
            </div>
        </div>
        <div>
            <a href="#" class="btn btn-black w-100 btn-popup-cart-continue">Realizar pedido</a>
        </div>
    </div>
    <header>
        <div class="logo-header">
            <img src="<?= PUBLIC_PATH.'/img/website-logo.png'; ?>" height="100%" alt="Hive Framework">
        </div>
        <ul class="menu animate animate-opacity">
            <li>
                <a href="<?= PUBLIC_ROUTE.'/inicio'; ?>"><i class="fa-solid fa-house"></i> Inicio</a>
            </li>
            <li>
                <a href="<?= ADMIN_PATH; ?>"><i class="fa-solid fa-gear"></i> Administrador</a>
            </li>
        </ul>
        <div class="content-buttons">
            <a href="<?= PUBLIC_ROUTE; ?>/mi-cuenta"><i class="fa-solid fa-user"></i></a>
            <div id="btn-show-cart"><i class="fa-solid fa-cart-shopping"></i><?= $_SESSION["cart_items"]; ?></div>
        </div>
    </header>