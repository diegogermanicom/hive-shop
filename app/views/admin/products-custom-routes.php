<!DOCTYPE html>
<html lang="<?= LANG; ?>">
    <head>
        <?php include VIEWS_ADMIN.'/partials/head.php'; ?>
    </head>
    <body id="<?= $data['admin']['name_page']; ?>">
        <div class="app">
            <?php include VIEWS_ADMIN.'/partials/header.php'; ?>
            <?php include VIEWS_ADMIN.'/partials/menu-left.php'; ?>
            <div id="popup-new-product-custom-route" class="popup">
                <div class="content">
                    <div class="title">New product custom route</div>
                    <div class="text">If you leave the route field of a language blank, the custom route will not be created.</div>
                    <div class="pb-10">
                        <select id="products-list" class="w-100">
                            <option value="0">Select a product...</option>
                            <?= $data['products_list']; ?>
                        </select>
                    </div>
                    <div class="pb-20">
                        <select id="categories-list" class="w-100"></select>
                    </div>
                    <div id="languages-content" class="pb-20"><?php
                        foreach($data['languages'] as $value) {
                            $html = '<div class="row item pb-10" id-language="'.$value['id_language'].'">';
                            $html .=    '<div class="col-12 col-sm-3 dots pt-8"><b>'.$value['alias'].'</b></div>';
                            $html .=    '<div class="col-12 col-sm-9"><input type="text" class="w-100"></div>';
                            $html .= '</div>';
                            echo $html;
                        }
                    ?></div>
                    <div class="row">
                        <div class="col-12 col-sm-6 pr-5 pr-sm-5">
                            <div id="btn-save-new-product-custom-route" class="btn btn-black w-100">Create route</div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="btn btn-black w-100 btn-popup-close">Close</div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="container-admin">
                <section>
                    <div class="container">
                        <div class="title-container underline text-left">Products custom routes</div>
                        <div class="pb-20">
                            <div id="open-popup-new-product-custom-route" class="btn btn-black">Create new route</div>
                        </div>
                        <div id="products-custom-routes-content"><?= $data['routes']['html']; ?></div>
                        <div class="pager text-center pt-20"><?= $data['routes']['pager']; ?></div>
                    </div>
                </section>
                <?php include VIEWS_ADMIN.'/partials/footer.php'; ?>
            </div>
        </div>
    </body>
</html>