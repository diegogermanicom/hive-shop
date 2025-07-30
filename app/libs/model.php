<?php

    /**
     * @author Diego Martín
     * @copyright Hive®
     * @version 1.0
     * @lastUpdated 2025
     */

     class Model {
        
        public $db;
        public $sleep = 200000;
        public $months = [
            'es' => ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            'en' => ['January', 'February', 'March', 'Apri', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
        ];
        public $week_days = [
            'es' => ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'],
            'en' => ['Mondays', 'Tuesdays', 'Wednesdays', 'Thursdays', 'Fridays', 'Saturdays', 'Sundays']
        ];
        public $week_days_min = [
            'es' => ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá', 'Do'],
            'en' => ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su']
        ];

        function __construct() {
            global $DB;
            $this->db = $DB->db;
        }

        public function check_maintenance() {
            // Close the access to the web for maintenance
            if(in_array($this->get_ip(), MAINTENANCE_IPS)) {
                return false;
            }
            if(MAINTENANCE == true && ROUTE != PUBLIC_ROUTE.'/service-down') {
                if(METHOD == 'get') {
                    Utils::redirect('service-down');
                } else {
                    return json_encode(array(
                        'response' => 'error',
                        'message' => 'The website is under maintenance.'
                    ));
                }
            }
        }

        public function query($sql, $params = null) {
            // This function is created to avoid malicious sql injections
            $query = $this->db->prepare($sql);
            if($params != null) {
                $type = '';
                $types = array(
                    'integer' => 'i',
                    'double' => 'd',
                    'string' => 's',
                    'boolean' => 'b'
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
        }

        public function get_ip() {
            // Returns the user's ip
            $ipaddress = '';
            if(isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
            } else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else if(isset($_SERVER['HTTP_X_FORWARDED'])) {
                $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
            } else if(isset($_SERVER['HTTP_FORWARDED_FOR'])) {
                $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
            } else if(isset($_SERVER['HTTP_FORWARDED'])) {
                $ipaddress = $_SERVER['HTTP_FORWARDED'];
            } else if(isset($_SERVER['REMOTE_ADDR'])) {
                $ipaddress = $_SERVER['REMOTE_ADDR'];
            } else {
                $ipaddress = 'desconocida';
            }
            return $ipaddress;
        }

        public function date_to_string($date) {
            $date = explode('-', $date);
            $str_date = intval($date[2]).' de '.$this->months[(intval($date[1]) - 1)].' de '.$date[0];
            return $str_date;
        }

        public function send_email($email, $titulo, $html, $reply = EMAIL_FROM) {
			// To use variables in emails the syntax is <%NAME%> in uppercase and then I do a replace
			$cabeceras = "From: ".EMAIL_HOST." <".EMAIL_FROM.">\r\n";
			$cabeceras .= "Reply-To: ".$reply."\r\n";
			$cabeceras .= "MIME-Version: 1.0\r\n";
			$cabeceras .= "Content-type: text/html; charset=utf-8\r\n";
			$cabeceras .= "X-Mailer: PHP/".phpversion().'\r\n';
    	    mail($email, $titulo, $html, $cabeceras);
		}

        public function base64_to_file($base64_string, $output_file) {
            $file = fopen($output_file, 'wb');
            // Split the string on commas
            // $data[0] == "data:image/png;base64"
            // $data[1] == <actual base64 string>
            $data = explode(',', $base64_string);
            if(count($data) > 1) {
                fwrite($file, base64_decode($data[1]));
            } else {
                fwrite($file, base64_decode($data[0]));
            }
            fclose($file);
        }

        public function parse_float_point($value) {
            $value = str_replace(',', '.', $value);
            return floatval($value);
        }

        public function parse_float_point_back($value) {
            $value = str_replace('.', ',', $value);
            return $value;
        }

        public function get_countries_options($id_continent = null) {
            $sql = 'SELECT * FROM ct_countries';
            $result = $this->query($sql);
            $html = '';
            while($row = $result->fetch_assoc()) {
                if($row['id_country'] == $id_continent) {
                    $selected = ' selected';
                } else {
                    $selected = '';
                }
                $html .= '<option value="'.$row['id_country'].'"'.$selected.'>'.$row[strtolower(LANG)].'</option>';
            }
            return $html;
        }

        public function get_countries_active_options($id_country = null) {
            $sql = 'SELECT * FROM ct_countries WHERE id_state = 2';
            $result = $this->query($sql);
            $html = '';
            while($row = $result->fetch_assoc()) {
                if($row['id_country'] == $id_country) {
                    $selected = ' selected';
                } else {
                    $selected = '';
                }
                $html .= '<option value="'.$row['id_country'].'"'.$selected.'>'.$row[strtolower(LANG)].'</option>';
            }
            return $html;
        }

        public function get_continents_options($id_continent = null) {
            $sql = 'SELECT * FROM ct_continents';
            $result = $this->query($sql);
            $html = '';
            while($row = $result->fetch_assoc()) {
                if($row['id_continent'] == $id_continent) {
                    $selected = ' selected';
                } else {
                    $selected = '';
                }
                $html .= '<option value="'.$row['id_continent'].'"'.$selected.'>'.$row[strtolower(LANG)].'</option>';
            }
            return $html;
        }

        public function get_continents_active_options($id_continent = null) {
            $sql = 'SELECT * FROM ct_continents WHERE id_state = 2';
            $result = $this->query($sql);
            $html = '';
            while($row = $result->fetch_assoc()) {
                if($row['id_continent'] == $id_continent) {
                    $selected = ' selected';
                } else {
                    $selected = '';
                }
                $html .= '<option value="'.$row['id_continent'].'"'.$selected.'>'.$row[strtolower(LANG)].'</option>';
            }
            return $html;
        }

        public function pager($result, $page = 1, $per_page = 20, $link = null) {
            $totalItems = $result->num_rows;
            if($totalItems != 0) {
                $result->data_seek(($page - 1) * $per_page);
                $rows = array();
                $row_count = 1;
                while($row = $result->fetch_assoc()) {
                    array_push($rows, $row);
                    if($row_count == $per_page) {
                        break;
                    } else {
                        $row_count++;
                    }
                }
                // I check if the link is personalized
                if($link == null) {
                    $gets = '';
                    foreach($_GET as $index => $value) {
                        if($index != 'page' && $index != 'per_page') {
                            $gets .= $index.'='.$value.'&';
                        }
                    }
                    $link = ROUTE.'?'.$gets;
                } else {
                    $symbol = (substr_count($link, '?') == 0) ? '?' : '&';
                    $link .= $symbol;
                }
                // Total number of pages
                $total_pages = ceil($totalItems / $per_page);
                if($page > $total_pages) {
                    $page = $total_pages;
                }
                // Record range on current page and total
                $range_init = ((($page - 1) * $per_page) + 1);
                $range_max = ((($page - 1) * $per_page) + $per_page);
                if($range_max > $totalItems) {
                    $range_max = $totalItems;
                }
                $html = '<div class="pager-info pb-20">';
                $html .=    '<span>'.$range_init.'-'.$range_max.' of '.$totalItems.' items</span>';
                $html .= '</div>';
                // I paint the buttons
                $html .= '<div class="pager-pages">';
                if($page > 3) {
                    $html .= '<a href="'.$link.'page=1" class="btn btn-trans btn-sm"><i class="fa-solid fa-angles-left"></i></a>';
                }
                if($page > 1) {
                    $html .= '<div data-page="'.($page - 1).'" class="btn btn-trans btn-sm"><i class="fa-solid fa-chevron-left"></i> Last</div>';
                }
                $min = ($page - 2);
                if($min < 1) {
                    $min = 1;
                }
                $max = ($page + 2);
                if($max > $total_pages) {
                    $max = $total_pages;
                }
                for($i = $min; $i <= $max; $i++) {
                    if($i == $page) {
                        $class = 'btn-black active';
                        $link_temp = '#';
                    } else {
                        $class = 'btn-white';
                        $link_temp = $link.'page='.$i;
                    }
                    $html .= '<a href="'.$link_temp.'" class="btn btn-sm '.$class.'">'.$i.'</a>';
                }
                if($page < ($total_pages - 1)) {
                    $html .= '<a href="'.$link.'page='.($page + 1).'" class="btn btn-trans btn-sm">Next <i class="fa-solid fa-angle-right"></i></a>';
                }
                if($page < ($total_pages - 2)) {
                    $html .= '<a href="'.$link.'page='.$total_pages.'" class="btn btn-trans btn-sm"><i class="fa-solid fa-angles-right"></i></a>';
                }
                $html .= '</div>';            
                $result->data_seek(0);
                return array(
                    'result' => $rows,
                    'pager' => $html
                );    
            } else {
                return array(
                    'result' => array(),
                    'pager' => ''
                );
            }
        }

        public function pagerAjax($result, $page = 1, $per_page = 20) {
            $totalItems = $result->num_rows;
            if($totalItems != 0) {
                $result->data_seek(($page - 1) * $per_page);
                $rows = array();
                $row_count = 1;
                while($row = $result->fetch_assoc()) {
                    array_push($rows, $row);
                    if($row_count == $per_page) {
                        break;
                    } else {
                        $row_count++;
                    }
                }
                // Total number of pages
                $total_pages = ceil($totalItems / $per_page);
                if($page > $total_pages) {
                    $page = $total_pages;
                }
                // Record range on current page and total
                $range_init = ((($page - 1) * $per_page) + 1);
                $range_max = ((($page - 1) * $per_page) + $per_page);
                if($range_max > $totalItems) {
                    $range_max = $totalItems;
                }
                $html = '<div class="pager-info pb-20">';
                $html .=    '<span>'.$range_init.'-'.$range_max.' of '.$totalItems.' items</span>';
                $html .= '</div>';
                // I paint the buttons
                $html .= '<div class="pager-pages">';
                if($page > 3) {
                    $html .= '<div data-page="1" class="btn btn-trans btn-sm"><i class="fa-solid fa-angles-left"></i></div>';
                }
                if($page > 1) {
                    $html .= '<div data-page="'.($page - 1).'" class="btn btn-trans btn-sm"><i class="fa-solid fa-chevron-left"></i> Last</div>';
                }
                $min = ($page - 2);
                if($min < 1) {
                    $min = 1;
                }
                $max = ($page + 2);
                if($max > $total_pages) {
                    $max = $total_pages;
                }
                for($i = $min; $i <= $max; $i++) {
                    if($i == $page) {
                        $class = 'btn-black active';
                    } else {
                        $class = 'btn-white';
                    }
                    $html .= '<div data-page="'.$i.'" class="btn btn-sm '.$class.'">'.$i.'</div>';
                }
                if($page < $total_pages) {
                    $html .= '<div data-page="'.($page + 1).'" class="btn btn-trans btn-sm">Next <i class="fa-solid fa-angle-right"></i></div>';
                }
                if($page < ($total_pages - 2)) {
                    $html .= '<div data-page="'.$total_pages.'" class="btn btn-trans btn-sm"><i class="fa-solid fa-angles-right"></i></div>';
                }
                $html .= '</div>';
                $result->data_seek(0);
                return array(
                    'result' => $rows,
                    'pager' => $html
                );    
            } else {
                return array(
                    'result' => array(),
                    'pager' => ''
                );
            }
        }

    }
    
?>