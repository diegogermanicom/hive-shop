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
                        <div class="title-container underline text-left">Category list</div>
                        <div class="pb-20">
                            <a href="<?= ADMIN_PATH; ?>/new-category" class="btn btn-black">Create new category</a>
                            <a href="<?= ADMIN_PATH; ?>/categories-custom-routes" class="btn btn-black">categories custom routes</a>
                        </div>
                        <div id="categories-content"><?= $data['categories']['html']; ?></div>
                        <div class="pager text-center pt-20"><?= $data['categories']['pager']; ?></div>
                    </div>
                </section>
                <?php include VIEWS_ADMIN.'/partials/footer.php'; ?>
            </div>
        </div>
    </body>
</html>