<!DOCTYPE html>
<html lang="<?= LANG; ?>">
    <head>
        <?php include VIEWS_PUBLIC.'/head.php'; ?>
    </head>
    <body id="<?= $data['app']['name_page']; ?>" class="<?= $_COOKIE['color-mode']; ?>">
        <?php include VIEWS_PUBLIC.'/header-body.php'; ?>
        <div class="app">
            <?php include VIEWS_PUBLIC.'/header.php'; ?>
            <div id="popup-new-address" class="popup">
                <div class="content">
                    <div class="title">Crear nueva dirección</div>
                    <div>
                        <input type="text" id="input-new-address-name" class="w-100" maxlength="90"></select>
                    </div>
                    <div>
                        <input type="text" id="input-new-address-lastname" class="w-100" maxlength="120"></select>
                    </div>
                    <div>
                        <select id="input-new-address-continent" class="w-100">
                            <option value="0">Continente...</option>
                            <?= $data['continents']; ?>
                        </select>
                    </div>
                    <div>
                        <select id="input-new-address-country" class="w-100"></select>
                    </div>
                    <div>
                        <select id="input-new-address-province" class="w-100"></select>
                    </div>
                    <div>
                        <input type="text" id="input-new-address-address" class="w-100" maxlength="255">
                    </div>
                    <div>
                        <input type="text" id="input-new-address-location" class="w-100" maxlength="150">
                    </div>
                    <div>
                        <input type="text" id="input-new-address-postal-code" class="w-100" maxlength="10">
                    </div>
                    <div>
                        <input type="text" id="input-new-address-telephone" class="w-100" maxlength="20">
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6 pr-5 pr-sm-0">
                            <div class="btn btn-black w-100" id="btn-create-address">Crear dirección</div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="btn btn-black w-100 btn-popup-close">Cerrar</div>
                        </div>
                    </div>
                </div>
            </div>
            <section>
                <div class="container">
                    <div class="title-container">Dirección de envío</div>
                    <div id="address-list"></div>
                </div>
            </section>
            <?php include VIEWS_PUBLIC.'/footer.php'; ?>
        </div>
    </body>
</html>