<!DOCTYPE html>
<html lang="<?= LANG; ?>">
    <head>
        <?php include VIEWS_PUBLIC.'/partials/head.php'; ?>
    </head>
    <body id="<?= $data['app']['name_page']; ?>" class="<?= $_COOKIE['color-mode']; ?>">
        <?php include VIEWS_PUBLIC.'/partials/header-body.php'; ?>
        <div class="app">
            <?php
                include VIEWS_PUBLIC.'/partials/header.php';
                include VIEWS_PUBLIC.'/partials/popup-notify-stock.php';
            ?>
            <div id="popup-notify-stock-info" class="popup">
                <div class="content">
                    <div class="title">Aviso de Stock</div>
                    <div class="text">Su solicitud ha sido registrada con éxito. Le enviaremos un correo electrónico cuando tengamos stock disponible para este producto.</div>
                    <div class="text-center">
                        <div class="btn btn-black btn-popup-close">Cerrar</div>
                    </div>
                </div>
            </div>
            <section>
                <div class="container">
                    <div class="row content-product">
                        <input type="hidden" id="input-id-product" value="<?= $data['product']['id_product']; ?>">
                        <input type="hidden" id="input-id-product-related" value="">
                        <input type="hidden" id="input-id-category" value="<?= $data['product']['id_category']; ?>">
                        <div class="col-12 col-sm-8">
                            <div class="row content-images"></div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="product-name"><?= $data['product']['name']; ?></div>
                            <div class="category-name"><?= $data['product']['category_name']; ?></div>
                            <div id="label-product-price">-</div>
                            <div class="pb-5 content-attributes"><?php
                                if(!empty($data['product']['attributes'])) {
                                    foreach($data['product']['attributes'] as $value) {   
                                        if(!empty($value['values'])) {
                                            echo '<div class="content-attribute" id-attribute="'.$value['id_attribute'].'">';
                                            echo '<div class="title" title="'.$value['description'].'"><b>'.$value['name'].'</b></div>';
                                            foreach($value['values'] as $val) {
                                                if(in_array($val['id_attribute_value'], $data['product']['valid_ids']['valid_values_id'])) {
                                                    $active = '';
                                                    if(in_array($val['id_attribute_value'], $data['product']['active_values_id'])) {
                                                        $active = ' active';
                                                    }
                                                    if($value['id_attribute_type'] == 1) {
                                                        echo '<div class="item'.$active.'" title="'.$val['description'].'" id-attribute-value="'.$val['id_attribute_value'].'">'.$val['name'].'</div>';
                                                    } else if($value['id_attribute_type'] == 2) {
                                                        echo '<div class="item'.$active.'" title="'.$val['description'].'" style="background-color: '.$val['value'].';" id-attribute-value="'.$val['id_attribute_value'].'"></div>';
                                                    } else if($value['id_attribute_type'] == 3) {
                                                        echo '<div class="item'.$active.'" title="'.$val['description'].'" style="background-image: url('.PUBLIC_PATH.$val['value'].')" id-attribute-value="'.$val['id_attribute_value'].'"></div>';
                                                    }
                                                }
                                            }
                                            echo '</div>';
                                        }
                                    }
                                }
                            ?></div>
                            <div class="pb-5">
                                <div class="btn btn-black w-100" id="btn-add-to-cart">Añadir a la cesta</div>
                                <div class="btn btn-black w-100 hidden" id="btn-notify-stock">Notificarme cuando haya stock</div>
                                <div class="box hidden" id="label-discontinued">Este producto esta descatalogado</div>
                            </div>
                            <div class="content-share">
                                <span>Compartir</span>
                                <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                                <a href="#"><i class="fa-solid fa-envelope"></i></a>
                            </div>
                            <div class="pt-20 lh-24"><?= $data['product']['description']; ?></div>
                        </div>
                    </div>
                </div>
            </section>
            <?php include VIEWS_PUBLIC.'/partials/footer.php'; ?>
        </div>
    </body>
</html>