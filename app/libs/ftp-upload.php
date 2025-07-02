<?php

    /**
     * Upload Ftp
     * @author Diego Martín
     * @copyright Hive®
     * @version 1.0
     * Dep: Font Awesome, jQuery
     * Files:
     *  - /js/ftp-upload.js
     *  - /app/lib/ftp-upload.php
     *  - /app/views/admin/ftp-upload-view.php
     */   

    class FtpUpload {

        public $conn;
        public $conn_success = false;
        public $messages = array(
            'The directory does not exist.',
            'Access denied.',
            'Error saving file.',
            'Error logging in.',
            'The file has been uploaded successfully.',
            'The directory has been created successfully.',
            'Error creating directory.',
            'Error reading file from ftp.'
        );
        public $banned_files = array(
            'ftp-upload-view.php',
            'ftp-upload.php',
            'ftp-upload-ajax.php',
            'ftp-upload.js',
            'ftp-upload.scss',
            'ftp-upload.css.map',
            'ftp-upload.css'
        );

        function __destruct() {
            if($this->conn_success == true) {
                ftp_close($this->conn);
            }
        }

        public function connect() {
            if($this->conn = @ftp_connect(FTP_UPLOAD_HOST)) {
                $this->conn_success = true;
                return true;
            } else {
                $this->conn_success = false;
                return false;
            }
        }

        public function login() {
            if(@ftp_login($this->conn, FTP_UPLOAD_USER, FTP_UPLOAD_PASS)) {
                ftp_pasv($this->conn, true);
                return true;
             } else {
                return false;
             }
        }

        public function get_folder_html($folder = '') {
            // Impido que puedan bajar mas de la raiz
            $pos1 = strpos($folder, SERVER_PATH);
            $pos2 = strpos($folder, SERVER_PATH.'/..');
            if(($pos1 !== false && $pos2 === false) || $folder == '') {
                if($folder == '') {
                    $folder = SERVER_PATH;
                } else if(substr($folder, -3) == '/..') {
                    // Si son dos puntos bajo un nivel
                    $array_folder = explode('/', $folder);
                    $folder = '';
                    for($i = 0; $i < (count($array_folder) - 2); $i++) {
                        $folder .= $array_folder[$i].'/';
                    }
                    $folder = substr($folder, 0, -1);
                }
                // Si no existe el directorio da error
                if(file_exists($folder)) {
                    $array_folder = scandir($folder);
                    $array_dir = array();
                    $array_file = array();
                    for($i = 0; $i < count($array_folder); $i++) {
                        if(is_dir($folder.'/'.$array_folder[$i])) {
                            if($array_folder[$i] != '.') {
                                array_push($array_dir, $array_folder[$i]);                            
                            }
                        } else {
                            if(!in_array($array_folder[$i], $this->banned_files)) {
                                $temp = array(
                                    'name' => $array_folder[$i],
                                    'size' => filesize($folder.'/'.$array_folder[$i])
                                );
                                array_push($array_file, $temp);
                            }
                        }
                    }            
                    sort($array_dir);
                    sort($array_file);
                    // Obtengo el directorio del ftp relaccionado
                    $dir = str_replace(SERVER_PATH, "", $folder);
                    $dir = FTP_UPLOAD_SERVER_PATH.$dir.'/';
                    if(!(@ftp_chdir($this->conn, $dir))) {
                        return array(
                            'response' => 'error',
                            'message' => 'Could not switch to directory "'.$dir.'".'
                        );    
                    }
                    // Obtengo la información de los ficheros del ftp
                    $ftp_rawlist = ftp_rawlist($this->conn, '.');
                    // Pintado del html del arbol
                    $html = '<div class="folder" folder="'.$folder.'" server-folder="'.$dir.'"><i class="fas fa-folder-open"></i> '.$folder.'</div>';
                    $html .= '<ul class="dir-list">';
                    // Pintado de los directorios
                    for($i = 0; $i < count($array_dir); $i++) {
                        $css_exist = ' no-existe';
                        for($e = 0; $e < count($ftp_rawlist); $e++) {
                            $ftp_file_info = preg_split("/[\s]+/", $ftp_rawlist[$e], 9);
                            if($array_dir[$i] == $ftp_file_info['8'] || $array_dir[$i] == '..') {
                                $css_exist = '';
                            }
                        }
                        $html .= '<li class="ftp-dir'.$css_exist.'" name="'.$array_dir[$i].'" id-folder="'.$i.'"><i class="fas fa-folder"></i> '.$array_dir[$i].'</li>';
                    }
                    // Pintado de los ficheros
                    for($i = 0; $i < count($array_file); $i++) {
                        $ftp_size = 0;
                        for($e = 0; $e < count($ftp_rawlist); $e++) {
                            $ftp_file_info = preg_split("/[\s]+/", $ftp_rawlist[$e], 9);
                            if($array_file[$i]['name'] == $ftp_file_info['8']) {
                                $ftp_size = $ftp_file_info['4'];
                            }
                        }
                        $size = number_format($array_file[$i]['size'], 0, '.', '.');
                        $css_size = '';
                        // Comparo el tamaño del fichero del ftp con el de desarrollo
                        if($ftp_size != $array_file[$i]['size']) {
                            $css_size = ' warning';
                        }
                        if($ftp_size == 0) {
                            $css_size = ' no-existe';                            
                        }
                        $html .= '<li class="ftp-file'.$css_size.'" id-file="'.$i.'">'.
                                    '<div class="name" name="'.$array_file[$i]['name'].'"><i class="far fa-file"></i> '.$array_file[$i]['name'].'</div>'.
                                    '<div class="size" size="'.$array_file[$i]['size'].'" ftp_size="'.$ftp_size.'">'.$size.' bytes</div>'.
                                '</li>';
                    }
                    $html .= '</ul>';
                    return array(
                        'response' => 'ok',
                        'html' => $html
                    );
                } else {
                    return array(
                        'response' => 'error',
                        'message' => $this->messages[0]
                    );
                }
            } else {
                return array(
                    'response' => 'error',
                    'message' => $this->messages[1]
                );
            }
        }

        public function upload_ftp($folder, $file) {
            $dir = str_replace(SERVER_PATH, "", $folder);
            $dir = FTP_UPLOAD_SERVER_PATH.$dir.'/';
            if(ftp_put($this->conn, $dir.$file, $folder.'/'.$file, FTP_BINARY)) {
                return array(
                    'response' => 'ok',
                    'message' => $this->messages[4]
                );
            } else {
                return array(
                    'response' => 'error',
                    'message' => $this->messages[2]
                );
            }
        }
        
        public function upload_all_ftp($folder, $files) {
            $dir = str_replace(SERVER_PATH, "", $folder);
            $dir = FTP_UPLOAD_SERVER_PATH.$dir.'/';
            $errors = 0;
            for($i = 0; $i < count($files); $i++) {
                if(!ftp_put($this->conn, $dir.$files[$i], $folder.'/'.$files[$i], FTP_BINARY)) {
                    $errors++;
                }
            }
            if($errors == 0) {
                return array(
                    'response' => 'ok',
                    'message' => $this->messages[4]
                );
            } else {
                return array(
                    'response' => 'error',
                    'message' => $this->messages[2]
                );
            }
        }

        public function create_folder($folder, $name) {
            $dir = str_replace(SERVER_PATH, "", $folder);
            $dir = FTP_UPLOAD_SERVER_PATH.$dir.'/';
            if(ftp_mkdir($this->conn, $dir.$name)) {
                return array(
                    'response' => 'ok',
                    'message' => $this->messages[5]
                );
            } else {
                return array(
                    'response' => 'error',
                    'message' => $this->messages[6]
                );                
            }
        }
        
        public function ftp_comparar($folder, $file) {
            $file_server = fopen($folder.'/'.$file, 'r');
            $code_server = '';
            while(!feof($file_server)) {
                $line = fgets($file_server);
                $code_server .= $line;
            }
            $dir = str_replace(SERVER_PATH, "", $folder);
            $dir = FTP_UPLOAD_SERVER_PATH.$dir.'/';
            $temp = fopen('php://temp', 'r+');
            if (ftp_fget($this->conn, $temp, $dir.$file, FTP_BINARY)) {
                $code_ftp = '';
                fseek($temp, 0);
                while(!feof($temp)) {
                    $line = fgets($temp);
                    $code_ftp .= $line;
                }
                return array(
                    'response' => 'ok',
                    'code_server' =>  $code_server,
                    'code_ftp' => $code_ftp
                );
            } else {
                return array(
                    'response' => 'error',
                    'message' => $this->messages[7]
                );                
            }
        }
        
        public function get_ftp_html($folder = '.') {
            $files = ftp_mlsd($this->conn, $folder);
            $array_dir = array();
            $array_file = array();
            for($i = 0; $i < count($files); $i++) {
                if($files[$i]['type'] == 'dir') {
                    array_push($array_dir, $files[$i]['name']);
                } else if($files[$i]['type'] == 'file') {
                    array_push($array_file, $files[$i]['name']);
                }
            }
            sort($array_dir);
            sort($array_file);
            $html = '<div><i class="fas fa-folder-open"></i> '.$folder.'</div>';
            $html .= '<ul class="dir-list">';
            for($i = 0; $i < count($array_dir); $i++) {
                $html .= '<li class="ftp-dir"><i class="fas fa-folder"></i> '.$array_dir[$i].'</li>';
            }
            for($i = 0; $i < count($array_file); $i++) {
                $html .= '<li class="ftp-file"><i class="far fa-file"></i> '.$array_file[$i].'</li>';
            }
            $html .= '</ul>';
            return array(
                'response' => 'ok',
                'html' => $html
            );
        }
        
    }

?>