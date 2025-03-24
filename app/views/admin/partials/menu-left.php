<div id="menu-left" class="active">
    <div id="btn-hide-menu-left" class="active"><i class="fa-solid fa-angles-left"></i></div>
    <div id="btn-show-menu-left"><i class="fa-solid fa-angles-right"></i></div>
    <div class="content">
        <div class="text-center pt-40 pb-20">
            <a href="<?= ADMIN_PATH ?>/home"><img src="<?= PUBLIC_PATH.'/img/website-logo.png'; ?>" width="60" alt="Hive Framework"></a>
        </div>
        <ul class="custom-collapse no-border dark-bg">
            <li>
                <div class="label no-pointer"><b>Hive Shop</b></div>
            </li>
            <li <?php if(in_array('home', $data['admin']['tags'])) echo 'class="active"'; ?>>
                <a class="label" href="<?= ADMIN_PATH ?>/home"><i class="fa-solid fa-house"></i>&nbsp;&nbsp;Home</a>
            </li>
            <li <?php if(in_array('users', $data['admin']['tags'])) echo 'class="active"'; ?>>
                <a class="label" href="<?= ADMIN_PATH ?>/users"><i class="fa-solid fa-user"></i>&nbsp;&nbsp;Users</a>
            </li>
            <li <?php if(in_array('catalog', $data['admin']['tags'])) echo 'class="active"'; ?>>
                <div class="label"><i class="fa-solid fa-book"></i>&nbsp;&nbsp;Catalog</div>
                <div class="options">
                    <a href="<?= ADMIN_PATH ?>/products"<?php if(in_array('products', $data['admin']['tags'])) echo ' class="active"'; ?>>
                        <i class="fa-solid fa-cube"></i>&nbsp;&nbsp;Products
                    </a>
                    <a href="<?= ADMIN_PATH ?>/categories"<?php if(in_array('categories', $data['admin']['tags'])) echo ' class="active"'; ?>>
                        <i class="fa-solid fa-filter"></i>&nbsp;&nbsp;Categories
                    </a>
                    <a href="<?= ADMIN_PATH ?>/attributes"<?php if(in_array('attributes', $data['admin']['tags'])) echo ' class="active"'; ?>>
                        <i class="fa-solid fa-tag"></i>&nbsp;&nbsp;Attributes
                    </a>
                    <a href="<?= ADMIN_PATH ?>/images"<?php if(in_array('images', $data['admin']['tags'])) echo ' class="active"'; ?>>
                        <i class="fa-regular fa-image"></i></i>&nbsp;&nbsp;Images
                    </a>
                </div>
            </li>
            <li <?php if(in_array('orders-menu', $data['admin']['tags'])) echo 'class="active"'; ?>>
                <div class="label"><i class="fa-solid fa-bag-shopping"></i>&nbsp;&nbsp;Orders</div>
                <div class="options">
                    <a href="<?= ADMIN_PATH ?>/orders"<?php if(in_array('orders', $data['admin']['tags'])) echo ' class="active"'; ?>>
                        <i class="fa-solid fa-file-lines"></i>&nbsp;&nbsp;Orders
                    </a>
                    <a href="<?= ADMIN_PATH ?>/carts"<?php if(in_array('carts', $data['admin']['tags'])) echo ' class="active"'; ?>>
                        <i class="fa-solid fa-cart-shopping"></i>&nbsp;&nbsp;Carts
                    </a>
                    <a href="<?= ADMIN_PATH ?>/codes"<?php if(in_array('codes', $data['admin']['tags'])) echo ' class="active"'; ?>>
                        <i class="fa-regular fa-closed-captioning"></i>&nbsp;&nbsp;Codes
                    </a>
                </div>
            </li>
            <li <?php if(in_array('shipments', $data['admin']['tags'])) echo 'class="active"'; ?>>
                <div class="label"><i class="fa-solid fa-truck"></i>&nbsp;&nbsp;Shipments</div>
                <div class="options">
                    <a href="<?= ADMIN_PATH ?>/shipments"<?php if(in_array('shipments-methods', $data['admin']['tags'])) echo ' class="active"'; ?>>
                        <i class="fa-solid fa-dolly"></i>&nbsp;&nbsp;Shipments
                    </a>
                    <a href="<?= ADMIN_PATH ?>/shipping-zones"<?php if(in_array('shipping-zones', $data['admin']['tags'])) echo ' class="active"'; ?>>
                        <i class="fa-solid fa-map-location-dot"></i>&nbsp;&nbsp;Zones
                    </a>
                </div>
            </li>
            <li <?php if(in_array('payments-menu', $data['admin']['tags'])) echo 'class="active"'; ?>>
                <div class="label"><i class="fa-solid fa-credit-card"></i>&nbsp;&nbsp;Payments</div>
                <div class="options">
                    <a href="<?= ADMIN_PATH ?>/payments"<?php if(in_array('payments', $data['admin']['tags'])) echo ' class="active"'; ?>>
                        <i class="fa-solid fa-wallet"></i>&nbsp;&nbsp;Payments
                    </a>
                    <a href="<?= ADMIN_PATH ?>/payment-zones"<?php if(in_array('payment-zones', $data['admin']['tags'])) echo ' class="active"'; ?>>
                        <i class="fa-solid fa-map-location-dot"></i>&nbsp;&nbsp;Payment zones
                    </a>
                </div>
            </li>
            <li <?php if(in_array('stats-menu', $data['admin']['tags'])) echo 'class="active"'; ?>>
                <div class="label"><i class="fa-solid fa-chart-pie"></i>&nbsp;&nbsp;Stats</div>
                <div class="options">
                    <a href="<?= ADMIN_PATH ?>/stats"<?php if(in_array('stats', $data['admin']['tags'])) echo ' class="active"'; ?>>
                        <i class="fa-solid fa-chart-simple"></i>&nbsp;&nbsp;Stats
                    </a>
                </div>
            </li>
            <li <?php if(in_array('settings', $data['admin']['tags'])) echo 'class="active"'; ?>>
                <div class="label"><i class="fa-solid fa-gear"></i>&nbsp;&nbsp;Settings</div>
                <div class="options">
                    <a href="<?= ADMIN_PATH ?>/languages"<?php if(in_array('languages', $data['admin']['tags'])) echo ' class="active"'; ?>>
                        <i class="fa-solid fa-earth-americas"></i>&nbsp;&nbsp;Languages
                    </a>
                    <a href="<?= ADMIN_PATH ?>/users-admin"<?php if(in_array('users-admin', $data['admin']['tags'])) echo ' class="active"'; ?>>
                        <i class="fa-solid fa-user-secret"></i>&nbsp;&nbsp;Admin Users
                    </a>
                    <?php if(ENVIRONMENT == 'PRE') { ?>
                        <a href="<?= ADMIN_PATH ?>/ftp-upload"<?php if(in_array('ftp-upload', $data['admin']['tags'])) echo ' class="active"'; ?>>
                            <i class="fa-regular fa-file"></i>&nbsp;&nbsp;Ftp Upload
                        </a>
                    <?php } ?>
                </div>
            </li>
            <li>
                <a class="label" href="<?= PUBLIC_ROUTE ?>" target="_blank"><i class="fa-solid fa-desktop"></i>&nbsp;&nbsp;Public Home</a>
            </li>
            <li>
                <a class="label" href="<?= ADMIN_PATH ?>/logout"><i class="fa-solid fa-right-from-bracket"></i>&nbsp;&nbsp;Logout</a>
            </li>
        </ul>
    </div>
</div>