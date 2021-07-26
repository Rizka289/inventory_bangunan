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
        if (!httpmethod())
            response(['message' => 'Ilegal akses'], 403);

        if (!isset($_POST['id']))
            response(['message' => 'File (form) kosong'], 404);
        $form = $_POST['id'];

        if (!file_exists(get_path('views', $form . '.php')))
            response(['message' => 'Form yang ' . $form . ' Tidak ditemukan'], 404);
        else {
            $data = json_decode($_POST['data']);
            $this->addViews($form, (array) $data);
            $this->render();
        }
    }
}