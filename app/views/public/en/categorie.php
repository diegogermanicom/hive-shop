<!DOCTYPE html>
<html lang="<?= LANG; ?>">
    <head>
        <?php include VIEWS_PUBLIC.'/head.php'; ?>
    </head>
    <body id="<?= $data['app']['name_page']; ?>" class="<?= $_COOKIE['color-mode']; ?>">
        <?php include VIEWS_PUBLIC.'/header-body.php'; ?>
        <div class="app">
            <?php include VIEWS_PUBLIC.'/header.php'; ?>
            <section>
                <div class="container">
                    <div><?= $data['category']['name']; ?></div>
                </div>
            </section>
            <?php include VIEWS_PUBLIC.'/footer.php'; ?>
        </div>
    </body>
</html>