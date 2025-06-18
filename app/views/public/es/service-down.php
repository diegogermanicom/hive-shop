<!DOCTYPE html>
<html lang="<?= LANG; ?>">
    <head>
        <?php include VIEWS_PUBLIC.'/partials/head.php'; ?>
    </head>
    <body id="<?= $data['app']['name_page']; ?>" class="<?= $_COOKIE['color-mode']; ?>">
        <div class="app">
            <section>
                <div class="container">
                    <div class="text-center pt-100">
                        <a href="<?= PUBLIC_ROUTE ?>/home"><img src="<?= PUBLIC_PATH.'/img/website-logo.png'; ?>" width="80" alt="Hive Framework"></a>
                    </div>
                    <div class="text-center pt-30 pb-100">The website is under maintenance.</div>
                </div>
            </section>
            <footer>
                <div class="text-center font-14">Published under <a href="https://opensource.org/licenses/MIT" target="_blank">MIT</a> license.</div>
                <div class="text-center font-14 pt-5">Copyright © <?= date('Y'); ?> <b class="core-color">Hive</b> Framework</div>
            </footer>
        </div>
    </body>
</html>