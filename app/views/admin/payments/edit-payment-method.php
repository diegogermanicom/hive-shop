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
                        <div class="title-container underline text-left">Edit payment method</div>
                    </div>
                    <div class="container-xl" id="form-edit-payment">
                        <input type="hidden" id="input-id-payment-method" value="<?= $data['payment_method']['id_payment_method']; ?>">
                        <div class="row pb-20">
                            <div class="col-12 col-sm-9 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Payment alias name *</b></div>
                                <div>
                                    <input type="text" id="input-alias" class="w-100" value="<?= $data['payment_method']['alias']; ?>" validate validate-type="name">
                                </div>
                            </div>
                            <div class="col-12 col-sm-3">
                                <div class="pb-10"><b>State</b></div>
                                <div>
                                    <select id="select-state" class="w-100"><?= $data['states']; ?></select>
                                </div>
                            </div>
                        </div>
                        <div class="row pb-20">
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Minimum value *</b></div>
                                <div>
                                    <input type="number" id="input-min-value" class="w-100" value="<?= $data['payment_method']['min_order_value']; ?>" validate validate-type="number">
                                </div>
                            </div>
                            <div class="col-12 col-sm-3">
                                <div class="pb-10"><b>Maximum value *</b></div>
                                <div>
                                    <input type="number" id="input-max-value" class="w-100" value="<?= $data['payment_method']['max_order_value']; ?>" validate validate-type="number">
                                </div>
                            </div>
                        </div>
                        <div class="pb-10"><b>Languages</b></div>
                        <div class="custom-tab pb-20" id="languages">
                            <div class="menu"><?php

                                foreach($data['languages'] as $index => $value) {
                                    if($index == 0) {
                                        $active = ' class="active"';
                                    } else {
                                        $active = '';
                                    }
                                    echo '<div id-tab="'.($index + 1).'" id-lang="'.$value['id_language'].'"'.$active.'>'.$value['alias'].'</div>';
                                }

                            ?></div>
                            <div class="content"><?php

                                foreach($data['languages'] as $index => $value) {
                                    if($index == 0) {
                                        $active = ' class="active"';
                                    } else {
                                        $active = '';
                                    }

                            ?><div id-tab="<?= ($index + 1); ?>"<?= $active; ?>>
                                    <div class="pb-10"><b>Product name *</b></div>
                                    <div class="pb-20">
                                        <input type="text" class="w-100 input-language-name" value="<?= $value['name']; ?>" validate validate-type="name">
                                    </div>
                                </div><?php

                                }

                            ?></div>
                        </div>
                        <div class="pb-10"><b>Zones</b></div>
                        <div id="payment-method-zones"><?= $data['zones']['html']; ?></div>
                        <div class="text-center pt-40">
                            <div id="btn-delete-payment" class="btn btn-red">Delete</div>
                            <div id="btn-save-edit-payment" class="btn btn-black">Save Payment</div>
                        </div>
                    </div>
                </section>
                <?php include VIEWS_ADMIN.'/partials/footer.php'; ?>
            </div>
        </div>
    </body>
</html>