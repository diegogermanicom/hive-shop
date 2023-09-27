<!DOCTYPE html>
<html lang="<?= LANG; ?>">
    <head>
        <?php include VIEWS_PUBLIC.'/head.php'; ?>
    </head>
    <body id="<?= $data['app']['name_page']; ?>" class="<?= $_COOKIE['color-mode']; ?>">
        <?php include VIEWS_PUBLIC.'/header-body.php'; ?>
        <div class="app">
            <?php include VIEWS_PUBLIC.'/header.php'; ?>
            <section class="pt-40 pb-50">
                <div class="container animate animate-top">
                    <div class="text-center pt-10 mega-title">Bienvenido a<br><span class="accent">Hive Shop</span></div>
                    <div class="text-center pt-10">Es rápido, ligero y sencillo.</div>
                </div>
            </section>
            <?php include VIEWS_PUBLIC.'/footer.php'; ?>
        </div>
    </body>
</html>