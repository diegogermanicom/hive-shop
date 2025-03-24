<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2023
     */   

    class AdminModel extends Model {

        function __construct() {
            parent::__construct();
            if(HAS_DDBB == false) {
                new Err(
                    'Error accessing administrator.',
                    'If you want to use the administrator you have to activate the <b>HAS_DDBB</b> variable to true and configure the database.'
                );
            }
        }

        public function setTitle($title) {
            return $title.' | Hive Admin';
        }

        public function security_admin_logout() {
            // I make sure that the admin user is logged out
            if(isset($_SESSION['admin']['id_admin'])) {
                if(METHOD == 'get') {
                    header('Location: '.ADMIN_PATH.'/home');
                    exit;
                } else {
                    return json_encode(array(
                        'response' => 'error',
                        'mensaje' => 'You do not have permissions to perform this action.'
                    ));
                }
            }
        }

        public function security_admin_login() {
            // I make sure that the admin user is logged in
            if(!(isset($_SESSION['admin']['id_admin']))) {
                if(METHOD == 'get') {
                    header('Location: '.ADMIN_PATH.'/login');
                    exit;
                } else {
                    return json_encode(array(
                        'response' => 'error',
                        'mensaje' => 'You do not have permissions to perform this action.'
                    ));
                }                
            }
        }

        public function login($email, $pass, $remember = 0) {
            // Pass must come in md5
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'users_admin WHERE email = ? AND pass = ? LIMIT 1';
            $result = $this->query($sql, array($email, $pass));
            if($result->num_rows != 0) {
                $row = $result->fetch_assoc();
                if($row['id_state'] == 2) {
                    $sql = 'UPDATE '.DDBB_PREFIX.'users_admin SET last_access = NOW(), ip_last_access = ? WHERE id_admin = ? LIMIT 1';
                    $this->query($sql, array($this->get_ip(), $row['id_admin']));
                    $_SESSION['admin'] = [];
                    $_SESSION['admin']['id_admin'] = $row['id_admin'];
                    $_SESSION['admin']['email'] = $row['email'];
                    $_SESSION['admin']['name'] = $row['name'];
                    $_SESSION['admin']['type'] = $row['id_admin_type'];
                    // If the user still does not have a remember code, I will create one for him
                    if($row["remember_code"] == '') {
                        $row["remember_code"] = uniqid();
                        $sql = 'UPDATE '.DDBB_PREFIX.'users_admin SET remember_code = ? WHERE id_admin = ? LIMIT 1';
                        $this->query($sql, array($row["remember_code"], $row['id_admin']));
                    }
                    if($remember == 1) {
                        setcookie("admin_remember", $row["remember_code"], time() + (60 * 60 * 24 * 7), PUBLIC_PATH.'/'); // 7 dias
                    }
                    return array('response' => 'ok');                    
                } else {
                    return array(
                        'response' => 'error',
                        'mensaje' => LANGTXT['user-admin-fail']
                    );                    
                }
            } else {
                return array(
                    'response' => 'error',
                    'mensaje' => LANGTXT['error-login-admin']
                );
            }
        }

        public function parse_slug($text) {
            $text = strtolower($text);
            $text = str_replace(array(' ', '.', ',', '?', '¿', '!', '¡', '&', '#', '@', '/', '_', '"', "'". '`', '^'), '-', $text);
            return $text;
        }

        public function get_related_attributes_string($id_product_related) {
            $sql = 'SELECT r.*, a.alias AS alias_attribute, v.alias AS alias_value FROM '.DDBB_PREFIX.'products_related_attributes AS r
                        INNER JOIN '.DDBB_PREFIX.'attributes AS a ON a.id_attribute  = r.id_attribute
                        INNER JOIN '.DDBB_PREFIX.'attributes_value AS v ON v.id_attribute_value = r.id_attribute_value
                    WHERE r.id_product_related = ?';
            $result = $this->query($sql, array($id_product_related));
            $html = '';
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $html .= $row['alias_attribute'].' '.$row['alias_value'].' - ';
                }
                $html = substr($html, 0, -3);
            } else {
                $html .= 'No attributes';
            }
            return $html;
        }

        public function get_states_list($id_state = 1) {
            $sql = 'SELECT * FROM '.DDBB_PREFIX.'ct_states ORDER BY id_state';
            $result = $this->query($sql);
            $html = '';
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $selected = '';
                    // If it is the view you have selected
                    if($id_state == $row['id_state']) {
                        $selected = ' selected';
                    }
                    $html .= '<option value="'.$row['id_state'].'"'.$selected.'>'.$row['name'].'</option>';
                }
            }
            return $html;
        }

        public function get_state($id_state) {
            $sql = 'SELECT `name` FROM '.DDBB_PREFIX.'ct_states WHERE id_state = ? LIMIT 1';
            $result = $this->query($sql, array($id_state));
            if($result->num_rows != 0) {
                $row = $result->fetch_assoc();
                return $row['name'];
            } else {
                return null;
            }
        }

        public function get_codes_rules_type_list($id_code_rule_type = 1) {
            $sql = 'SELECT * FROM ct_codes_rules_type';
            $result = $this->query($sql);
            $html = '';
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $selected = '';
                    // If it is the view you have selected
                    if($id_code_rule_type == $row['id_code_rule_type']) {
                        $selected = ' selected';
                    }
                    $html .= '<option value="'.$row['id_code_rule_type'].'"'.$selected.'>'.$row['name'].'</option>';
                }
            }
            return $html;
        }

        public function get_codes_rules_add_type_list($id_code_rule_add_type = 1) {
            $sql = 'SELECT * FROM ct_codes_rules_add_type';
            $result = $this->query($sql);
            $html = '';
            if($result->num_rows != 0) {
                while($row = $result->fetch_assoc()) {
                    $selected = '';
                    // If it is the view you have selected
                    if($id_code_rule_add_type == $row['id_code_rule_add_type']) {
                        $selected = ' selected';
                    }
                    $html .= '<option value="'.$row['id_code_rule_add_type'].'"'.$selected.'>'.$row['name'].'</option>';
                }
            }
            return $html;
        }

    }

?>