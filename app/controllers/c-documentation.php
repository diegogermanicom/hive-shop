<?php

    /*
     * Author: Diego Martin
     * Copyright: Hive®
     * Version: 1.0
     * Last Update: 2023
     */

    class CDocumentation extends Controller {

        // Documentation services ------------------------------------------------
        
        public function documentation($args) {
            $app = new App('documentation-page');
            $data = $app->getAppData();
            $data['meta']['title'] = $app->setTitle('Documentation');
            $data['meta']['description'] = 'If you want to know everything that the Hive framework offers you, take a look at the documentation. Clear and simple in less than 10 minutes you will be using Hive like a pro.';
            $data['meta']['keywords'] .= ', documentation, info';
            $data['og']['description'] = 'If you want to know everything that the Hive framework offers you, take a look at the documentation. Clear and simple in less than 10 minutes you will be using Hive like a pro.';
            $data['og']['url'] = 'http://hiveframework.com/documentation';
            $this->view('/doc/documentation', $data);
        }

        public function configuration($args) {
            $app = new App('documentation-configuration-page');
            $data = $app->getAppData();
            $data['meta']['title'] = $app->setTitle('Documentation - Configuration');
            $data['meta']['description'] = 'If you want to know everything that the Hive framework offers you, take a look at the documentation. Clear and simple in less than 10 minutes you will be using Hive like a pro.';
            $data['meta']['keywords'] .= ', documentation, info, configuration';
            $data['og']['description'] = 'If you want to know everything that the Hive framework offers you, take a look at the documentation. Clear and simple in less than 10 minutes you will be using Hive like a pro.';
            $data['og']['url'] = 'http://hiveframework.com/documentation/configuration';
            $this->view('/doc/configuration', $data);
        }

        public function architecture($args) {
            $app = new App('documentation-architecture-page');
            $data = $app->getAppData();
            $data['meta']['title'] = $app->setTitle('Documentation - Architecture');
            $data['meta']['description'] = 'If you want to know everything that the Hive framework offers you, take a look at the documentation. Clear and simple in less than 10 minutes you will be using Hive like a pro.';
            $data['meta']['keywords'] .= ', documentation, info, architecture';
            $data['og']['description'] = 'If you want to know everything that the Hive framework offers you, take a look at the documentation. Clear and simple in less than 10 minutes you will be using Hive like a pro.';
            $data['og']['url'] = 'http://hiveframework.com/documentation/architecture';
            $this->view('/doc/architecture', $data);
        }

        public function routes($args) {
            $app = new App('documentation-routes-page');
            $data = $app->getAppData();
            $data['meta']['title'] = $app->setTitle('Documentation - Routes');
            $data['meta']['description'] = 'If you want to know everything that the Hive framework offers you, take a look at the documentation. Clear and simple in less than 10 minutes you will be using Hive like a pro.';
            $data['meta']['keywords'] .= ', documentation, info, routes';
            $data['og']['description'] = 'If you want to know everything that the Hive framework offers you, take a look at the documentation. Clear and simple in less than 10 minutes you will be using Hive like a pro.';
            $data['og']['url'] = 'http://hiveframework.com/documentation/routes';
            $this->view('/doc/routes', $data);
        }

        public function assets($args) {
            $app = new App('documentation-assets-page');
            $data = $app->getAppData();
            $data['meta']['title'] = $app->setTitle('Documentation - Assets');
            $data['meta']['description'] = 'If you want to know everything that the Hive framework offers you, take a look at the documentation. Clear and simple in less than 10 minutes you will be using Hive like a pro.';
            $data['meta']['keywords'] .= ', documentation, info, assets';
            $data['og']['description'] = 'If you want to know everything that the Hive framework offers you, take a look at the documentation. Clear and simple in less than 10 minutes you will be using Hive like a pro.';
            $data['og']['url'] = 'http://hiveframework.com/documentation/assets';
            $this->view('/doc/assets', $data);
        }

        public function core_css($args) {
            $app = new App('documentation-core-css-page');
            $data = $app->getAppData();
            $data['meta']['title'] = $app->setTitle('Documentation - Core CSS');
            $data['meta']['description'] = 'If you want to know everything that the Hive framework offers you, take a look at the documentation. Clear and simple in less than 10 minutes you will be using Hive like a pro.';
            $data['meta']['keywords'] .= ', documentation, info, core css, css';
            $data['og']['description'] = 'If you want to know everything that the Hive framework offers you, take a look at the documentation. Clear and simple in less than 10 minutes you will be using Hive like a pro.';
            $data['og']['url'] = 'http://hiveframework.com/documentation/core-css';
            $this->view('/doc/core-css', $data);
        }

        public function models($args) {
            $app = new App('documentation-models-page');
            $data = $app->getAppData();
            $data['meta']['title'] = $app->setTitle('Documentation - Models');
            $data['meta']['description'] = 'If you want to know everything that the Hive framework offers you, take a look at the documentation. Clear and simple in less than 10 minutes you will be using Hive like a pro.';
            $data['meta']['keywords'] .= ', documentation, info, models';
            $data['og']['description'] = 'If you want to know everything that the Hive framework offers you, take a look at the documentation. Clear and simple in less than 10 minutes you will be using Hive like a pro.';
            $data['og']['url'] = 'http://hiveframework.com/documentation/models';
            $this->view('/doc/models', $data);
        }

    }

?>