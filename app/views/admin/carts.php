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
                        <div class="title-container underline text-left">Carts list</div>
                        <div><?= $data['carts']['html']; ?></div>
                        <div class="pager text-center pt-20"><?= $data['carts']['pager']; ?></div>
                    </div>
                </section>
                <?php include VIEWS_ADMIN.'/footer.php'; ?>
            </div>
        </div>
    </body>
</html>