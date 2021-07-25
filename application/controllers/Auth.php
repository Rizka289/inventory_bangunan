<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    function index(){
        if(is_login()){
            redirect(base_url("dashboard"));
        }
        $data = array(
            'resource' => array('main', 'dore'),
            'content' => array('pages/login'),
            'data_content' => array('formid' => 'form-login')
            // 'pageName' => 'Hallo, Selamat Datang'
        );
        $this->addViews('templates/backoffice_dore', $data);

        $this->add_cachedJavascript('pages/login', 'file', 'body:end', ['formid' => 'form-login']);

        $this->render();
    }
    function tes(){
        $this->load->helper('mailsender');
        $res = sendemail("fathur.pashter15@gmail.com", "Connected" , "Admin Inventory Barang", "dev.kamscode" , false, 'dev.kamscode@kamscodelab.tech');

        if(!$res['sts'])
            response(['message'=>"Gagal Mengirim Email", 'err' => $res['message']], 500);

        response("COnnected");
    }
}