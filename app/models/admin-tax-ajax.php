<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2025
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
                return array('response' => 'ok');
            } else {
                return array(
                    'response' => 'error',
                    'message' => 'That name already exists. Try another one.'
                );
            }
        }

        public function save_edit_tax_type() {
            // I check that this alias no longer exists.
            $sql = 'SELECT id_tax_type FROM '.DDBB_PREFIX.'tax_types WHERE name = ? LIMIT 1';
            $result = $this->query($sql, $_POST['name']);
            if($result->num_rows == 0) {
                return array('response' => 'ok');
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
            return array('response' => 'ok');            
        }

    }

?>