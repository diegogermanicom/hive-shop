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
                        <div class="title-container underline text-left">Shipping zones list</div>
                        <div class="pb-20">
                            <a href="<?= ADMIN_PATH; ?>/new-shipping-zone" class="btn btn-black">Create new shipping zone</a>
                        </div>
                        <div><?= $data['shipping_zones']['html']; ?></div>
                        <div class="pager text-center pt-20"><?= $data['shipping_zones']['pager']; ?></div>
                    </div>
                </section>
                <?php include VIEWS_ADMIN.'/partials/footer.php'; ?>
            </div>
        </div>
    </body>
</html>