<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2023
     */   

    class AdminPaymentAjax extends AdminModel {

        public $name_page;

        function __construct($name_page = 'default-page') {
            parent::__construct();
            $this->name_page = $name_page;
        }

        public function save_new_payment() {
            // I check that this alias no longer exists.
            $sql = 'SELECT id_payment_method FROM '.DDBB_PREFIX.'payment_methods WHERE alias = ? LIMIT 1';
            $result = $this->query($sql, $_POST['alias']);
            if($result->num_rows == 0) {
                $sql = 'INSERT INTO '.DDBB_PREFIX.'payment_methods (alias, min_order_value, max_order_value, id_state)
                    VALUES (?, ?, ?, ?)';
                $this->query($sql, array(
                    $_POST['alias'], $_POST['min_value'], $_POST['max_value'], $_POST['id_state']
                ));
                $id_payment_method = $this->db->insert_id;
                for($i = 0; $i < count($_POST['languages']); $i++) {
                    $sql = 'INSERT INTO '.DDBB_PREFIX.'payment_methods_language (id_payment_method, id_language, name) VALUES (?, ?, ?)';
                    $this->query($sql, array(
                        $id_payment_method, $_POST['languages'][$i]['id_lang'], $_POST['languages'][$i]['name']
                    ));
                }
                return array('response' => 'ok');
            } else {
                return array(
                    'response' => 'error',
                    'message' => 'That alias already exists. Try another one.'
                );
            }
        }

        public function save_edit_payment() {
            // I check that this alias no longer exists.
            $sql = 'SELECT id_payment_method FROM '.DDBB_PREFIX.'payment_methods WHERE alias = ? AND id_payment_method != ? LIMIT 1';
            $result = $this->query($sql, array($_POST['alias'], $_POST['id_payment_method']));
            if($result->num_rows == 0) {
                $sql = 'UPDATE '.DDBB_PREFIX.'payment_methods SET alias = ?, min_order_value = ?, max_order_value = ?, id_state = ?
                        WHERE id_payment_method = ? LIMIT 1';
                $this->query($sql, array(
                    $_POST['alias'], $_POST['min_value'], $_POST['max_value'],
                    $_POST['id_state'], $_POST['id_payment_method']
                ));
                for($i = 0; $i < count($_POST['languages']); $i++) {
                    $sql = 'UPDATE '.DDBB_PREFIX.'payment_methods_language SET name = ? WHERE id_payment_method = ? AND id_language = ? LIMIT 1';
                    $this->query($sql, array(
                        $_POST['languages'][$i]['name'], $_POST['id_payment_method'], $_POST['languages'][$i]['id_lang']
                    ));
                }
                // I save the values ​​of the zones
                $sql = 'DELETE FROM '.DDBB_PREFIX.'payment_methods_zones WHERE id_payment_method = ?';
                $this->query($sql, array($_POST['id_payment_method']));
                foreach($_POST['zones'] as $zone) {
                    if($zone['active'] == 1) {
                        $sql = 'INSERT INTO '.DDBB_PREFIX.'payment_methods_zones (id_payment_method, id_payment_zone) VALUES (?, ?)';
                        $this->query($sql, array($_POST['id_payment_method'], $zone['id_payment_zone']));
                    }
                }
                return array(
                    'response' => 'ok',
                    'message' => 'The payment method has been successfully updated!'
                );
            } else {
                return array(
                    'response' => 'error',
                    'message' => 'That alias already exists. Try another one.'
                );
            }
        }

        public function delete_payment($id_payment_method) {
            $sql = 'DELETE FROM '.DDBB_PREFIX.'payment_methods WHERE id_payment_method = ? LIMIT 1';
            $this->query($sql, array($id_payment_method));
            $sql = 'DELETE FROM '.DDBB_PREFIX.'payment_methods_language WHERE id_payment_method = ?';
            $this->query($sql, array($id_payment_method));
            $sql = 'DELETE FROM '.DDBB_PREFIX.'payment_methods_zones WHERE id_payment_method = ?';
            $this->query($sql, array($id_payment_method));
            return array(
                'response' => 'ok'
            );
        }

        public function save_new_payment_zone() {
            // I check that this alias no longer exists.
            $sql = 'SELECT id_payment_zone  FROM '.DDBB_PREFIX.'payment_zones WHERE name = ? LIMIT 1';
            $result = $this->query($sql, $_POST['name']);
            if($result->num_rows == 0) {
                $sql = 'INSERT INTO '.DDBB_PREFIX.'payment_zones (name, id_state) VALUES (?, ?)';
                $this->query($sql, array($_POST['name'], $_POST['id_state']));
                return array('response' => 'ok');
            } else {
                return array(
                    'response' => 'error',
                    'message' => 'That name already exists. Try another one.'
                );
            }            
        }

        public function get_payment_zone_countries($id_payment_zone, $id_continent, $page = 1, $per_page = 30) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'payment_zone_countries WHERE id_payment_zone = ?';
            $result = $this->query($sql, $id_payment_zone);
            // If you have selected a continent filter by its id
            if($id_continent == 0) {
                $sql_where = '';
            } else {
                $sql_where = 'AND c.id_continent = '.$id_continent;
            }
            $sql = 'SELECT c.*, o.en AS continent_name FROM '.DDBB_PREFIX.'ct_countries AS c
                        INNER JOIN '.DDBB_PREFIX.'ct_continents AS o ON o.id_continent = c.id_continent
                    WHERE c.id_state = 2 AND o.id_state = 2 '.$sql_where.' ORDER BY c.id_country';
            $result_countries = $this->query($sql);
            if($result_countries->num_rows != 0) {
                $pager = $this->pagerAjax($result_countries, $page, $per_page);
                $html = '<table>';
                $html .= '<tbody>';
                foreach($pager['result'] as $row_country) {
                    $checked = '';
                    while($row = $result->fetch_assoc()) {
                        if($row_country['id_country'] == $row['id_country']) {
                            $checked = ' checked';
                        }
                    }
                    $result->data_seek(0);
                    $html .= '<tr>';
                    $html .=    '<td class="w-50">';
                    $html .=        '<label class="checkbox">';
                    $html .=            '<input type="checkbox" value="'.$row_country['id_country'].'"'.$checked.'>';
                    $html .=            '<span class="checkmark"></span>'.$row_country['en'];
                    $html .=        '</label>';
                    $html .=    '</td>';
                    $html .=    '<td class="w-50">'.$row_country['continent_name'].'</td>';
                    $html .= '</tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
            } else {
                $html = 'No countries found.';
            }
            return array(
                'response' => 'ok',
                'html' => $html,
                'pager' => (isset($pager)) ? $pager['pager'] : ''
            );
        }

        public function get_payment_zone_provinces($id_payment_zone, $id_country, $page = 1, $per_page = 30) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'payment_zone_provinces WHERE id_payment_zone = ?';
            $result = $this->query($sql, $id_payment_zone);
            // If you have selected a country filter by its id
            if($id_country == 0) {
                $sql_where = '';
            } else {
                $sql_where = 'AND c.id_country = '.$id_country;
            }
            $sql = 'SELECT p.*, c.en AS country_name FROM '.DDBB_PREFIX.'ct_provinces AS p
                        INNER JOIN '.DDBB_PREFIX.'ct_countries AS c ON c.id_country = p.id_country
                    WHERE p.id_state = 2 AND c.id_state = 2 '.$sql_where.' ORDER BY p.id_province';
            $result_provinces = $this->query($sql);
            if($result_provinces->num_rows != 0) {
                $pager = $this->pagerAjax($result_provinces, $page, $per_page);
                $html = '<table>';
                $html .= '<tbody>';
                foreach($pager['result'] as $row_province) {
                    $checked = '';
                    while($row = $result->fetch_assoc()) {
                        if($row_province['id_province'] == $row['id_province']) {
                            $checked = ' checked';
                        }
                    }
                    $result->data_seek(0);
                    $html .= '<tr>';
                    $html .=    '<td class="w-50">';
                    $html .=        '<label class="checkbox">';
                    $html .=            '<input type="checkbox" value="'.$row_province['id_province'].'"'.$checked.'>';
                    $html .=            '<span class="checkmark"></span>'.$row_province['en'];
                    $html .=        '</label>';
                    $html .=    '</td>';
                    $html .=    '<td class="w-50">'.$row_province['country_name'].'</td>';
                    $html .= '</tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
            } else {
                $html = 'No countries found.';
            }
            return array(
                'response' => 'ok',
                'html' => $html,
                'pager' => (isset($pager)) ? $pager['pager'] : ''
            );
        }

        public function save_edit_payment_zone() {
            // I check that this alias no longer exists.
            $sql = 'SELECT id_payment_zone FROM '.DDBB_PREFIX.'payment_zones WHERE name = ? AND id_payment_zone != ? LIMIT 1';
            $result = $this->query($sql, array($_POST['name'], $_POST['id_payment_zone']));
            if($result->num_rows == 0) {
                $sql = 'UPDATE '.DDBB_PREFIX.'payment_zones SET name = ?, id_state = ? WHERE id_payment_zone = ? LIMIT 1';
                $this->query($sql, array($_POST['name'], $_POST['id_state'], $_POST['id_payment_zone']));
                // I remove all items from the list
                if(isset($_POST['continents'])) {
                    $sql = 'DELETE FROM '.DDBB_PREFIX.'payment_zone_continents
                            WHERE id_payment_zone = ? AND id_continent IN ('.implode(",", $_POST['continents']).')';
                    $this->query($sql, $_POST['id_payment_zone']);
                }
                if(isset($_POST['countries'])) {
                    $sql = 'DELETE FROM '.DDBB_PREFIX.'payment_zone_countries
                            WHERE id_payment_zone = ? AND id_country IN ('.implode(",", $_POST['countries']).')';
                    $this->query($sql, $_POST['id_payment_zone']);
                }
                if(isset($_POST['provinces'])) {
                    $sql = 'DELETE FROM '.DDBB_PREFIX.'payment_zone_provinces
                            WHERE id_payment_zone = ? AND id_province IN ('.implode(",", $_POST['provinces']).')';
                    $this->query($sql, $_POST['id_payment_zone']);
                }
                // I save the selected items
                if(isset($_POST['continents_add'])) {
                    foreach($_POST['continents_add'] as $value) {
                        $sql = 'INSERT INTO '.DDBB_PREFIX.'payment_zone_continents (id_payment_zone, id_continent) VALUES (?, ?)';
                        $this->query($sql, array($_POST['id_payment_zone'], $value));
                    }
                }
                if(isset($_POST['countries_add'])) {
                    foreach($_POST['countries_add'] as $value) {
                        $sql = 'INSERT INTO '.DDBB_PREFIX.'payment_zone_countries (id_payment_zone, id_country) VALUES (?, ?)';
                        $this->query($sql, array($_POST['id_payment_zone'], $value));
                    }
                }
                if(isset($_POST['provinces_add'])) {
                    foreach($_POST['provinces_add'] as $value) {
                        $sql = 'INSERT INTO '.DDBB_PREFIX.'payment_zone_provinces (id_payment_zone, id_province) VALUES (?, ?)';
                        $this->query($sql, array($_POST['id_payment_zone'], $value));
                    }
                }
                return array(
                    'response' => 'ok',
                    'message' => 'The payment zone has been successfully updated!'
                );
            } else {
                return array(
                    'response' => 'error',
                    'message' => 'That name already exists. Try another one.'
                );
            }
        }

        public function delete_payment_zone($id_payment_zone) {
            $sql = 'DELETE FROM '.DDBB_PREFIX.'payment_zones WHERE id_payment_zone = ? LIMIT 1';
            $this->query($sql, array($id_payment_zone));
            $sql = 'DELETE FROM '.DDBB_PREFIX.'payment_zone_continents WHERE id_payment_zone = ?';
            $this->query($sql, array($id_payment_zone));
            $sql = 'DELETE FROM '.DDBB_PREFIX.'payment_zone_countries WHERE id_payment_zone = ?';
            $this->query($sql, array($id_payment_zone));
            $sql = 'DELETE FROM '.DDBB_PREFIX.'payment_zone_provinces WHERE id_payment_zone = ?';
            $this->query($sql, array($id_payment_zone));
            // I remove what is related to the methods
            $sql = 'DELETE FROM '.DDBB_PREFIX.'payment_methods_zones WHERE id_payment_zone = ?';
            $this->query($sql, array($id_payment_zone));
            return array(
                'response' => 'ok'
            );
        }

    }

?>