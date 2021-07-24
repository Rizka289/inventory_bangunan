<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ws extends CI_Controller{
    function login(){
        $this->load->library('Auth');
        $post = $this->auth->prepare($_POST);
        $this->auth->login($post);
    }
}