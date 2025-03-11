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
                    <div class="box register-content">
                        <?php
                            if(isset($_GET['checkout'])) {
                                $value = 1;
                            } else {
                                $value = 0;
                            }
                        ?>
                        <input type="hidden" value="<?= $value; ?>" id="input-checkout">
                        <div>Formulario rápido de registro</div>
                        <div class="pt-10">
                            <input type="text" class="w-100" id="input-register-email" placeholder="Correo electrónico">
                        </div>
                        <div class="pt-5">
                            <input type="text" class="w-100" id="input-register-name" placeholder="Nombre">
                        </div>
                        <div class="pt-5">
                            <input type="text" class="w-100" id="input-register-lastname" placeholder="Apellidos">
                        </div>
                        <div class="pt-5">
                            <input type="password" class="w-100" id="input-register-pass-1" placeholder="Contraseña">
                        </div>
                        <div class="pt-5">
                            <input type="password" class="w-100" id="input-register-pass-2" placeholder="Repetir contraseña">
                        </div>
                        <div class="pt-10">
                            <label class="checkbox"><input type="checkbox" id="checkbox-register-newsletter" value="1"><span class="checkmark"></span>I want you to send me promotions to my email.</label>
                        </div>
                        <div class="pt-10">
                            <label class="checkbox"><input type="checkbox" id="checkbox-register-accept" value="1"><span class="checkmark"></span>I have read and accept the <a href="<?= PUBLIC_ROUTE.'/privacy-policy'; ?>">privacy</a> and <a href="<?= PUBLIC_ROUTE.'/privacy-policy'; ?>">legal policy</a>.</label>
                        </div>
                        <div class="pt-20">
                            <div class="btn btn-black w-100" id="btn-send-register">Enviar</div>
                        </div>
                    </div>
                </div>
            </section>
            <?php include VIEWS_PUBLIC.'/partials/footer.php'; ?>
        </div>
    </body>
</html>