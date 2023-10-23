<div id="popup-edit-address" class="popup">
    <div class="content content-md">
        <div class="title">Editar dirección</div>
        <div class="row pb-20">
            <div class="col-12 col-sm-5 pr-10 pr-sm-0">
                <div class="pb-10"><b>Nombre</b></div>
                <div>
                    <input type="text" id="input-edit-address-name" class="w-100" maxlength="90"></select>
                </div>
            </div>
            <div class="col-12 col-sm-7">
                <div class="pb-10"><b>Apellidos</b></div>
                <div>
                    <input type="text" id="input-edit-address-lastname" class="w-100" maxlength="120"></select>
                </div>
            </div>
        </div>
        <div class="row pb-20">
            <div class="col-12 col-sm-4 pr-10 pr-sm-0">
                <div class="pb-10"><b>Continente</b></div>
                <div>
                    <select id="input-edit-address-continent" class="w-100">
                        <option value="0">Seleccionar...</option>
                        <?php if(isset($data['continents'])) { echo $data['continents']; } ?>
                    </select>
                </div>
            </div>
            <div class="col-12 col-sm-4 pr-10 pr-sm-0">
                <div class="pb-10"><b>País</b></div>
                <div>
                    <select id="input-edit-address-country" class="w-100"></select>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="pb-10"><b>Provincia</b></div>
                <div>
                    <select id="input-edit-address-province" class="w-100"></select>
                </div>
            </div>
        </div>
        <div class="row pb-20">
            <div class="col-12 col-sm-8 pr-10 pr-sm-0">
                <div class="pb-10"><b>Dirección</b></div>
                <div>
                    <input type="text" id="input-edit-address-address" class="w-100" maxlength="255">
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="pb-10"><b>Localidad</b></div>
                <div>
                    <input type="text" id="input-edit-address-location" class="w-100" maxlength="150">
                </div>
            </div>
        </div>
        <div class="row pb-20">
            <div class="col-12 col-sm-5 pr-10 pr-sm-0">
                <div class="pb-10"><b>Código postal</b></div>
                <div>
                    <input type="text" id="input-edit-address-postal-code" class="w-100" maxlength="10">
                </div>
            </div>
            <div class="col-12 col-sm-7">
                <div class="pb-10"><b>Teléfono</b></div>
                <div>
                    <input type="text" id="input-edit-address-telephone" class="w-100" maxlength="20">
                </div>
            </div>
        </div>
        <div class="pb-20 edit-address-main-content">
            <label class="checkbox"><input type="checkbox" id="checkbox-edit-address-main" value="1"><span class="checkmark"></span>Guardar como dirección principal.</label>
        </div>
        <div class="row">
            <div class="col-12 col-sm-6 pr-5 pr-sm-0">
                <div class="btn btn-black w-100" id="btn-edit-address">Guardar cambios</div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="btn btn-black w-100 btn-popup-close">Cerrar</div>
            </div>
        </div>
    </div>
</div>
