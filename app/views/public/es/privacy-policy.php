<!DOCTYPE html>
<html lang="<?= LANG; ?>">
    <head>
        <?php include VIEWS_PUBLIC.'/partials/head.php'; ?>
    </head>
    <body id="<?= $data['app']['name_page']; ?>" class="<?= $_COOKIE['color-mode']; ?>">
        <?php include VIEWS_PUBLIC.'/partials/header-body.php'; ?>
        <div class="app">
            <?php include VIEWS_PUBLIC.'/partials/header.php'; ?>
            <section>
                <div class="container">
                    <div class="text-center pt-100">
                        <a href="<?= PUBLIC_ROUTE ?>/home"><img src="<?= PUBLIC_PATH.'/img/website-logo.png'; ?>" width="80" alt="Hive Framework"></a>
                    </div>
                    <div class="text-center pt-30 pb-100">Política de privacidad.</div>
                </div>
            </section>
            <?php include VIEWS_PUBLIC.'/partials/footer.php'; ?>
        </div>
    </body>
</html>