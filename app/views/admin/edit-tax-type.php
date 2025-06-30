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
                        <div class="title-container underline text-left">Edit tax type</div>
                    </div>
                    <div class="container-xl" id="form-edit-tax-type">
                        <input type="hidden" id="input-id-tax-type" value="<?= $data['tax']['id_tax_type']; ?>">
                        <div class="row pb-20">
                            <div class="col-12 col-sm-9 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Tax name *</b></div>
                                <div>
                                    <input type="text" id="input-name" class="w-100" value="<?= $data['tax']['name']; ?>" validate validate-type="name">
                                </div>
                            </div>
                            <div class="col-12 col-sm-3">
                                <div class="pb-10"><b>State</b></div>
                                <div>
                                    <select id="select-state" class="w-100"><?= $data['states']; ?></select>
                                </div>
                            </div>
                        </div>
                        <div class="pb-10"><b>Zones</b></div>
                        <div id="tax-zones"><?= $data['zones']['html']; ?></div>
                        <div class="text-center pt-40">
                            <div id="btn-delete-tax-type" class="btn btn-red">Delete</div>
                            <div id="btn-save-edit-tax-type" class="btn btn-black">Save tax type</div>
                        </div>
                    </div>
                </section>
                <?php include VIEWS_ADMIN.'/partials/footer.php'; ?>
            </div>
        </div>
    </body>
</html>