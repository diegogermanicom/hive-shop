<!DOCTYPE html>
<html lang="<?= LANG; ?>">
    <head>
        <?php include VIEWS_ADMIN.'/partials/head.php'; ?>
    </head>
    <body id="<?= $data['admin']['name_page']; ?>">
        <div class="app">
            <?php include VIEWS_ADMIN.'/partials/header.php'; ?>
            <?php include VIEWS_ADMIN.'/partials/menu-left.php'; ?>
            <div id="container-admin">
                <section>
                    <div class="container">
                        <div class="title-container underline text-left">Edit shipping method</div>
                    </div>
                    <div class="container-lg" id="form-edit-shipment">
                        <input type="hidden" id="input-id-shipping-method" value="<?= $data['shipping_method']['id_shipping_method']; ?>">
                        <div class="row pb-20">
                            <div class="col-12 col-sm-9 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Shipment alias name *</b></div>
                                <div>
                                    <input type="text" id="input-alias" class="w-100" value="<?= $data['shipping_method']['alias']; ?>" validate validate-type="name">
                                </div>
                            </div>
                            <div class="col-12 col-sm-3">
                                <div class="pb-10"><b>State</b></div>
                                <div>
                                    <select id="select-state" class="w-100"><?= $data['product_states']; ?></select>
                                </div>
                            </div>
                        </div>
                        <div class="row pb-20">
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Minimum value *</b></div>
                                <div>
                                    <input type="number" id="input-min-value" class="w-100" value="<?= $data['shipping_method']['min_order_value']; ?>" validate validate-type="number">
                                </div>
                            </div>
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Maximum value *</b></div>
                                <div>
                                    <input type="number" id="input-max-value" class="w-100" value="<?= $data['shipping_method']['max_order_value']; ?>" validate validate-type="number">
                                </div>
                            </div>
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Minimum weight(kg) *</b></div>
                                <div>
                                    <input type="number" id="input-min-weight" class="w-100" value="<?= $data['shipping_method']['min_order_weight']; ?>" validate validate-type="number">
                                </div>
                            </div>
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Maximum weight(kg) *</b></div>
                                <div>
                                    <input type="number" id="input-max-weight" class="w-100" value="<?= $data['shipping_method']['max_order_weight']; ?>" validate validate-type="number">
                                </div>
                            </div>
                        </div>
                        <div class="text-center pt-40">
                            <div id="btn-save-edit-shipment" class="btn btn-black">Save Shipment</div>
                        </div>
                    </div>
                </section>
                <?php include VIEWS_ADMIN.'/partials/footer.php'; ?>
            </div>
        </div>
    </body>
</html>