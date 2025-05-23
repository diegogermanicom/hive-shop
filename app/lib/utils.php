<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2025
    */

    class Utils {
        
        public static function validateDomain($dominio) {
            $result = preg_match('/^(?!\-)(?:[a-zA-Z0-9\-]{1,63}\.)+[a-zA-Z]{2,63}$/', $dominio);
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

        public static function query($sql, $params = null) {
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
                        new Err(
                            LANGTXT['error-query-title'],
                            LANGTXT['error-query-description']
                        );
                    }
                }
                $query->execute();
                return $query->get_result();
            } else {
                return array();
            }
        }

        public static function settingsValidator($settings) {
            if($settings['HOST_DEV'] != '' && !Utils::validateDomain($settings['HOST_DEV'])) {
                exit('The value of the HOST_DEV constant is incorrect. Must be a valid domain.');
            }
            if($settings['HOST_PRO'] != '' && !Utils::validateDomain($settings['HOST_PRO'])) {
                exit('The value of the HOST_PRO constant is incorrect. Must be a valid domain.');
            }
            if($settings['DEV']['PROTOCOL'] != '' && !in_array($settings['DEV']['PROTOCOL'], array('http', 'https'))) {
                exit('The value of the DEV > PROTOCOL constant is incorrect. Must be a valid protocol (http or https).');
            }
            if($settings['DEV']['PUBLIC_PATH'] != '' && !Utils::validateRelativePath($settings['DEV']['PUBLIC_PATH'])) {
                exit('The value of the DEV > PUBLIC_PATH constant is incorrect. Must be a valid relative path.');
            }
            if($settings['PRO']['PROTOCOL'] != '' && !in_array($settings['PRO']['PROTOCOL'], array('http', 'https'))) {
                exit('The value of the PRO > PROTOCOL constant is incorrect. Must be a valid protocol (http or https).');
            }
            if($settings['PRO']['PUBLIC_PATH'] != '' && !Utils::validateRelativePath($settings['PRO']['PUBLIC_PATH'])) {
                exit('The value of the PRO > PUBLIC_PATH constant is incorrect. Must be a valid relative path.');
            }
            if(!Utils::validateSlug($settings['APP_NAME'])) {
                exit('The value of the APP_NAME constant is incorrect. Must be a valid slug.');
            }
            if(!Utils::validateSlug($settings['ADMIN_NAME'])) {
                exit('The value of the ADMIN_NAME constant is incorrect. Must be a valid slug.');
            }
            if(!is_bool($settings['MULTILANGUAGE'])) {
                exit('The value of the MULTILANGUAGE constant is incorrect. It has to be a boolean variable.');
            }
            if(!is_array($settings['LANGUAGES'])) {
                exit('The value of the LANGUAGES constant is incorrect. It has to be a array variable.');
            }
            if(!is_bool($settings['HAS_DDBB'])) {
                exit('The value of the HAS_DDBB constant is incorrect. It has to be a boolean variable.');
            }
            if(!is_bool($settings['MAINTENANCE'])) {
                exit('The value of the MAINTENANCE constant is incorrect. It has to be a boolean variable.');
            }
            if(!is_array($settings['MAINTENANCE_IPS'])) {
                exit('The value of the MAINTENANCE_IPS constant is incorrect. It has to be a array variable.');
            }
            if($settings['EMAIL_FROM'] != '' && !filter_var($settings['EMAIL_FROM'], FILTER_VALIDATE_EMAIL)) {
                exit('The value of the DEV > PUBLIC_PATH constant is incorrect. Must be a valid email.');
            }
        }

    }

?>