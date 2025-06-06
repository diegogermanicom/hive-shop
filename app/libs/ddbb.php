<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2025
    */

    class Ddbb {

        public $db = null;

        function __construct() {
            $this->connect_ddbb();
        }

        function __destruct() {
            $this->disconnect_ddbb();
        }

        public function connect_ddbb() {
            if(HAS_DDBB == true) {
                $this->db = @new mysqli(DDBB_HOST, DDBB_USER, DDBB_PASS, DDBB);
                if($this->db->connect_errno) {
                    Utils::error(LANGTXT['error-ddbb-description']);
                } else {
                    $this->db->set_charset("utf8");
                    $this->db->query('SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode, "ONLY_FULL_GROUP_BY",""));');
                }
            }
        }

        public function disconnect_ddbb() {
            if(HAS_DDBB == true) {
                $this->db->close();
            }
        }

    }

?>