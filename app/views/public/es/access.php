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
                <div class="container-sm">
                    <div class="box login-content">
                        <?php
                            if(isset($_GET['checkout'])) {
                                $value = 1;
                            } else {
                                $value = 0;
                            }
                        ?>
                        <input type="hidden" value="<?= $value; ?>" id="input-checkout">
                        <div class="pb-10">Iniciar sesión con mi usuario</div>
                        <div>
                            <input id="input-login-email" type="text" placeholder="Correo electrónico" class="w-100">
                        </div>
                        <div class="pt-5">
                            <input id="input-login-pass" type="password" placeholder="Contraseña" class="w-100">
                        </div>
                        <div class="pt-10">
                            <div class="btn btn-black btn-md w-100" id="btn-send-login">Send</div>
                        </div>
                        <div class="pt-10">
                            <label class="checkbox"><input type="checkbox" id="checkbox-login-remember" value="1"><span class="checkmark"></span>Remember me.</label>
                        </div>
                    </div>
                    <div class="box mt-40">
                        <div class="text-center">¿Eres nuevo? Crea una cuenta en tan solo 1 minuto.</div>
                        <div class="pt-10">
                            <?php
                                if(isset($_GET['checkout'])) {
                                    $url_register = PUBLIC_ROUTE.'/registro?checkout';
                                } else {
                                    $url_register = PUBLIC_ROUTE.'/registro';
                                }
                            ?>
                            <a href="<?= $url_register; ?>" class="btn btn-black btn-md w-100">Register</a>
                        </div>
                    </div>
                </div>
            </section>
            <?php include VIEWS_PUBLIC.'/partials/footer.php'; ?>
        </div>
    </body>
</html>