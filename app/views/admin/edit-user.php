<!DOCTYPE html>
<html lang="<?= LANG; ?>">
    <head>
        <?php include VIEWS_ADMIN.'/partials/head.php'; ?>
    </head>
    <body id="<?= $data['admin']['name_page']; ?>">
        <div class="app">
            <?php include VIEWS_ADMIN.'/partials/header.php'; ?>
            <?php include VIEWS_ADMIN.'/partials/menu-left.php'; ?>
            <div id="popup-edit-address" class="popup">
                <div class="content content-md">
                    <div class="title">EDIT ADDRESS</div>
                    <div class="content-edit-address pb-20">
                        <div class="row pb-20">
                            <div class="col-4 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Continent</b></div>
                                <div>
                                    <select id="input-edit-address-continent" class="w-100"><?= $data['continents']; ?></select>
                                </div>
                            </div>
                            <div class="col-4 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Country</b></div>
                                <div>
                                    <select id="input-edit-address-country" class="w-100"></select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="pb-10"><b>Province</b></div>
                                <div>
                                    <select id="input-edit-address-province" class="w-100"></select>
                                </div>
                            </div>
                        </div>
                        <div class="row pb-20">
                            <div class="col-12">
                                <div class="pb-10"><b>Address *</b></div>
                                <div>
                                    <input type="text" id="input-edit-address-address" class="w-100" maxlength="255">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Location *</b></div>
                                <div>
                                    <input type="text" id="input-edit-address-location" class="w-100" maxlength="150">
                                </div>
                            </div>
                            <div class="col-4 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Postal code *</b></div>
                                <div>
                                    <input type="text" id="input-edit-address-postal-code" class="w-100" maxlength="10">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="pb-10"><b>Telephone *</b></div>
                                <div>
                                    <input type="text" id="input-edit-address-telephone" class="w-100" maxlength="20">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6 pr-5 pr-sm-5">
                            <div id="btn-save-edit-address" class="btn btn-black w-100">Save address</div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="btn btn-black w-100 btn-popup-close">Close</div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="container-admin">
                <section>
                    <div class="container content-edit-user">
                        <input type="hidden" id="input-id-user" value="<?= $data['user']['id_user']; ?>">
                        <div class="title-container underline text-left">Edit user #<?= $data['user']['id_user']; ?></div>
                        <div class="row pb-20">
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>First name *</b></div>
                                <div>
                                    <input type="text" id="input-name" class="w-100" maxlength="90" value="<?= $data['user']['name']; ?>">
                                </div>
                            </div>
                            <div class="col-12 col-sm-4 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Last name *</b></div>
                                <div>
                                    <input type="text" id="input-last-name" class="w-100" maxlength="120" value="<?= $data['user']['lastname']; ?>">
                                </div>
                            </div>
                            <div class="col-12 col-sm-5">
                                <div class="pb-10"><b>Email *</b></div>
                                <div>
                                    <input type="text" id="input-email" class="w-100" maxlength="90" value="<?= $data['user']['email']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row pb-20">
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>User state</b></div>
                                <div>
                                    <select id="select-state" class="w-100"><?= $data['user_states']; ?></select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Registration date</b></div>
                                <div>
                                    <input type="text" class="w-100" value="<?= $data['user']['insert_date']; ?>" readonly disabled>
                                </div>
                            </div>
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Registration IP</b></div>
                                <div>
                                    <input type="text" class="w-100" value="<?= $data['user']['ip_register']; ?>" readonly disabled>
                                </div>
                            </div>
                            <div class="col-12 col-sm-3">
                                <div class="pb-10"><b>Last access IP</b></div>
                                <div>
                                    <input type="text" class="w-100" value="<?= $data['user']['ip_last_access']; ?>" readonly disabled>
                                </div>
                            </div>
                        </div>
                        <div class="pb-10"><b>User addresses</b></div>
                        <div id="content-addresses"></div>
                        <div class="text-center pt-40">
                            <div id="btn-close-user-sessions" class='btn btn-black btn-md'>Close Sessions</div>
                            <div id="btn-resend-validation-email" class='btn btn-black btn-md'>Resend Validation Email</div>
                        </div>
                        <div class="text-center pt-20">
                            <div id="btn-save-edit-user" class="btn btn-black">Save User</div>
                        </div>
                    </div>
                </section>
                <?php include VIEWS_ADMIN.'/partials/footer.php'; ?>
            </div>
        </div>
    </body>
</html>