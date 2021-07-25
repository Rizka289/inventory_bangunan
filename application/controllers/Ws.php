<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ws extends CI_Controller{
    function login(){
        $this->load->library('Auth');
        $post = $this->auth->prepare($_POST);
        $this->auth->login($post);
    }
    function form()
    {
        if (httpmethod())
            response(['message' => 'Ilegal akses'], 403);

        if (!isset($_GET['f']))
            response(['message' => 'File (form) kosong'], 404);
        $form = $_GET['f'];

        if (!file_exists(get_path('views', $form . '.php')))
            response(['message' => 'Form yang ' . $form . ' Tidak ditemukan'], 404);
        else {
            $this->addViews($form);
            $this->render();
        }
    }
}