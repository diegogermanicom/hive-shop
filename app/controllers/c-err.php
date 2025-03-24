<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2023
     */   

    class Err extends Controller {

        function __construct($title, $description) {
            $data = array(
                'error_title' => $title,
                'error_description' => $description
            );
            $data['app']['name_page'] = 'error-page';
            $data['meta']['title'] = 'Error page';
            if(file_exists(VIEWS_PUBLIC.'/error.php')) {
                $this->view('/error.php', $data);
            } else {
                echo $title.'<br>You do not have any views associated with this language or the <b>'.PUBLIC_ROUTE.'/error.php</b> file cannot be found.';
            }
        }

    }

?>