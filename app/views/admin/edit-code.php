<!DOCTYPE html>
<html lang="<?= LANG; ?>">
    <head>
        <?php include VIEWS_ADMIN.'/partials/head.php'; ?>
    </head>
    <body id="<?= $data['admin']['name_page']; ?>">
        <div class="app">
            <?php include VIEWS_ADMIN.'/partials/header.php'; ?>
            <?php include VIEWS_ADMIN.'/partials/menu-left.php'; ?>
            <div id="popup-add-code-rule" class="popup">
                <div class="content content-sm">
                    <div class="title">ADD CODE RULE</div>
                    <div class="pb-20">
                        <div class="row pb-20">
                            <div class="col-12 col-sm-4"><b>Rule type</b></div>
                            <div class="col-12 col-sm-8">
                                <select id="select-add-code-rule-type" class="w-100"><?= $data['codes_rules_type']; ?></select>
                            </div>
                        </div>
                        <div class="row pb-20">
                            <div class="col-12 col-sm-4"><b>Add type</b></div>
                            <div class="col-12 col-sm-8">
                                <select id="select-add-code-rule-add-type" class="w-100"><?= $data['codes_rules_add_type']; ?></select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-6 pr-10 pr-sm-0">
                                <div class="pb-10"><b>All elements</b></div>
                                <div id="add-code-rule-elements-list" class="custom-list" style="height: 200px;"></div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="pb-10"><b>Selected</b></div>
                                <div id="add-code-rule-elements-added" class="custom-list" style="height: 200px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6 pr-5 pr-sm-0">
                            <div id="btn-add-code-rule" class="btn btn-black w-100">Add Rule</div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="btn btn-black w-100 btn-popup-close">Close</div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="popup-edit-code-rule" class="popup">
                <div class="content content-sm">
                    <div class="title">EDIT CODE RULE</div>
                    <div class="pb-20">
                        <div class="row pb-20">
                            <div class="col-12 col-sm-4"><b>Rule type</b></div>
                            <div class="col-12 col-sm-8">
                                <select id="select-edit-code-rule-type" class="w-100"><?= $data['codes_rules_type']; ?></select>
                            </div>
                        </div>
                        <div class="row pb-20">
                            <div class="col-12 col-sm-4"><b>Add type</b></div>
                            <div class="col-12 col-sm-8">
                                <select id="select-edit-code-rule-add-type" class="w-100"><?= $data['codes_rules_add_type']; ?></select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-6 pr-10 pr-sm-0">
                                <div class="pb-10"><b>All elements</b></div>
                                <div id="edit-code-rule-elements-list" class="custom-list" style="height: 200px;"></div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="pb-10"><b>Selected</b></div>
                                <div id="edit-code-rule-elements-added" class="custom-list" style="height: 200px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6 pr-5 pr-sm-0">
                            <div id="btn-save-code-rule" class="btn btn-black w-100">Save</div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="btn btn-black w-100 btn-popup-close">Close</div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="container-admin">
                <section>
                    <div class="container content-save-code">
                        <input type="hidden" id="input-id-code" value="<?= $data['code']['id_code']; ?>">
                        <div class="title-container underline text-left">Edit Code #<?= $data['code']['id_code']; ?></div>
                        <div class="row pb-20">
                            <div class="col-12 col-sm-5 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Code name *</b></div>
                                <div>
                                    <input type="text" id="input-name" class="w-100" maxlength="90" value="<?= $data['code']['name']; ?>">
                                </div>
                            </div>
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Code *</b> (numbers and letters)</div>
                                <div>
                                    <input type="text" id="input-code" class="w-100" maxlength="30" value="<?= $data['code']['code']; ?>">
                                </div>
                            </div>
                            <div class="col-12 col-sm-2 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Available</b></div>
                                <div>
                                    <input type="number" id="input-available" class="w-100" value="<?= $data['code']['available']; ?>" maxlength="7">
                                </div>
                            </div>
                            <div class="col-12 col-sm-2">
                                <div class="pb-10"><b>Per user</b></div>
                                <div>
                                    <input type="number" id="input-per-user" class="w-100" value="<?= $data['code']['per_user']; ?>" maxlength="7">
                                </div>
                            </div>
                        </div>
                        <div class="row pb-20">
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Discount type</b></div>
                                <div>
                                    <select id="select-type" class="w-100"><?= $data['type']; ?></select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Amount</b></div>
                                <div>
                                    <input type="text" id="input-amount" class="w-100" value="<?= $data['code']['amount']; ?>" maxlength="7">
                                </div>
                            </div>
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Must be registered</b></div>
                                <div>
                                    <select id="select-registered" class="w-100">
                                        <option value="0"<?php if($data['code']['registered'] == 0) echo ' selected'; ?>>No</option>
                                        <option value="1"<?php if($data['code']['registered'] == 1) echo ' selected'; ?>>Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-3">
                                <div class="pb-10"><b>Exclude products on sale</b></div>
                                <div>
                                    <select id="select-exclude" class="w-100">
                                        <option value="0"<?php if($data['code']['exclude_sales'] == 0) echo ' selected'; ?>>No</option>
                                        <option value="1"<?php if($data['code']['exclude_sales'] == 1) echo ' selected'; ?>>Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row pb-20">
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Minimum cart value</b></div>
                                <div>
                                    <input type="text" id="input-minimum" class="w-100" value="<?= $data['code']['minimum']; ?>" maxlength="7">
                                </div>
                            </div>
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Valid from</b></div>
                                <div>
                                    <input type="date" id="input-start-date" placeholder="aaaa-mm-dd" class="w-100" maxlength="10" value="<?= $data['code']['start_date']; ?>">
                                </div>
                            </div>
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Valid until</b></div>
                                <div>
                                    <input type="date" id="input-end-date" placeholder="aaaa-mm-dd" class="w-100" maxlength="10" value="<?= $data['code']['end_date']; ?>">
                                </div>
                            </div>
                            <div class="col-12 col-sm-3">
                                <div class="pb-10"><b>Compatible with other codes</b></div>
                                <div>
                                    <select id="select-compatible" class="w-100">
                                        <option value="0"<?php if($data['code']['compatible'] == 0) echo ' selected'; ?>>No</option>
                                        <option value="1"<?php if($data['code']['compatible'] == 1) echo ' selected'; ?>>Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-20">
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Free Shipping</b></div>
                                <div>
                                    <select id="select-free-shipping" class="w-100">
                                        <option value="0"<?php if($data['code']['free_shipping'] == 0) echo ' selected'; ?>>No</option>
                                        <option value="1"<?php if($data['code']['free_shipping'] == 1) echo ' selected'; ?>>Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-3">
                                <div class="pb-10"><b>State</b></div>
                                <div>
                                    <select id="select-state" class="w-100"><?= $data['code_states']; ?></select>
                                </div>
                            </div>
                        </div>
                        <div class="pb-10"><b>Code rules</b></div>
                        <div class="pb-10">
                            <div id="btn-open-popup-new-code-rule" class="btn btn-black btn-md">Create new rule</div>
                        </div>
                        <div id="code-rules" class="pb-20"></div>
                        <div class="text-center pt-40">
                            <div id="btn-save-edit-code" class="btn btn-black">Save Code</div>
                        </div>
                    </div>
                </section>
                <?php include VIEWS_ADMIN.'/partials/footer.php'; ?>
            </div>
        </div>
    </body>
</html>