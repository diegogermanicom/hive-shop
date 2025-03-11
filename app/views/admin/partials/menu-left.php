<div id="menu-left" class="active">
    <div id="btn-hide-menu-left" class="active"><i class="fa-solid fa-angles-left"></i></div>
    <div id="btn-show-menu-left"><i class="fa-solid fa-angles-right"></i></div>
    <div class="content">
        <div class="text-center pt-40 pb-20">
            <a href="<?= ADMIN_PATH ?>/home"><img src="<?= PUBLIC_PATH.'/img/website-logo.png'; ?>" width="60" alt="Hive Framework"></a>
        </div>
        <nav>
            <a href="<?= ADMIN_PATH ?>/home"<?php if(in_array($data['admin']['name_page'], $data['menu']['home'])) echo ' class="active"'; ?>><i class="fa-solid fa-house"></i>&nbsp;&nbsp;Home</a>
        </nav>
        <div class="separator"></div>
        <nav>
            <a href="<?= ADMIN_PATH ?>/products"<?php if(in_array($data['admin']['name_page'], $data['menu']['products'])) echo ' class="active"'; ?>><i class="fa-solid fa-cube"></i>&nbsp;&nbsp;Products</a>
            <a href="<?= ADMIN_PATH ?>/categories"<?php if(in_array($data['admin']['name_page'], $data['menu']['categories'])) echo ' class="active"'; ?>><i class="fa-solid fa-filter"></i>&nbsp;&nbsp;Categories</a>
            <a href="<?= ADMIN_PATH ?>/attributes"<?php if(in_array($data['admin']['name_page'], $data['menu']['attributes'])) echo ' class="active"'; ?>><i class="fa-solid fa-tag"></i>&nbsp;&nbsp;Attributes</a>
            <a href="<?= ADMIN_PATH ?>/images"<?php if(in_array($data['admin']['name_page'], $data['menu']['images'])) echo ' class="active"'; ?>><i class="fa-regular fa-image"></i></i>&nbsp;&nbsp;Images</a>
        </nav>
        <div class="separator"></div>
        <nav>
            <a href="<?= ADMIN_PATH ?>/codes"<?php if(in_array($data['admin']['name_page'], $data['menu']['codes'])) echo ' class="active"'; ?>><i class="fa-regular fa-closed-captioning"></i>&nbsp;&nbsp;Codes</a>
            <a href="<?= ADMIN_PATH ?>/carts"<?php if(in_array($data['admin']['name_page'], $data['menu']['carts'])) echo ' class="active"'; ?>><i class="fa-solid fa-cart-shopping"></i>&nbsp;&nbsp;Carts</a>
            <a href="<?= ADMIN_PATH ?>/orders"<?php if(in_array($data['admin']['name_page'], $data['menu']['orders'])) echo ' class="active"'; ?>><i class="fa-solid fa-truck"></i>&nbsp;&nbsp;Orders</a>
            <a href="<?= ADMIN_PATH ?>/languages"<?php if(in_array($data['admin']['name_page'], $data['menu']['languages'])) echo ' class="active"'; ?>><i class="fa-solid fa-earth-americas"></i>&nbsp;&nbsp;Languages</a>
            <a href="<?= ADMIN_PATH ?>/stats"<?php if(in_array($data['admin']['name_page'], $data['menu']['stats'])) echo ' class="active"'; ?>><i class="fa-solid fa-chart-simple"></i>&nbsp;&nbsp;Stats</a>
        </nav>
        <div class="separator"></div>
        <nav>
            <a href="<?= ADMIN_PATH ?>/users"<?php if(in_array($data['admin']['name_page'], $data['menu']['users'])) echo ' class="active"'; ?>><i class="fa-solid fa-user"></i>&nbsp;&nbsp;Users</a>
            <a href="<?= ADMIN_PATH ?>/users-admin"<?php if(in_array($data['admin']['name_page'], $data['menu']['admin_users'])) echo ' class="active"'; ?>><i class="fa-solid fa-user-secret"></i>&nbsp;&nbsp;Admin Users</a>
            <?php if(ENVIRONMENT == 'PRE') { ?>
                <a href="<?= ADMIN_PATH ?>/ftp-upload"<?php if(in_array($data['admin']['name_page'], $data['menu']['ftp_upload'])) echo ' class="active"'; ?>><i class="fa-regular fa-file"></i>&nbsp;&nbsp;Ftp Upload</a>
            <?php } ?>
        </nav>
        <div class="separator"></div>
        <nav>
            <a href="<?= PUBLIC_ROUTE ?>"><i class="fa-solid fa-desktop"></i>&nbsp;&nbsp;Public Home</a>
            <a href="<?= ADMIN_PATH ?>/logout"><i class="fa-solid fa-right-from-bracket"></i>&nbsp;&nbsp;Logout</a>
        </nav>
    </div>
</div>