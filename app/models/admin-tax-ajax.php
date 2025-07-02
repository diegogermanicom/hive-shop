<?php

    /**
     * @author Diego Martín
     * @copyright Hive®
     * @version 1.0
     * @lastUpdated 2025
     */

    class AdminTaxAjax extends AdminModel {

        public $name_page;

        function __construct($name_page = 'default-page') {
            parent::__construct();
            $this->name_page = $name_page;
        }
    
        public function save_new_tax_type() {
            // I check that this alias no longer exists.
            $sql = 'SELECT id_tax_type FROM '.DDBB_PREFIX.'tax_types WHERE name = ? LIMIT 1';
            $result = $this->query($sql, $_POST['name']);
            if($result->num_rows == 0) {
                $sql = 'INSERT INTO '.DDBB_PREFIX.'tax_types (name, id_state) VALUES (?, ?)';
                $this->query($sql, array($_POST['name'], $_POST['id_state']));
                $id_tax_type = $this->db->insert_id;
                $this->createDefaultPercents($id_tax_type);
                return array('response' => 'ok');
            } else {
                return array(
                    'response' => 'error',
                    'message' => 'That name already exists. Try another one.'
                );
            }
        }

        private function createDefaultPercents($id_tax_type) {
            $sql = 'SELECT id_tax_zone FROM '.DDBB_PREFIX.'tax_zones';
            $result_zones = $this->query($sql);
            if($result_zones->num_rows > 0) {
                while($row_zones = $result_zones->fetch_assoc()) {
                    $sql = 'INSERT INTO tax_types_percent (id_tax_type, id_tax_zone, percent) VALUES (?, ?, 0)';
                    $this->query($sql, array($id_tax_type, $row_zones['id_tax_zone']));
                }
            }
        }

        public function save_edit_tax_type() {
            // I check that this alias no longer exists.
            $sql = 'SELECT id_tax_type FROM '.DDBB_PREFIX.'tax_types WHERE name = ? AND id_tax_type != ? LIMIT 1';
            $result = $this->query($sql, array($_POST['name'], $_POST['id_tax_type']));
            if($result->num_rows == 0) {
                $sql = 'UPDATE '.DDBB_PREFIX.'tax_types SET name = ?, id_state = ? WHERE id_tax_type = ? LIMIT 1';
                $this->query($sql, array($_POST['name'], $_POST['id_state'], $_POST['id_tax_type']));
                // I save the values ​​of the zones
                $sql = 'DELETE FROM '.DDBB_PREFIX.'tax_types_zones WHERE id_tax_type = ?';
                $this->query($sql, array($_POST['id_tax_type']));
                if(isset($_POST['zones'])) {
                    foreach($_POST['zones'] as $zone) {
                        if($zone['active'] == 1) {
                            $sql = 'INSERT INTO '.DDBB_PREFIX.'tax_types_zones (id_tax_type, id_tax_zone) VALUES (?, ?)';
                            $this->query($sql, array($_POST['id_tax_type'], $zone['id_tax_zone']));
                        }
                        $sql = 'UPDATE '.DDBB_PREFIX.'tax_types_percent SET percent = ? WHERE id_tax_type = ? AND id_tax_zone = ? LIMIT 1';
                        $this->query($sql, array($zone['percent'], $_POST['id_tax_type'], $zone['id_tax_zone']));
                    }    
                }
                return array(
                    'response' => 'ok',
                    'message' => 'The tax type has been successfully updated!'
                );
            } else {
                return array(
                    'response' => 'error',
                    'message' => 'That name already exists. Try another one.'
                );
            }
            return array('response' => 'ok');
        }

        public function delete_tax_type($id_tax_type) {
            $sql = 'DELETE FROM '.DDBB_PREFIX.'tax_types WHERE id_tax_type = ? LIMIT 1';
            $this->query($sql, array($id_tax_type));
            $sql = 'DELETE FROM '.DDBB_PREFIX.'tax_types_zones WHERE id_tax_type = ?';
            $this->query($sql, array($id_tax_type));
            $sql = 'DELETE FROM '.DDBB_PREFIX.'tax_types_percent WHERE id_tax_type = ?';
            $this->query($sql, array($id_tax_type));
            return array('response' => 'ok');            
        }

        public function save_new_tax_zone() {
            // I check that this alias no longer exists.
            $sql = 'SELECT id_tax_zone  FROM '.DDBB_PREFIX.'tax_zones WHERE name = ? LIMIT 1';
            $result = $this->query($sql, $_POST['name']);
            if($result->num_rows == 0) {
                $sql = 'INSERT INTO '.DDBB_PREFIX.'tax_zones (name, id_state) VALUES (?, ?)';
                $this->query($sql, array($_POST['name'], $_POST['id_state']));
                $id_tax_zone = $this->db->insert_id;
                $this->createDefaultPercentZone($id_tax_zone);
                return array('response' => 'ok');
            } else {
                return array(
                    'response' => 'error',
                    'message' => 'That name already exists. Try another one.'
                );
            }            
        }

        private function createDefaultPercentZone($id_tax_zone) {
            $sql = 'SELECT id_tax_type FROM '.DDBB_PREFIX.'tax_types';
            $result_type = $this->query($sql);
            if($result_type->num_rows > 0) {
                while($row_type = $result_type->fetch_assoc()) {
                    $sql = 'INSERT INTO '.DDBB_PREFIX.'tax_types_percent (id_tax_type, id_tax_zone, percent) VALUES (?, ?, 0)';
                    $this->query($sql, array($row_type['id_tax_type'], $id_tax_zone));
                }
            }
        }

        public function get_tax_zone_countries($id_tax_zone, $id_continent, $page = 1, $per_page = 30) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'tax_zone_countries WHERE id_tax_zone = ?';
            $result = $this->query($sql, $id_tax_zone);
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

        public function get_tax_zone_provinces($id_tax_zone, $id_country, $page = 1, $per_page = 30) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'tax_zone_provinces WHERE id_tax_zone = ?';
            $result = $this->query($sql, $id_tax_zone);
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

        public function save_edit_tax_zone() {
            // I check that this alias no longer exists.
            $sql = 'SELECT id_tax_zone FROM '.DDBB_PREFIX.'tax_zones WHERE name = ? AND id_tax_zone != ? LIMIT 1';
            $result = $this->query($sql, array($_POST['name'], $_POST['id_tax_zone']));
            if($result->num_rows == 0) {
                $sql = 'UPDATE '.DDBB_PREFIX.'tax_zones SET name = ?, id_state = ? WHERE id_tax_zone = ? LIMIT 1';
                $this->query($sql, array($_POST['name'], $_POST['id_state'], $_POST['id_tax_zone']));
                // I remove all items from the list
                if(isset($_POST['continents'])) {
                    $sql = 'DELETE FROM '.DDBB_PREFIX.'tax_zone_continents
                            WHERE id_tax_zone = ? AND id_continent IN ('.implode(",", $_POST['continents']).')';
                    $this->query($sql, $_POST['id_tax_zone']);
                }
                if(isset($_POST['countries'])) {
                    $sql = 'DELETE FROM '.DDBB_PREFIX.'tax_zone_countries
                            WHERE id_tax_zone = ? AND id_country IN ('.implode(",", $_POST['countries']).')';
                    $this->query($sql, $_POST['id_tax_zone']);
                }
                if(isset($_POST['provinces'])) {
                    $sql = 'DELETE FROM '.DDBB_PREFIX.'tax_zone_provinces
                            WHERE id_tax_zone = ? AND id_province IN ('.implode(",", $_POST['provinces']).')';
                    $this->query($sql, $_POST['id_tax_zone']);
                }
                // I save the selected items
                if(isset($_POST['continents_add'])) {
                    foreach($_POST['continents_add'] as $value) {
                        $sql = 'INSERT INTO '.DDBB_PREFIX.'tax_zone_continents (id_tax_zone, id_continent) VALUES (?, ?)';
                        $this->query($sql, array($_POST['id_tax_zone'], $value));
                    }
                }
                if(isset($_POST['countries_add'])) {
                    foreach($_POST['countries_add'] as $value) {
                        $sql = 'INSERT INTO '.DDBB_PREFIX.'tax_zone_countries (id_tax_zone, id_country) VALUES (?, ?)';
                        $this->query($sql, array($_POST['id_tax_zone'], $value));
                    }
                }
                if(isset($_POST['provinces_add'])) {
                    foreach($_POST['provinces_add'] as $value) {
                        $sql = 'INSERT INTO '.DDBB_PREFIX.'tax_zone_provinces (id_tax_zone, id_province) VALUES (?, ?)';
                        $this->query($sql, array($_POST['id_tax_zone'], $value));
                    }
                }
                return array(
                    'response' => 'ok',
                    'message' => 'The tax zone has been successfully updated!'
                );
            } else {
                return array(
                    'response' => 'error',
                    'message' => 'That name already exists. Try another one.'
                );
            }
        }

        public function delete_tax_zone($id_tax_zone) {
            $sql = 'DELETE FROM '.DDBB_PREFIX.'tax_zones WHERE id_tax_zone = ? LIMIT 1';
            $this->query($sql, array($id_tax_zone));
            $sql = 'DELETE FROM '.DDBB_PREFIX.'tax_zone_continents WHERE id_tax_zone = ?';
            $this->query($sql, array($id_tax_zone));
            $sql = 'DELETE FROM '.DDBB_PREFIX.'tax_zone_countries WHERE id_tax_zone = ?';
            $this->query($sql, array($id_tax_zone));
            $sql = 'DELETE FROM '.DDBB_PREFIX.'tax_zone_provinces WHERE id_tax_zone = ?';
            $this->query($sql, array($id_tax_zone));
            // I remove what is related to the methods
            $sql = 'DELETE FROM '.DDBB_PREFIX.'tax_types_zones WHERE id_tax_zone = ?';
            $this->query($sql, array($id_tax_zone));
            $sql = 'DELETE FROM '.DDBB_PREFIX.'tax_types_percent WHERE id_tax_zone = ?';
            $this->query($sql, array($id_tax_zone));
            return array(
                'response' => 'ok'
            );            
        }

    }

?>