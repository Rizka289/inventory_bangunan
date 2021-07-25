<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Satuan extends CI_Controller
{

	private $params = array(
		"pageName" => "Utils",
		'navbar' => 'components/navbar/navbar_dore',
		'sidebar' => 'components/sidebar/sidebar_dore',
		'loadingAnim' => true,
		'navbarConf' => array('adaSidebar' => true)
	);
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Satuan_model');
		if(!is_login('admin'))
			response("Tidak memiliki akses", 403);
	}

	public function index()
	{


		$data = $this->params + [
			'resource' => array('main', 'dore', 'icons', 'form', 'datatables'),
			'subPageName' => 'Kelola Satuan',
			'data_content' => array(
				'dtid' => 'dt-satuan',
				'dtTitle' => 'Daftar Satuan Barang',
				'head' => array(
					'Id',
					'Satuan',					
				)
			),
			'content' => array('components/compui/datatables.responsive'),
			'sidebarConf' => config_sidebar('admin', 3, 1)
		];
		$this->add_cachedJavascript('utils/datatables.renderer', 'file', 'body:end', [
			'tableid' => 'dt-satuan',
			'url_tambah_data' => 'satuan/add',
			'url_sumber_data' => 'satuan/list',
			'url_update_data' => 'satuan/update',
			'url_delete_data' => 'satuan/delete',
			'form' => 'forms/tambah_satuan',
			'adaCheckbox' => "true",
			'row_scirpt' => "(d, i) => {
				return '<tr>' +
						'<td>' + d.id_uom + '</td>'+
						'<td>' + d.uom + '</td>'+
					'</tr>';
			} ",
			'editCallback' => "(data) => {
				$('#nama-satuan').val(data[1])
				$('#id').val(data[0])
			}"
		]);
		$this->addViews('templates/backoffice_dore', $data);
		$this->render();
	}

	function list(){
		$res = $this->Satuan_model->getAll();
		response(['data' => $res]);
	}

	function add(){
		if(!httpmethod())
			response("Metode Akses Ilegal", 403);

		$res = $this->Satuan_model->add();
		
		if($res)
			response("Berhasil menambah satuan");
		else
			response("Gagal, Terjadi kesalahan", 500);
	}

	function update(){
		if(!httpmethod())
			response("Metode Akses Ilegal", 403);

		$res = $this->Satuan_model->update($_POST);
		if($res)
			response("Berhasil Mengupdate satuan");
		else
			response("Gagal, Terjadi kesalahan", 500);
	}

	function delete(){
		if(!httpmethod())
			response("Metode Akses Ilegal", 403);

		$res = $this->Satuan_model->delete($_POST['ids']);
		if($res)
			response("Berhasil Menghapus satuan");
		else
			response("Gagal, Terjadi kesalahan", 500);
	}
}
