<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    private $pageInfo = array('pageName' => 'Dashboard');
    function __construct()
    {
        parent::__construct();
        if (!is_login())
            redirect(base_url());
    }
    function index()
    {
        $data = $this->pageInfo + array(
            'resource' => array('main', 'dore', 'icons'),
            'sidebar' => 'components/sidebar/sidebar_dore',
            'navbar' => 'components/navbar/navbar_dore',
            'adaThemeSelector' => true,
            'sidebarConf' => config_sidebar(sessiondata('login', 'user_role')),
            'navbarConf' => array(
                'adaSidebar' => true
            )
        );
        $this->addViews('templates/backoffice_dore', $data);
        $this->render();
    }
}
