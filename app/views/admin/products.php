<!DOCTYPE html>
<html lang="<?= LANG; ?>">
    <head>
        <?php include VIEWS_ADMIN.'/head.php'; ?>
    </head>
    <body id="<?= $data['admin']['name_page']; ?>">
        <div class="app">
            <?php include VIEWS_ADMIN.'/header.php'; ?>
            <?php include VIEWS_ADMIN.'/menu-left.php'; ?>
            <div id="container-admin">
                <section>
                    <div class="container">
                        <div class="title-container underline text-left">Products list</div>
                        <div class="pb-20">
                            <a href="<?= ADMIN_PATH; ?>/new-product" class="btn btn-black">Create new product</a>
                            <a href="<?= ADMIN_PATH; ?>/products-custom-routes" class="btn btn-black">Products custom routes</a>
                        </div>
                        <div id="products-content"><?= $data['products']['html']; ?></div>
                        <div class="pager text-center pt-20"><?= $data['products']['pager']; ?></div>
                    </div>
                </section>
                <?php include VIEWS_ADMIN.'/footer.php'; ?>
            </div>
        </div>
    </body>
</html>