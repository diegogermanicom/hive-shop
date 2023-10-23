    <footer>
        <?php if(MULTILANGUAGE == true && isset($data['routes'])) { ?>
        <div class="pb-15 animate animate-opacity text-center">
            <label>Elige tu idioma</label>
            <?php
                $html = '<select id="select-choose-language">';
                for($i = 0; $i < count(LANGUAGES); $i++) {
                    $selected = '';
                    if(LANGUAGES[$i] == LANG) {
                        $selected = ' selected';
                    }
                    if(isset($data['routes'][LANGUAGES[$i]])) {
                        $html .= '<option value="'.LANGUAGES[$i].'" route="'.$data['routes'][LANGUAGES[$i]].'"'.$selected.'>'.LANGUAGES[$i].'</option>';
                    } else {
                        $html .= '<option value="'.LANGUAGES[$i].'" route=""'.$selected.'>'.LANGUAGES[$i].'</option>';
                    }
                }
                $html .= '</select>';
                echo $html;
            ?>
        </div>
        <?php } ?>
        <div class="text-center">Publicado bajo licencia <a href="https://opensource.org/licenses/MIT" target="_blank">MIT</a>.</div>
        <div class="text-center"><b class="hive-color">Hive</b> Framework Copyright © <?= date('Y'); ?></div>
    </footer>