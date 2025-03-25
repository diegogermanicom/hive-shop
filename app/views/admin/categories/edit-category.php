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
                    <div class="container content-edit-category">
                        <input type="hidden" id="input-id-category" value="<?= $data['category']['id_category']; ?>">
                        <div class="title-container underline text-left">Edit category #<?= $data['category']['id_category']; ?></div>
                        <div class="row pb-20">
                            <div class="col-12 col-sm-6 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Category alias name *</b></div>
                                <div>
                                    <input type="text" id="input-alias" class="w-100" value="<?= $data['category']['alias']; ?>">
                                </div>
                            </div>
                            <div class="col-12 col-sm-2 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Parent category *</b></div>
                                <div>
                                    <select id="select-id-parent" class="w-100"><?= $data['categories']; ?></select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-2 pr-10 pr-sm-0">
                                <div class="pb-10"><b>View</b></div>
                                <div>
                                    <select id="select-view" class="w-100"><?= $data['category_views']; ?></select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-2">
                                <div class="pb-10"><b>State</b></div>
                                <div>
                                    <select id="select-state" class="w-100"><?= $data['category_states']; ?></select>
                                </div>
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
                                            <div class="pb-10"><b>Category name *</b></div>
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
                        <div class="text-center pt-40">
                            <a href="<?= PUBLIC_ROUTE.$data['category']['route']; ?>" class="btn btn-black" target="_blank">View Category (<?= LANG; ?>)</a>
                            <div id="btn-save-category" class="btn btn-black">Save Category</div>
                        </div>
                    </div>
                </section>
                <?php include VIEWS_ADMIN.'/partials/footer.php'; ?>
            </div>
        </div>
    </body>
</html>