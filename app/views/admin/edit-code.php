<!DOCTYPE html>
<html lang="<?= LANG; ?>">
    <head>
        <?php include VIEWS_ADMIN.'/head.php'; ?>
    </head>
    <body id="<?= $data['admin']['name_page']; ?>">
        <div class="app">
            <?php include VIEWS_ADMIN.'/header.php'; ?>
            <?php include VIEWS_ADMIN.'/menu-left.php'; ?>
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
                                    <input type="text" id="input-available" class="w-100" value="<?= $data['code']['available']; ?>" maxlength="7">
                                </div>
                            </div>
                            <div class="col-12 col-sm-2">
                                <div class="pb-10"><b>State</b></div>
                                <div>
                                    <select id="select-state" class="w-100"><?= $data['code_states']; ?></select>
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
                        <div class="row">
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Minimum amount</b></div>
                                <div>
                                    <input type="text" id="input-minimum" class="w-100" value="<?= $data['code']['minimum']; ?>" maxlength="7">
                                </div>
                            </div>
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Valid from</b></div>
                                <div>
                                    <input type="text" id="input-start-date" placeholder="aaaa-mm-dd" class="w-100" maxlength="10" value="<?= $data['code']['start_date']; ?>">
                                </div>
                            </div>
                            <div class="col-12 col-sm-3 pr-10 pr-sm-0">
                                <div class="pb-10"><b>Valid until</b></div>
                                <div>
                                    <input type="text" id="input-end-date" placeholder="aaaa-mm-dd" class="w-100" maxlength="10" value="<?= $data['code']['end_date']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="text-center pt-40">
                            <div id="btn-save-edit-code" class="btn btn-black">Save Code</div>
                        </div>
                    </div>
                </section>
                <?php include VIEWS_ADMIN.'/footer.php'; ?>
            </div>
        </div>
    </body>
</html>