<!DOCTYPE html>
<html lang="<?= LANG; ?>">
    <head>
        <?php include VIEWS_PUBLIC.'/partials/head.php'; ?>
    </head>
    <body id="<?= $data['app']['name_page']; ?>" class="<?= $_COOKIE['color-mode']; ?>">
        <?php include VIEWS_PUBLIC.'/partials/header-body.php'; ?>
        <div class="app">
            <?php
                include VIEWS_PUBLIC.'/partials/header.php';
                include VIEWS_PUBLIC.'/partials/popup-new-address.php';
                include VIEWS_PUBLIC.'/partials/popup-edit-address.php';
                include VIEWS_PUBLIC.'/partials/popup-new-billing-address.php';
                include VIEWS_PUBLIC.'/partials/popup-edit-billing-address.php';
            ?>
            <section>
                <div class="container-xl">
                    <div class="row">
                        <div class="col-12 col-sm-8">
                            <div class="title-container text-left">¿Tienes un código de descuento?</div>
                            <div>
                                <label class="checkbox" id="btn-checkout-code"><input type="checkbox" id="input-checkout-code" value="1"><span class="checkmark"></span> Sí, tengo un código y me gustaría canjearlo.</label>
                            </div>
                            <div id="code-content" class="hidden pt-10">
                                <div class="row">
                                    <div class="col-12 col-sm-8">
                                        <input type="text" id="input-code" class="w-100" maxlength="25">
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="btn btn-black btn-md w-100" id="btn-apply-code">Aplicar</div>
                                    </div>
                                </div>
                                <div id="code-result" class="hidden pt-10"></div>
                            </div>
                            <div class="title-container text-left pt-40">Dirección de envío</div>
                            <div class="row pb-20" id="address-list"></div>
                            <div class="text-center pb-20">
                                <div class="btn btn-black btn-sm" id="btn-popup-new-address">Crear nueva dirección</div>
                            </div>
                            <div class="text-center">
                                <label class="checkbox" id="btn-billing-address"><input type="checkbox" id="input-check-billing" value="1" checked><span class="checkmark"></span> La dirección de facturación es la misma que la de envío.</label>
                            </div>
                            <div class="hidden" id="billing-content">
                                <div class="title-container text-left pt-40">Dirección de facturación</div>
                                <div class="row pb-20" id="billing-list"></div>
                                <div class="text-center">
                                    <div class="btn btn-black btn-sm" id="btn-popup-new-billing-address">Crear nueva dirección de facturación</div>
                                </div>
                            </div>
                            <div class="title-container text-left pt-40">Selecciona el método de envío</div>
                            <div id="shipping-methods-list"></div>
                            <div class="title-container text-left pt-40">¿Quieres dejarnos algún comentario?</div>
                            <div>
                                <textarea id="textarea-comment" class="w-100"></textarea>
                            </div>
                            <div class="title-container text-left pt-40">Selecciona el método de pago</div>
                            <div>
                                <div>
                                    <label class="radio"><input type="radio" value="1" name="input-payment" class="input-radio-payment" checked><span class="checkmark"></span> Tarjeta de crédito o débito.</label>
                                </div>
                                <div>
                                    <label class="radio"><input type="radio" value="2" name="input-payment" class="input-radio-payment"><span class="checkmark"></span> Transferencia bancaria.</label>
                                </div>
                                <div>
                                    <label class="radio"><input type="radio" value="3" name="input-payment" class="input-radio-payment"><span class="checkmark"></span> Pago contra reembolso.</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4 pl-20 pl-sm-0">
                            <div class="title-container text-left">En tu cesta</div>
                            <div class="row">
                                <div class="col-8">Total</div>
                                <div class="col-4"><?= $data['cart']['total']; ?></div>
                            </div>
                            <div class="content-checkout-cart"><?= $data['cart']['html']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="text-center pt-40">
                    <div class="btn btn-black" id="btn-checkout-payment">Continuar con el pago</div>
                </div>
            </section>
            <?php include VIEWS_PUBLIC.'/partials/footer.php'; ?>
        </div>
    </body>
</html>