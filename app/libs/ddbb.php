<?php

    /**
     * @author Diego Martín
     * @copyright Hive®
     * @version 1.0
     * @lastUpdated 2025
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
                    Utils::error('An error occurred while connecting to the database. Please check your connection credentials and domain.');
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