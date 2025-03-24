<!DOCTYPE html>
<html lang="<?= LANG; ?>">
    <head>
        <?php include VIEWS_ADMIN.'/partials/head.php'; ?>
    </head>
    <body id="<?= $data['admin']['name_page']; ?>">
        <div class="app">
            <?php include VIEWS_ADMIN.'/partials/header.php'; ?>
            <section>
                <div class="container-sm">
                    <?php if(HAS_DDBB == true) { ?>
                    <div class="login-content pt-50">
                        <div class="title-container">Restricted access</div>
                        <div>
                            <input type="text" id="input-email" name="input-email" class="w-100" placeholder="Email">
                        </div>
                        <div class="pt-10">
                            <input type="password" id="input-pass" name="input-pass" class="w-100" placeholder="Pass">
                        </div>
                        <div class="pt-10">
                            <label class="checkbox"><input type="checkbox" id="checkbox-admin-remember" value="1"><span class="checkmark"></span>Remember me.</label>
                        </div>
                        <div class="pt-20">
                            <div id="btn-send-login" class="btn btn-black w-100"><i class="fa-solid fa-right-to-bracket"></i> Login</div>
                        </div>
                    </div>
                    <?php
                        } else {
                            echo '<div class="text-center pt-50">This project does not have an administrator.</div>';
                        }
                    ?>
                    <div class="text-center pt-20">
                        <a href="<?= PUBLIC_ROUTE ?>" class="btn btn-black btn-md"><i class="fa-solid fa-house"></i> Back to Public Home</a>
                    </div>
                </div>
            </section>
            <?php include VIEWS_ADMIN.'/partials/footer.php'; ?>
        </div>
    </body>
</html>