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
                <div class="btn btn-black w-100 btn-popup-close">Continue</div>
            </div>                
        </div>
    </div>
    <div id="popup-cookies"<?php if(!(isset($_COOKIE["acepto_cookies"]))) echo ' class="active"'; ?>>
        <div class="content">
            <div>This website uses its own and third-party cookies to improve our services and show you advertising related to your preferences by analyzing your browsing habits. If you continue browsing the web, you consent to its use. You can obtain more information in our <a href="<?= PUBLIC_ROUTE.'/privacy-policy'; ?>">Cookie Policy</a>.</div>
            <div class="text-center pt-20">
                <div id="btn-acepta-cookies" class="btn btn-black">Continue</div>
            </div>
        </div>
    </div>
    <header>
        <div class="logo-header">
            <img src="<?= PUBLIC_PATH.'/img/website-logo.png'; ?>" height="100%" alt="Hive Framework">
        </div>
        <ul class="menu animate animate-opacity">
            <li>
                <a href="<?= PUBLIC_ROUTE.'/home'; ?>"><i class="fa-solid fa-house"></i> Home</a>
            </li>
            <li>
                <a href="<?= ADMIN_PATH; ?>"><i class="fa-solid fa-gear"></i> Administrator</a>
            </li>
        </ul>
        <div class="header-content-right">
            <label class="switch" id="btn-change-color-mode">
                <input type="checkbox" value="1"<?php if($_COOKIE["color-mode"] == 'dark-mode') echo ' checked'; ?>>
                <span class="slider round"></span>
            </label>
        </div>
    </header>