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
                    <div class="text-center">Cash Delivery.</div>
                </div>
            </section>
            <?php include VIEWS_PUBLIC.'/partials/footer.php'; ?>
        </div>
    </body>
</html>