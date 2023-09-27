<!DOCTYPE html>
<html lang="<?= LANG; ?>">
    <head>
        <meta charset="UTF-8">
        <title><?= $data['meta']['title']; ?></title>
        <meta name="robots" content="noindex, nofollow">
        <link rel="icon" href="<?= PUBLIC_PATH; ?>/icono.png" type="image/png">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css?family=Maven+Pro:400,500,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
        <link href="<?= PUBLIC_PATH; ?>/css/app.css?<?= uniqid(); ?>" rel="stylesheet">
    </head>
    <body id="<?= $data['app']['name_page']; ?>" class="<?= $_COOKIE['color-mode']; ?>">
        <div class="app">
            <section>
                <div class="container container-lg">
                    <div class="text-center pt-50">
                        <a href="<?= PUBLIC_ROUTE ?>/home"><img src="<?= PUBLIC_PATH.'/img/hive-logo.png'; ?>" width="60" alt="Hive Framework"></a>
                    </div>
                    <div class="title-container underline text-left pt-30"><?= $data['error_title']; ?></div>
                    <div class="texto-container pb-100"><?= $data['error_description']; ?></div>
                </div>
            </section>
        </div>
    </body>
</html>