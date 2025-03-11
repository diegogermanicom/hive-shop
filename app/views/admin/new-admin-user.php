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
                    <div class="container content-new-admin-user">
                        <div class="title-container underline text-left">Create new admin user</div>
                        <div class="row pb-20">
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>First name *</b></div>
                                <div>
                                    <input type="text" id="input-name" class="w-100" maxlength="90">
                                </div>
                            </div>
                            <div class="col-12 col-sm-4 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Last name *</b></div>
                                <div>
                                    <input type="text" id="input-last-name" class="w-100" maxlength="120">
                                </div>
                            </div>
                            <div class="col-12 col-sm-5">
                                <div class="pb-10"><b>Email *</b></div>
                                <div>
                                    <input type="text" id="input-email" class="w-100" maxlength="90">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Password *</b> (numbers, letters or "-_.")</div>
                                <div>
                                    <input type="password" id="input-password-1" class="w-100" maxlength="90">
                                </div>
                            </div>
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Repeat password *</b></div>
                                <div>
                                    <input type="password" id="input-password-2" class="w-100" maxlength="90">
                                </div>
                            </div>
                            <div class="col-12 col-sm-3">
                                <div class="pb-10"><b>Type</b></div>
                                <div>
                                    <select id="select-admin-type" class="w-100"><?= $data['admin_type']; ?></select>
                                </div>
                            </div>
                        </div>
                        <div class="text-center pt-40">
                            <div id="btn-save-new-admin-user" class="btn btn-black">Create Admin User</div>
                        </div>
                    </div>
                </section>
                <?php include VIEWS_ADMIN.'/partials/footer.php'; ?>
            </div>
        </div>
    </body>
</html>