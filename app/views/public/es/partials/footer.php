<footer>
        <?php if(MULTILANGUAGE == true && isset($data['routes'])) { ?>
        <div class="pb-15 animate animate-opacity text-center">
            <label>Elige tu idioma</label>
            <?php
                $html = '<select id="select-choose-language">';
                foreach($data['routes'] as $index => $value) {
                    if(in_array($index, LANGUAGES)) {
                        $selected = '';
                        if($index == LANG) {
                            $selected = ' selected';
                        }
                        $html .= '<option value="'.$index.'" route="'.$value.'"'.$selected.'>'.$index.'</option>';
                    }
                }
                $html .= '</select>';
                echo $html;
            ?>
        </div>
        <?php } ?>
        <div class="text-center font-14">Published under <a href="https://opensource.org/licenses/MIT" target="_blank">MIT</a> license.</div>
        <div class="text-center font-14 pt-5">Copyright © <?= date('Y'); ?> <b class="core-color">Hive</b> Framework - <a href="<?= PUBLIC_ROUTE; ?>/privacy-policy">Privacy policy</a> - <a href="<?= PUBLIC_ROUTE; ?>/cookie-policy">Cookie policy</a></div>
    </footer>