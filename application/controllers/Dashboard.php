<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    function __construct() {
        parent::__construct();

        if(!is_login())
            redirect(base_url());
    }
    function index(){
        $data = array(
            'resource' => array('main', 'dore', 'icons'),
            'sidebar' => 'components/sidebar/sidebar_dore',
            'navbar' => 'components/navbar/navbar_dore',
            'sidebarConf' => config_sidebar('admin'),
            'navbarConf' => array(
                'adaSidebar' => true
            )
        );
        $this->addViews('templates/backoffice_dore', $data);
        $this->render();
    }
}