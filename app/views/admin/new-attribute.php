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
                    <div class="container content-new-attribute">
                        <div class="title-container underline text-left">Create new attribute</div>
                        <div class="row pb-20">
                            <div class="col-12 col-sm-6 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Attribute alias name *</b></div>
                                <div>
                                    <input type="text" id="input-alias" class="w-100">
                                </div>
                            </div>
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Type</b></div>
                                <div>
                                    <select id="select-type" class="w-100"><?= $data['attributes_types']; ?></select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-3">
                                <div class="pb-10"><b>Html</b></div>
                                <div>
                                    <select id="select-view" class="w-100"><?= $data['attributes_html']; ?></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-5 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Attribute properties</b></div>
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
                                            <div class="pb-10">
                                                <b>Attribute name</b>
                                            </div>
                                            <div class="pb-10">
                                                <input type="text" class="w-100 w-100 input-language-name">
                                            </div>
                                            <div class="pb-10">
                                                <b>Description</b>
                                            </div>
                                            <div class="pb-20">
                                                <textarea class="w-100 textarea-language-description" style="height: 100px"></textarea>
                                            </div>
                                        </div><?php

                                        }

                                    ?></div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Values</b> (sort by priority)</div>
                                <div id="attribute-values-list" class="custom-list" style="height: 310px;"></div>
                            </div>
                            <div class="col-12 col-sm-3">
                                <div class="pb-10"><i class="fa-solid fa-caret-left"></i> Add values</div>
                                <div class="box content-new-value">
                                    <div class="pb-10">Value alias</div>
                                    <div class="pb-10">
                                        <input type="text" id="input-value-alias" class="w-100">
                                    </div>
                                    <div class="pb-10 hidden" id="value-property-color">
                                        <div class="pb-10">Value property (#fdfdfd)</div>
                                        <input type="text" id="input-value-property-color" class="w-100">
                                    </div>
                                    <div class="pb-10 hidden" id="value-property-image">
                                        <div class="pb-10">Value property</div>
                                        <div class="row">
                                            <div class="col-12 col-sm-8">
                                                <label for="input-value-property-image" class="btn btn-black btn-md w-100">Upload image</label>
                                                <input type="file" id="input-value-property-image" class="hidden">
                                            </div>
                                            <div class="col-12 col-sm-4 pl-5 pl-sm-0">
                                                <div id="upload-image" image-name=""></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <div id="btn-add-attribute-value" class="btn btn-black btn-md">Create Value</div>
                                    </div>
                                </div>
                                <div class="pt-10">* You can edit the values after saving</div>
                            </div>
                        </div>
                        <div class="text-center pt-40">
                            <div id="btn-save-new-attribute" class="btn btn-black">Create Attribute</div>
                        </div>
                    </div>
                </section>
                <?php include VIEWS_ADMIN.'/partials/footer.php'; ?>
            </div>
        </div>
    </body>
</html>