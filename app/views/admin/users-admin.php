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
                        <div class="title-container underline text-left">Admin user list</div>
                        <div class="pb-20">
                            <a href="<?= ADMIN_PATH; ?>/new-admin-user" class="btn btn-black">Create new admin user</a>
                        </div>
                        <div><?= $data['users_admin']['html']; ?></div>
                        <div class="pager text-center pt-20"><?= $data['users_admin']['pager']; ?></div>
                    </div>
                </section>
                <?php include VIEWS_ADMIN.'/partials/footer.php'; ?>
            </div>
        </div>
    </body>
</html>