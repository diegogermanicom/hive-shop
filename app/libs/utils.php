<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2025
    */

    class Utils {

        public const ONEYEAR = (24 * 60 * 60 * 365);
        public const ONEMONTH = (24 * 60 * 60 * 30);
        public const ONEWEEK = (24 * 60 * 60 * 7);

        public static function validateDomain($dominio) {
            $result = preg_match('/^(?!\-)(?:[a-zA-Z0-9\-]{1,60}\.)+[a-zA-Z]{2,20}$/', $dominio);
            return $result;
        }

        public static function validateSlug($slug) {
            $result = preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug);
            return $result;
        }

        public static function validateRelativePath($path) {
            $result = preg_match('#^/?[a-zA-Z0-9/_-]+$#', $path);
            return $result;
        }

        public static function error($message) {
            $html = '<html>';
            $html .=    '<head>';
            $html .=        '<title>Error</title>';
            $html .=        '<meta charset="UTF-8">';
            $html .=        '<meta name="viewport" content="width=device-width, initial-scale=1">';
            $html .=        '<style>';
            $html .=            'body {';
            $html .=                'font-size: 18px; font-family: arial; color: #494949; padding: 20px 20px 20px 20px;';
            $html .=            '}';
            $html .=            'div.content {';
            $html .=                'max-width: 800px; background-color: #e7e7e7; border: 2px solid #c7c7c7; padding: 40px 50px 40px 50px; margin: auto auto;';
            $html .=            '}';
            $html .=            'div.title {';
            $html .=                'font-size: 22px; border-bottom: 2px solid #c7c7c7; padding-bottom: 10px; margin-bottom: 20px;';
            $html .=            '}';
            $html .=        '</style>';
            $html .=    '</head>';
            $html .=    '<body>';
            $html .=        '<div class="content">';
            $html .=            '<div class="title"><b>An error has occurred</b></div>';
            $html .=            '<div>'.$message.'</div>';
            $html .=            '<div style="padding-top: 40px;">';
            $html .=                '<button onclick="window.history.back()">Return to the previous page</button>';
            $html .=            '</div>';
            $html .=        '</div>';
            $html .=    '</body>';
            $html .= '</html>';
            echo $html;
            exit;
        }
        
        public static function errorPost($message) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(array(
                'message' => $message
            ));
            exit;
        }

        public static function debug($var) {
            echo '<pre>';
            var_dump($var);
            echo '</pre>';
            exit;
        }

        public static function query($sql, $params = null) {
            Utils::checkDefined('HAS_DDBB', 'LANGTXT');
            if(HAS_DDBB == true) {
                // This function is created to avoid malicious sql injections
                global $DB;
                $query = $DB->db->prepare($sql);
                if($params != null) {
                    $type = '';
                    $types = array(
                        'integer' => 'i', 'double' => 'd',
                        'string' => 's', 'boolean' => 'b'
                    );
                    if(!is_array($params)) {
                        $params = array($params);
                    }
                    foreach($params as $value) {
                        if($value == NULL) {
                            $type .= 's';
                        } else if(isset($types[gettype($value)])) {
                            $type .= $types[gettype($value)];
                        }
                    }    
                    if(!@$query->bind_param($type, ...$params)) {
                        Utils::error(LANGTXT['error-query-description']);
                    }
                }
                $query->execute();
                return $query->get_result();
            } else {
                return null;
            }
        }

        public static function error_log($message) {
            $sql = 'INSERT INTO error_log (message) VALUES (?)';
            Utils::query($sql, array($message));
        }

        public static function settingsValidator($settings) {
            if($settings['HOST_DEV'] != '' && !Utils::validateDomain($settings['HOST_DEV'])) {
                Utils::error('The value of the HOST_DEV constant is incorrect. Must be a valid domain.');
            }
            if($settings['HOST_PRO'] != '' && !Utils::validateDomain($settings['HOST_PRO'])) {
                Utils::error('The value of the HOST_PRO constant is incorrect. Must be a valid domain.');
            }
            if($settings['DEV']['PROTOCOL'] != '' && !in_array($settings['DEV']['PROTOCOL'], array('http', 'https'))) {
                Utils::error('The value of the DEV > PROTOCOL constant is incorrect. Must be a valid protocol (http or https).');
            }
            if($settings['DEV']['PUBLIC_PATH'] == '/') {
                Utils::error('To indicate the root directory, leave the DEV > PUBLIC_PATH field empty.');
            }
            if($settings['DEV']['PUBLIC_PATH'] != '' && !Utils::validateRelativePath($settings['DEV']['PUBLIC_PATH'])) {
                Utils::error('The value of the DEV > PUBLIC_PATH constant is incorrect. Must be a valid relative path.');
            }
            if($settings['PRO']['PROTOCOL'] != '' && !in_array($settings['PRO']['PROTOCOL'], array('http', 'https'))) {
                Utils::error('The value of the PRO > PROTOCOL constant is incorrect. Must be a valid protocol (http or https).');
            }
            if($settings['PRO']['PUBLIC_PATH'] == '/') {
                Utils::error('To indicate the root directory, leave the PRO > PUBLIC_PATH field empty.');
            }
            if($settings['PRO']['PUBLIC_PATH'] != '' && !Utils::validateRelativePath($settings['PRO']['PUBLIC_PATH'])) {
                Utils::error('The value of the PRO > PUBLIC_PATH constant is incorrect. Must be a valid relative path.');
            }
            if(!Utils::validateSlug($settings['APP_NAME'])) {
                Utils::error('The value of the APP_NAME constant is incorrect. Must be a valid slug.');
            }
            if(!Utils::validateSlug($settings['ADMIN_NAME'])) {
                Utils::error('The value of the ADMIN_NAME constant is incorrect. Must be a valid slug.');
            }
            if(!is_bool($settings['MULTILANGUAGE'])) {
                Utils::error('The value of the MULTILANGUAGE constant is incorrect. It has to be a boolean variable.');
            }
            if(!is_array($settings['LANGUAGES'])) {
                Utils::error('The value of the LANGUAGES constant is incorrect. It has to be a array variable.');
            }
            if(!is_bool($settings['HAS_DDBB'])) {
                Utils::error('The value of the HAS_DDBB constant is incorrect. It has to be a boolean variable.');
            }
            if(!is_bool($settings['MAINTENANCE'])) {
                Utils::error('The value of the MAINTENANCE constant is incorrect. It has to be a boolean variable.');
            }
            if(!is_array($settings['MAINTENANCE_IPS'])) {
                Utils::error('The value of the MAINTENANCE_IPS constant is incorrect. It has to be a array variable.');
            }
            if($settings['EMAIL_FROM'] != '' && !filter_var($settings['EMAIL_FROM'], FILTER_VALIDATE_EMAIL)) {
                Utils::error('The value of the DEV > PUBLIC_PATH constant is incorrect. Must be a valid email.');
            }
            if($settings['FTP_UPLOAD_SERVER_PATH'] != '' && !Utils::validateRelativePath($settings['FTP_UPLOAD_SERVER_PATH'])) {
                Utils::error('The value of the FTP_UPLOAD_SERVER_PATH constant is incorrect. Must be a valid relative path.');
            }
        }

        public static function checkDefined($definedVars) {
            if(is_array($definedVars)) {
                foreach($definedVars as $var) {
                    if(!defined($var)) {
                        Utils::error('The '.$var.' constant does not exist.');
                    }
                }
            } else {
                if(!defined($definedVars)) {
                    Utils::error('The '.$definedVars.' constant does not exist.');
                }
            }
        }

        public static function init() {
            Utils::checkDefined('APP_NAME');
            date_default_timezone_set('Europe/Madrid');
            ignore_user_abort(true);
            ini_set('memory_limit', '256M');
            // Start user session
            session_name(APP_NAME);
            session_start();
        }

        public static function getEnviroment() {
            Utils::checkDefined(array('HOST', 'HOST_DEV', 'HOST_PRO'));
            if(strpos(HOST, HOST_DEV) !== false && HOST_DEV != '') {
                error_reporting(E_ALL);
                ini_set('display_errors', '1');
                return 'DEV';
            } else if(strpos(HOST, HOST_PRO) !== false && HOST_PRO != '') {
                error_reporting(0);
                ini_set('display_errors', '0');
                return 'PRO';
            } else {
                Utils::error('Permission denied.');
            }        
        }

        public static function getLanguage() {
            Utils::checkDefined(array('MULTILANGUAGE', 'PUBLIC_PATH', 'ROUTE', 'LANGUAGES', 'LANGUAGE', 'LANG_PATH'));
            if(MULTILANGUAGE == true) {
                // First I try to get the language from the route
                $lang = explode(PUBLIC_PATH.'/', ROUTE)[1];
                if(strpos($lang, '/') !== false) {
                    $lang = explode('/', $lang)[0];
                }
                // Second attempt to get the cookie value
                if(!in_array($lang, LANGUAGES) && isset($_COOKIE['lang'])) {
                    $lang = $_COOKIE['lang'];
                }
                // Third attempt to get the language value from the browser
                if(!in_array($lang, LANGUAGES) && isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
                }
                // Fourth attempt I give the value of the default language of the web
                if(!in_array($lang, LANGUAGES)) {
                    $lang = LANGUAGE;
                }
                // If the application is multilanguage and does not point to any language
                if(in_array(ROUTE, array(PUBLIC_PATH.'/', PUBLIC_PATH))) {
                    header('Location: '.PUBLIC_PATH.'/'.$lang);
                    exit;
                }
            } else {
                $lang = LANGUAGE;
            }
            // I check that the language text package file exists
            if(!file_exists(LANG_PATH.'/'.$lang.'.php')) {
                $lang = LANGUAGE;
                if(!file_exists(LANG_PATH.'/'.$lang.'.php')) {
                    Utils::error('The configuration file of the default language of the app does not exist. Check the <b>langs</b> folder.');
                }
            }
            setcookie('lang', $lang, time() + Utils::ONEYEAR, PUBLIC_PATH.'/'); // 1 año
            $_COOKIE['lang'] = $lang;
            return $lang;
        }

        public static function setThemeColor() {
            Utils::checkDefined('PUBLIC_PATH');
            if(!isset($_COOKIE['color-mode'])) {
                setcookie('color-mode', 'light-mode', time() + Utils::ONEYEAR, PUBLIC_PATH.'/'); // 1 año
                $_COOKIE['color-mode'] = 'light-mode';
            }        
        }

        public static function checkServiceDownView() {
            Utils::checkDefined(array('MAINTENANCE', 'ROUTE', 'PUBLIC_ROUTE'));
            if(MAINTENANCE == false && ROUTE == PUBLIC_ROUTE.'/service-down') {
                header('Location: '.PUBLIC_ROUTE);
                exit;
            }        
        }

    }

?>