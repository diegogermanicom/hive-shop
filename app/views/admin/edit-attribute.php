<!DOCTYPE html>
<html lang="<?= LANG; ?>">
    <head>
        <?php include VIEWS_ADMIN.'/partials/head.php'; ?>
    </head>
    <body id="<?= $data['admin']['name_page']; ?>">
        <div class="app">
            <?php include VIEWS_ADMIN.'/partials/header.php'; ?>
            <?php include VIEWS_ADMIN.'/partials/menu-left.php'; ?>
            <div id="popup-attribute-value-properties" class="popup">
                <div class="content">
                    <div class="title">EDIT VALUE PROPERTIES</div>
                    <div class="pb-10"><b>Value alias</b></div>
                    <div class="pb-20">
                        <input type="text" class="w-100" id="input-edit-value-alias">
                    </div>
                    <div class="pb-10"><b>Value properties</b></div>
                    <div id="value-properties-content"></div>
                    <div class="row">
                        <div class="col-12 col-sm-6 pr-5 pr-sm-5">
                            <div id="btn-save-edit-attribute-value" class="btn btn-black w-100">Save</div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="btn btn-black w-100 btn-popup-close">Close</div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="container-admin">
                <section>
                    <div class="container content-edit-attribute">
                        <input type="hidden" id="input-id-attribute" value="<?= $_GET['id_attribute']; ?>">
                        <div class="title-container underline text-left">Edit attribute #<?= $_GET['id_attribute']; ?></div>
                        <div class="row pb-20">
                            <div class="col-12 col-sm-6 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Attribute alias name *</b></div>
                                <div>
                                    <input type="text" id="input-alias" class="w-100" value="<?= $data['attribute']['alias']; ?>">
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

                                        foreach($data['languages'] as $i => $value) {
                                            if($i == 0) {
                                                $active = ' class="active"';
                                            } else {
                                                $active = '';
                                            }
                                            echo '<div id-tab="'.($i + 1).'" id-lang="'.$value['id_language'].'"'.$active.'>'.$value['alias'].'</div>';
                                        }

                                    ?></div>
                                    <div class="content"><?php

                                        foreach($data['languages'] as $i => $value) {
                                            if($i == 0) {
                                                $active = ' class="active"';
                                            } else {
                                                $active = '';
                                            }

                                    ?><div id-tab="<?= ($i + 1); ?>"<?= $active; ?>>
                                            <div class="pb-10">
                                                <b>Attribute name</b>
                                            </div>
                                            <div class="pb-10">
                                                <input type="text" class="w-100 w-100 input-language-name" value="<?= $value['name'] ;?>">
                                            </div>
                                            <div class="pb-10">
                                                <b>Description</b>
                                            </div>
                                            <div class="pb-20">
                                                <textarea class="w-100 textarea-language-description" style="height: 100px"><?= $value['description'] ;?></textarea>
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
                            <div id="btn-save-attribute" class="btn btn-black">Save Attribute</div>
                        </div>
                    </div>
                </section>
                <?php include VIEWS_ADMIN.'/partials/footer.php'; ?>
            </div>
        </div>
    </body>
</html>