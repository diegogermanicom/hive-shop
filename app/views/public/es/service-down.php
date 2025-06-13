<!DOCTYPE html>
<html lang="<?= LANG; ?>">
    <head>
        <?php include VIEWS_PUBLIC.'/partials/head.php'; ?>
    </head>
    <body id="<?= $data['app']['name_page']; ?>" class="<?= $_COOKIE['color-mode']; ?>">
        <div class="app">
            <section>
                <div class="container-md pt-50 pb-80">
                    <div class="text-center font-20">The website is under maintenance.</div>
                </div>
            </section>
            <footer>
                <div class="text-center font-14">Published under <a href="https://opensource.org/licenses/MIT" target="_blank">MIT</a> license.</div>
                <div class="text-center font-14 pt-5">Copyright © <?= date('Y'); ?> <b class="core-color">Hive</b> Framework</div>
            </footer>
        </div>
    </body>
</html>