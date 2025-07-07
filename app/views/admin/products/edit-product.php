<!DOCTYPE html>
<html lang="<?= LANG; ?>">
    <head>
        <?php include VIEWS_ADMIN.'/partials/head.php'; ?>
    </head>
    <body id="<?= $data['admin']['name_page']; ?>">
        <div class="app">
            <?php include VIEWS_ADMIN.'/partials/header.php'; ?>
            <?php include VIEWS_ADMIN.'/partials/menu-left.php'; ?>
            <div id="popup-delete-image" class="popup">
                <div class="content">
                    <div class="title">DELETE IMAGE</div>
                    <div class="text">Do you want to remove the image only from the product and its related products or from all products, related products and the server?</div>
                    <div class="row">
                        <div class="col-12 col-sm-4 pr-5 pr-sm-0">
                            <div id="btn-delete-from-server-image" class="btn btn-red w-100">From Server</div>
                        </div>
                        <div class="col-12 col-sm-4 pr-5 pr-sm-0">
                            <div id="btn-delete-just-product-image" class="btn btn-black w-100">Just Product</div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="btn btn-black w-100 btn-popup-close">Close</div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="popup-add-image" class="popup">
                <div class="content content-md">
                    <div class="title">ADD IMAGE</div>
                    <div class="text">Select the images you want to add from the server.</div>
                    <div class="content-images pb-20"></div>
                    <div class="row">
                        <div class="col-12 col-sm-6 pr-5 pr-sm-0">
                            <div id="btn-add-images" class="btn btn-black w-100">Add Image</div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="btn btn-black w-100 btn-popup-close">Close</div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="popup-add-related" class="popup">
                <div class="content content-sm">
                    <div class="title">ADD RELATED</div>
                    <div class="content-related pb-20"></div>
                    <div class="row">
                        <div class="col-12 col-sm-6 pr-5 pr-sm-0">
                            <div id="btn-add-related" class="btn btn-black w-100">Add Related</div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="btn btn-black w-100 btn-popup-close">Close</div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="popup-edit-related" class="popup">
                <div class="content content-sm">
                    <div class="title">EDIT RELATED</div>
                    <div class="content-related pb-20"></div>
                    <div class="row">
                        <div class="col-12 col-sm-6 pr-5 pr-sm-0">
                            <div id="btn-save-edit-related" class="btn btn-black w-100">Save</div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="btn btn-black w-100 btn-popup-close">Close</div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="container-admin">
                <section>
                    <div class="container content-edit-product">
                        <input type="hidden" id="input-id-product" value="<?= $data['product']['id_product']; ?>">
                        <div class="title-container underline text-left">Edit product #<?= $data['product']['id_product']; ?></div>
                        <div class="row pb-20">
                            <div class="col-12 col-sm-6 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Product alias name *</b></div>
                                <div>
                                    <input type="text" id="input-name" class="w-100" value="<?= $data['product']['alias']; ?>">
                                </div>
                            </div>
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Price *</b></div>
                                <div>
                                    <input type="text" id="input-price" class="w-100" value="<?= $data['product']['price']; ?>">
                                </div>
                            </div>
                            <div class="col-12 col-sm-3 pr-10">
                                <div class="pb-10"><b>Weight(Kg) *</b></div>
                                <div>
                                    <input type="text" id="input-weight" class="w-100" value="<?= $data['product']['weight']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row pb-20">
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Tax type</b></div>
                                <div>
                                    <select id="select-tax" class="w-100"><?= $data['tax_types']; ?></select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>View</b></div>
                                <div>
                                    <select id="select-view" class="w-100"><?= $data['product_views']; ?></select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>State</b></div>
                                <div>
                                    <select id="select-state" class="w-100"><?= $data['product_states']; ?></select>
                                </div>
                            </div>
                        </div>
                        <div class="row pb-20">
                            <div class="col-6 col-sm-3 pr-10">
                                <div class="pb-10"><b>Categories *</b> (select main *)</div>
                                <div id="category-list-1" class="custom-list" style="height: 200px;"></div>
                            </div>
                            <div class="col-6 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><i class="fa-solid fa-caret-left"></i> Add categories from the list</div>
                                <div id="category-list-2" class="custom-list" style="height: 200px;"><?= $data['categories']; ?></div>
                            </div>
                            <div class="col-6 col-sm-3 pr-10">
                                <div class="pb-10"><b>Attributes</b> (sort by priority)</div>
                                <div id="attribute-list-1" class="custom-list" style="height: 200px;"><?= $data['attributes_product']; ?></div>
                            </div>
                            <div class="col-6 col-sm-3">
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
                                                <input type="text" class="w-100 input-language-name" value="<?= $value['name']; ?>">
                                            </div>
                                            <div class="pb-10"><b>Slug</b> (must start with a letter)</div>
                                            <div class="pb-20">
                                                <input type="text" class="w-100 input-language-slug" value="<?= $value['slug']; ?>">
                                            </div>
                                            <div class="pb-10"><b>Description</b></div>
                                            <div class="pb-20">
                                                <textarea class="w-100 textarea-language-description" style="height: 100px;"><?= $value['description']; ?></textarea>
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
                                                <input type="text" class="w-100 input-language-meta-title" value="<?= $value['meta_title']; ?>">
                                            </div>
                                            <div class="pb-10"><b>Meta keywords</b> (separated by commas)</div>
                                            <div class="pb-20">
                                                <input type="text" class="w-100 input-language-meta-keywords" value="<?= $value['meta_keywords']; ?>">
                                            </div>
                                            <div class="pb-10"><b>Meta description</b></div>
                                            <div class="pb-20">
                                                <textarea class="w-100 textarea-language-meta-description" style="height: 100px;"><?= $value['meta_description']; ?></textarea>
                                            </div>
                                        </div><?php

                                        }

                                    ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="pb-10"><b>Product images</b></div>
                        <div class="pb-10">
                            <div id="btn-open-popup-add-image" class="btn btn-black btn-md">Add images</div>
                            <label for="input-file" class="btn btn-black btn-md">Upload new images</label>
                            <span class="pl-5"> (drag to sort by priority) Maximum size 5 mb</span>
                            <input type="file" id="input-file" multiple style="display: none;">
                        </div>
                        <div id="upload-images" class="mb-20"></div>
                        <div class="pb-10"><b>Product related</b></div>
                        <div class="pb-10">
                            <div id="btn-open-popup-add-related" class="btn btn-black btn-md">Create new related</div>
                        </div>
                        <div id="products-related" class="pb-20"></div>
                        <div class="text-center pt-40">
                            <a href="<?= PUBLIC_ROUTE.$data['product']['route']; ?>" class="btn btn-black" target="_blank">View Product (<?= LANG; ?>)</a>
                            <div id="btn-save-product" class="btn btn-black">Save Product</div>
                        </div>
                    </div>
                </section>
                <?php include VIEWS_ADMIN.'/partials/footer.php'; ?>
            </div>
        </div>
    </body>
</html>