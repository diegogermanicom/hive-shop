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
            $this->connect();
        }

        function __destruct() {
            $this->disconnect();
        }

        public function connect() {
            if(HAS_DDBB == true) {
                $this->db = @new mysqli(DDBB_HOST, DDBB_USER, DDBB_PASS, DDBB);
                if($this->db->connect_errno) {
                    Utils::error(LANGTXT['error-ddbb-description']);
                } else {
                    $this->db->set_charset("utf8");
                }
            }
        }

        public function disconnect() {
            if(HAS_DDBB == true) {
                $this->db->close();
            }
        }

    }

?>