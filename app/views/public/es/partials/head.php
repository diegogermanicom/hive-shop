        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= $data['meta']['title']; ?></title>
        <meta name="description" content="<?= $data['meta']['description']; ?>" />
        <meta name="keywords" content="<?= $data['meta']['keywords']; ?>" />
        <meta name="application-name" content="<?= $data['head']['application-name']; ?>" />
        <meta name="apple-mobile-web-app-title" content="<?= $data['head']['application-name']; ?>" />
        <meta name="author" content="<?= $data['head']['author']; ?>" />
        <meta name="robots" content="<?= $data['head']['robots']; ?>">
        <link rel="canonical" href="<?= $data['head']['canonical']; ?>">
        <!-- Open Graph -->
        <?php
            foreach($data['og'] as $index => $value) {
                echo '<meta property="'.$index.'" content="'.$value.'">';
            }
        ?>
        <!-- Open Graph end -->
        <link rel="icon" href="<?= PUBLIC_PATH; ?>/icono.png" type="image/png">
        <link href="<?= PUBLIC_PATH; ?>/css/vendor/fontawesome.min.css" rel="stylesheet">
        <link href="<?= PUBLIC_PATH; ?>/css/vendor/brands.min.css" rel="stylesheet">
        <link href="<?= PUBLIC_PATH; ?>/css/vendor/solid.min.css" rel="stylesheet">
        <link href="<?= PUBLIC_PATH; ?>/css/vendor/balloon.css" rel="stylesheet">
        <link href="<?= PUBLIC_PATH; ?>/css/vendor/slick.css" rel="stylesheet">
        <link href="<?= PUBLIC_PATH; ?>/css/vendor/slick-theme.css" rel="stylesheet">
        <?php
            if(ENVIRONMENT == 'PRE') {
                echo '<link href="'.PUBLIC_PATH.'/css/core.css?'.uniqid().'" rel="stylesheet">';
                echo '<link href="'.PUBLIC_PATH.'/css/app.css?'.uniqid().'" rel="stylesheet">';
            } else {
                echo '<link href="'.PUBLIC_PATH.'/css/core.css" rel="stylesheet">';
                echo '<link href="'.PUBLIC_PATH.'/css/app.css" rel="stylesheet">';
            }
        ?>
        <script src="<?= PUBLIC_PATH; ?>/js/vendor/jquery-3.3.1.min.js"></script>
        <script src="<?= PUBLIC_PATH; ?>/js/vendor/slick.min.js"></script>
        <script>
            const PUBLIC_PATH = '<?= PUBLIC_PATH; ?>';
            const PUBLIC_ROUTE = '<?= PUBLIC_ROUTE; ?>';
            const ROUTE = '<?= ROUTE; ?>';
        </script>
        <?php
            if(ENVIRONMENT == 'PRE') {
                echo '<script src="'.PUBLIC_PATH.'/js/hive.js?'.uniqid().'"></script>';
                echo '<script src="'.PUBLIC_PATH.'/js/app.js?'.uniqid().'"></script>';    
            } else {
                echo '<script src="'.PUBLIC_PATH.'/js/min/hive.min.js?'.uniqid().'"></script>';
                echo '<script src="'.PUBLIC_PATH.'/js/min/app.min.js?'.uniqid().'"></script>';                
            }
        ?>