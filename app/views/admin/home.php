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
                    <div class="container pt-50 pb-40">
                        <div class="text-center pt-10 mega-title">Welcome to<br><span class="accent">Hive Administrator</span></div>
                        <div class="text-center pt-10">Easily manage your application</div>
                    </div>
                </section>
                <section>
                    <div class="container container-sm">
                        <div class="text-center">Welcome <b><?= $_SESSION['admin']['email']; ?></b></div>
                    </div>
                </section>
                <?php include VIEWS_ADMIN.'/footer.php'; ?>
            </div>
        </div>
    </body>
</html>