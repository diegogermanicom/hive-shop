<?php

    /**
     * @author Diego Martín
     * @copyright Hive®
     * @version 1.0
     * @lastUpdated 2025
     */

    class AdminModel extends Model {

        function __construct() {
            parent::__construct();
            if(HAS_DDBB == false) {
                Utils::error('If you want to use the administrator you have to activate the access to the database.');
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
                        'message' => 'You do not have permissions to perform this action.'
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
                        'message' => 'You do not have permissions to perform this action.'
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
                        Utils::initCookie('admin_remember', $row["remember_code"], Utils::ONEMONTH);
                    }
                    return array(
                        'response' => 'ok'
                    );
                } else {
                    return array(
                        'response' => 'error',
                        'message' => LANGTXT['user-admin-fail']
                    );                    
                }
            } else {
                return array(
                    'response' => 'error',
                    'message' => LANGTXT['error-login-admin']
                );
            }
        }

        public function parse_slug($text) {
            $text = strtolower($text);
            $text = str_replace(array(' ', '.', ',', '?', '¿', '!', '¡', '&', '#', '@', '/', '_', '"', "'". '`', '^'), '-', $text);
            return $text;
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

        public function create_all_product_routes($id_product = null) {
            // I create the routes of a product or of all the products
            if($id_product == null) {
                $sql = 'DELETE FROM '.DDBB_PREFIX.'products_routes';
                $this->query($sql);
                $sql = 'SELECT * FROM '.DDBB_PREFIX.'products_categories';
                $result_categories = $this->query($sql);
            } else {
                $sql = 'DELETE FROM '.DDBB_PREFIX.'products_routes WHERE id_product = ?';
                $this->query($sql, array($id_product));
                $sql = 'SELECT * FROM '.DDBB_PREFIX.'products_categories WHERE id_product = ?';
                $result_categories = $this->query($sql, array($id_product));
            }
            while($row_category = $result_categories->fetch_assoc()) {
                $sql = 'SELECT * FROM '.DDBB_PREFIX.'products_language WHERE id_product = ?';
                $result_languages = $this->query($sql, array($row_category['id_product']));
                while($row_language = $result_languages->fetch_assoc()) {
                    $category_route = $this->get_category_route($row_category['id_category'], $row_language['id_language']);
                    $product_route = $category_route.'/'.$row_language['slug'];
                    $sql = 'INSERT INTO '.DDBB_PREFIX.'products_routes (id_product, id_category, id_language, route) VALUES (?, ?, ?, ?)';
                    $this->query($sql, array($row_language['id_product'], $row_category['id_category'], $row_language['id_language'], $product_route));
                }
            }
        }

        public function get_category_route($id_category, $id_lang, $route = '') {
            // I get the complete route of the category
            $sql = 'SELECT c.id_parent, l.slug FROM '.DDBB_PREFIX.'categories AS c
                        INNER JOIN '.DDBB_PREFIX.'categories_language AS l ON l.id_category = c.id_category
                    WHERE c.id_category = ? AND l.id_language = ? LIMIT 1';
            $result = $this->query($sql, array($id_category, $id_lang));
            if($result->num_rows != 0) {
                $row = $result->fetch_assoc();
                if($row['id_parent'] != 1) {
                    return $this->get_category_route($row['id_parent'], $id_lang, '/'.$row['slug'].$route);
                }
                return '/'.$row['slug'].$route;
            } else {
                return '';
            }
        }

    }

?>