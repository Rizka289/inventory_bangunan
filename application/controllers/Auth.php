<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    function index(){
        $data = array(
            'resource' => array('main', 'dore'),
            'content' => array('pages/login'),
            'data_content' => array('formid' => 'form-login')
            // 'pageName' => 'Hallo, Selamat Datang'
        );
        $this->addViews('templates/admin_dore', $data);

        $this->add_cachedJavascript('pages/login', 'file', 'body:end', ['formid' => 'form-login']);

        $this->render();
    }
}