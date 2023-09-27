<!DOCTYPE html>
<html lang="<?= LANG; ?>">
    <head>
        <?php include VIEWS_ADMIN.'/head.php'; ?>
    </head>
    <body id="<?= $data['admin']['name_page']; ?>">
        <div class="app">
            <?php include VIEWS_ADMIN.'/header.php'; ?>
            <section>
                <div class="container container-sm">
                    <div class="login-content pt-50">
                        <div class="title-container">Restricted access</div>
                        <div>
                            <input type="text" id="input-email" name="input-email" class="w-100" placeholder="Email">
                        </div>
                        <div class="pt-10">
                            <input type="password" id="input-pass" name="input-pass" class="w-100" placeholder="Pass">
                        </div>
                        <div class="pt-20">
                            <div id="btn-send-login" class="btn btn-black w-100"><i class="fa-solid fa-right-to-bracket"></i> Login</div>
                        </div>
                    </div>
                    <div class="text-center pt-20">
                        <a href="<?= PUBLIC_ROUTE ?>/home" class="btn btn-black btn-md"><i class="fa-solid fa-house"></i> Back to Public Home</a>
                    </div>
                </div>
            </section>
            <?php include VIEWS_ADMIN.'/footer.php'; ?>
        </div>
    </body>
</html>