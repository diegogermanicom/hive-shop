<!DOCTYPE html>
<html lang="<?= LANG; ?>">
    <head>
        <?php include VIEWS_ADMIN.'/partials/head.php'; ?>
    </head>
    <body id="<?= $data['admin']['name_page']; ?>">
        <div class="app">
            <?php include VIEWS_ADMIN.'/partials/header.php'; ?>
            <?php include VIEWS_ADMIN.'/partials/menu-left.php'; ?>
            <div id="container-admin">
                <section>
                    <div class="container content-new-product">
                        <div class="title-container underline text-left">Create new product</div>
                        <div class="row pb-20">
                            <div class="col-12 col-sm-6 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Product alias name *</b></div>
                                <div>
                                    <input type="text" id="input-name" class="w-100">
                                </div>
                            </div>
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Price *</b></div>
                                <div>
                                    <input type="text" id="input-price" class="w-100">
                                </div>
                            </div>
                            <div class="col-12 col-sm-3 pr-10">
                                <div class="pb-10"><b>Weight(Kg) *</b></div>
                                <div>
                                    <input type="text" id="input-weight" class="w-100">
                                </div>
                            </div>
                        </div>
                        <div class="row pb-20">
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>View</b></div>
                                <div>
                                    <select id="select-view" class="w-100"><?= $data['product_views']; ?></select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-3">
                                <div class="pb-10"><b>State</b></div>
                                <div>
                                    <select id="select-state" class="w-100"><?= $data['product_states']; ?></select>
                                </div>
                            </div>
                        </div>
                        <div class="row pb-20">
                            <div class="col-12 col-sm-3 pr-10">
                                <div class="pb-10"><b>Categories *</b> (select main *)</div>
                                <div id="category-list-1" class="custom-list" style="height: 200px;"></div>
                            </div>
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><i class="fa-solid fa-caret-left"></i> Add categories from the list</div>
                                <div id="category-list-2" class="custom-list" style="height: 200px;"><?= $data['categories']; ?></div>
                            </div>
                            <div class="col-12 col-sm-3 pr-10">
                                <div class="pb-10"><b>Attributes</b> (sort by priority)</div>
                                <div id="attribute-list-1" class="custom-list" style="height: 200px;"></div>
                            </div>
                            <div class="col-12 col-sm-3">
                                <div class="pb-10"><i class="fa-solid fa-caret-left"></i> Add attributes from the list</div>
                                <div id="attribute-list-2" class="custom-list" style="height: 200px;"><?= $data['attributes']; ?></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-6 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Product properties</b></div>
                                <div class="custom-tab pb-20" id="properties">
                                    <div class="menu"><?php

                                        foreach($data['languages'] as $index => $value) {
                                            if($index == 0) {
                                                $active = ' class="active"';
                                            } else {
                                                $active = '';
                                            }
                                            echo '<div id-tab="'.($index + 1).'" id-lang="'.$value['id_language'].'"'.$active.'>'.$value['alias'].'</div>';
                                        }

                                    ?></div>
                                    <div class="content"><?php

                                        foreach($data['languages'] as $index => $value) {
                                            if($index == 0) {
                                                $active = ' class="active"';
                                            } else {
                                                $active = '';
                                            }

                                    ?><div id-tab="<?= ($index + 1); ?>"<?= $active; ?>>
                                            <div class="pb-10"><b>Product name *</b></div>
                                            <div class="pb-20">
                                                <input type="text" class="w-100 input-language-name">
                                            </div>
                                            <div class="pb-10"><b>Slug</b> (must start with a letter)</div>
                                            <div class="pb-20">
                                                <input type="text" class="w-100 input-language-slug">
                                            </div>
                                            <div class="pb-10"><b>Description</b></div>
                                            <div class="pb-20">
                                                <textarea class="w-100 textarea-language-description" style="height: 100px;"></textarea>
                                            </div>
                                        </div><?php

                                        }

                                    ?></div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="pb-10"><b>Meta data</b></div>
                                <div class="custom-tab pb-20" id="meta_data">
                                    <div class="menu"><?php

                                        foreach($data['languages'] as $index => $value) {
                                            if($index == 0) {
                                                $active = ' class="active"';
                                            } else {
                                                $active = '';
                                            }
                                            echo '<div id-tab="'.($index + 1).'" id-lang="'.$value['id_language'].'"'.$active.'>'.$value['alias'].'</div>';
                                        }

                                    ?></div>
                                    <div class="content"><?php

                                        foreach($data['languages'] as $index => $value) {
                                            if($index == 0) {
                                                $active = ' class="active"';
                                            } else {
                                                $active = '';
                                            }

                                    ?><div id-tab="<?= ($index + 1); ?>"<?= $active; ?>>
                                            <div class="pb-10"><b>Meta title</b></div>
                                            <div class="pb-20">
                                                <input type="text" class="w-100 input-language-meta-title">
                                            </div>
                                            <div class="pb-10"><b>Meta keywords</b> (separated by commas)</div>
                                            <div class="pb-20">
                                                <input type="text" class="w-100 input-language-meta-keywords">
                                            </div>
                                            <div class="pb-10"><b>Meta description</b></div>
                                            <div class="pb-20">
                                                <textarea class="w-100 textarea-language-meta-description" style="height: 100px;"></textarea>
                                            </div>
                                        </div><?php

                                        }

                                    ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="pb-10"><b>Product images</b></div>
                        <div class="pb-10">
                            <label for="input-file" class="btn btn-black btn-md">Upload new images</label>
                            <span class="pl-5"> (drag to sort by priority) Maximum size 5 mb</span>
                            <input type="file" id="input-file" multiple class="hidden">
                        </div>
                        <div id="upload-images"></div>
                        <div class="text-center pt-40">
                            <div id="btn-save-new-product" class="btn btn-black">Create Product</div>
                        </div>
                    </div>
                </section>
                <?php include VIEWS_ADMIN.'/partials/footer.php'; ?>
            </div>
        </div>
    </body>
</html>