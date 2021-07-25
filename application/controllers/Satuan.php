<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Satuan extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Satuan_model');
		must_login();
	}

	public function index()
	{
		$data = [
			"title" => "Kelola Satuan",
		];
		$data = [
			"satuan" => $this->Satuan_model->getAll();
		];


		$this->load->view('', $data);
	}
	
}
