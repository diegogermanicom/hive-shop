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
                <div class="container-sm">
                    <div class="text-center pt-50">
                        <a href="<?= PUBLIC_ROUTE ?>/home"><img src="<?= PUBLIC_PATH.'/img/website-logo.png'; ?>" width="60" alt="Hive Framework"></a>
                    </div>
                    <!-- You can move this code where you want to put your newsletter -->
                    <div class="box register-content mt-50">
                        <div>Fast register user form</div>
                        <div class="pt-10">
                            <input type="text" class="w-100" id="input-register-email" placeholder="Email">
                        </div>
                        <div class="pt-5">
                            <input type="text" class="w-100" id="input-register-name" placeholder="Name">
                        </div>
                        <div class="pt-5">
                            <input type="text" class="w-100" id="input-register-lastname" placeholder="Lastname">
                        </div>
                        <div class="pt-5">
                            <input type="password" class="w-100" id="input-register-pass-1" placeholder="Password">
                        </div>
                        <div class="pt-5">
                            <input type="password" class="w-100" id="input-register-pass-2" placeholder="Repeat password">
                        </div>
                        <div class="pt-10">
                            <label class="checkbox"><input type="checkbox" id="checkbox-register-newsletter" value="1"><span class="checkmark"></span>I want you to send me promotions to my email.</label>
                        </div>
                        <div class="pt-10">
                            <label class="checkbox"><input type="checkbox" id="checkbox-register-accept" value="1"><span class="checkmark"></span>I have read and accept the <a href="<?= PUBLIC_ROUTE.'/privacy-policy'; ?>">privacy</a> and <a href="<?= PUBLIC_ROUTE.'/privacy-policy'; ?>">legal policy</a>.</label>
                        </div>
                        <div class="pt-20">
                            <div class="btn btn-black w-100" id="btn-send-register">Send</div>
                        </div>
                    </div>
                    <!-- end register -->
                </div>
            </section>
            <?php include VIEWS_PUBLIC.'/footer.php'; ?>
        </div>
    </body>
</html>