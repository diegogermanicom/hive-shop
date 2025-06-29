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
                        <div class="title-container underline text-left">Edit shipping zone</div>
                    </div>
                    <div class="container-xl" id="form-edit-shipping-zone">
                        <input type="hidden" id="input-id-shipping-zone" value="<?= $data['shipping_zone']['id_shipping_zone']; ?>">
                        <div class="row pb-20">
                            <div class="col-12 col-sm-9 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Shipping zone name *</b></div>
                                <div>
                                    <input type="text" id="input-name" class="w-100" validate validate-type="name" value="<?= $data['shipping_zone']['name']; ?>">
                                </div>
                            </div>
                            <div class="col-12 col-sm-3">
                                <div class="pb-10"><b>State</b></div>
                                <div>
                                    <select id="select-state" class="w-100"><?= $data['states']; ?></select>
                                </div>
                            </div>
                        </div>
                        <div class="pb-10"><b>Locations</b></div>
                        <div id="locations-content">
                            <div class="custom-tab">
                                <div class="menu">
                                    <div id-tab="1" class="active">Continents</div>
                                    <div id-tab="2">Countries</div>
                                    <div id-tab="3">Provinces</div>
                                </div>
                                <div class="content">
                                    <div id-tab="1" class="active">
                                        <div class="pb-20">
                                            <label class="checkbox">
                                                <input type="checkbox" value="0" id="btn-select-all-continents" class="btn-select-all">
                                                <span class="checkmark"></span>Select all
                                            </label>
                                        </div>
                                        <div id="shipping-zone-continents"><?= $data['continents']; ?></div>
                                    </div>
                                    <div id-tab="2">
                                        <div class="row pb-20">
                                            <div class="col-12 col-sm-6 pt-10">
                                                <label class="checkbox">
                                                    <input type="checkbox" value="0" id="btn-select-all-countries" class="btn-select-all">
                                                    <span class="checkmark"></span>Select all
                                                </label>
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <select id="select-countries-continents">
                                                    <option value="0">All</option>
                                                    <?= $data['continents_select']; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="shipping-zone-countries"></div>
                                        <div id="shipping-zone-countries-pager" class="pager text-center pt-20"></div>
                                    </div>
                                    <div id-tab="3">
                                        <div class="row pb-20">
                                            <div class="col-12 col-sm-6 pt-10">
                                                <label class="checkbox">
                                                    <input type="checkbox" value="0" id="btn-select-all-provinces" class="btn-select-all">
                                                    <span class="checkmark"></span>Select all
                                                </label>
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <select id="select-provinces-countries">
                                                    <option value="0">All</option>
                                                    <?= $data['countries_select']; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="shipping-zone-provinces"></div>
                                        <div id="shipping-zone-provinces-pager" class="pager text-center pt-20"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center pt-40">
                            <div id="btn-delete-shipping-zone" class="btn btn-red">Delete</div>
                            <div id="btn-save-edit-shipping-zone" class="btn btn-black">Save shipping zone</div>
                        </div>
                    </div>
                </section>
                <?php include VIEWS_ADMIN.'/partials/footer.php'; ?>
            </div>
        </div>
    </body>
</html>