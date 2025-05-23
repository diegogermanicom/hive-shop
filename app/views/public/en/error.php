<!DOCTYPE html>
<html lang="<?= LANG; ?>">
    <head>
        <meta charset="UTF-8">
        <title><?= $data['meta']['title']; ?></title>
        <meta name="robots" content="noindex, nofollow">
        <link rel="icon" href="<?= PUBLIC_PATH; ?>/icono.png" type="image/png">
        <?php
            if(ENVIRONMENT == 'DEV') {
                echo '<link href="'.PUBLIC_PATH.'/css/core.css?'.uniqid().'" rel="stylesheet">';
                echo '<link href="'.PUBLIC_PATH.'/css/app.css?'.uniqid().'" rel="stylesheet">';
            } else {
                echo '<link href="'.PUBLIC_PATH.'/css/core.css" rel="stylesheet">';
                echo '<link href="'.PUBLIC_PATH.'/css/app.css" rel="stylesheet">';
            }
        ?>
    </head>
    <body id="<?= $data['app']['name_page']; ?>" class="<?= $_COOKIE['color-mode']; ?>">
        <div class="app">
            <section>
                <div class="container-lg">
                    <div class="text-center pt-50">
                        <img src="<?= PUBLIC_PATH.'/img/website-logo.png'; ?>" alt="Hive Framework" width="60">
                    </div>
                    <div class="title-container underline text-left pt-30"><?= $data['error_title']; ?></div>
                    <div class="text-container pb-100"><?= $data['error_description']; ?></div>
                </div>
            </section>
        </div>
    </body>
</html>