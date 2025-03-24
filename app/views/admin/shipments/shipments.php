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
                        <div class="title-container underline text-left">Shipping methods list</div>
                        <div class="pb-20">
                            <a href="<?= ADMIN_PATH; ?>/new-shipping-method" class="btn btn-black">Create new shipping method</a>
                        </div>
                        <div><?= $data['shipments']['html']; ?></div>
                        <div class="pager text-center pt-20"><?= $data['shipments']['pager']; ?></div>
                    </div>
                </section>
                <?php include VIEWS_ADMIN.'/partials/footer.php'; ?>
            </div>
        </div>
    </body>
</html>