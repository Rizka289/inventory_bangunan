<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Merk extends CI_Controller
{

	private $params = array(
		"pageName" => "Utils",
		'navbar' => 'components/navbar/navbar_dore',
		'sidebar' => 'components/sidebar/sidebar_dore',
		'loadingAnim' => true,
		'adaThemeSelector' => true,
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
			'subPageName' => 'Kelola Merk',
			'data_content' => array(
				'dtid' => 'dt-merk',
				'dtTitle' => 'Daftar Merk Material',
				'head' => array(
					'Id',
					'Merk',					
				)
			),
			'content' => array('components/compui/datatables.responsive'),
			'sidebarConf' => config_sidebar('admin', 4, 1)
		];
		$this->add_cachedJavascript('utils/datatables.renderer', 'file', 'body:end', [
			'tableid' => 'dt-merk',
			'url_tambah_data' => 'merk/add',
			'url_sumber_data' => 'merk/list',
			'url_update_data' => 'merk/update',
			'url_delete_data' => 'merk/delete',
			'form' => 'forms/tambah_merk',
			'adaCheckbox' => "true",
			'row_scirpt' => "(d, i) => {
				return '<tr>' +
						'<td>' + d.id_merk_material + '</td>'+
						'<td>' + d.merk_material + '</td>'+
					'</tr>';
			} ",
			'editCallback' => "(data) => {
				$('#nama-merk').val(data[1])
				$('#id').val(data[0])
			}"
		]);
		$this->addViews('templates/backoffice_dore', $data);
		$this->render();
	}

	function list(){
		$res = $this->db->get('merk_material')->result();
		response(['data' => $res]);
	}

	function add(){
		if(!httpmethod())
			response("Metode Akses Ilegal", 403);

		try {
            unset($_POST['id']);
            $this->db->insert('merk_material', $_POST);
        } catch (\Throwable $th) {
            response("Gagal, Terjadi kesalahan", 500);
        }
    
        response("Berhasil menambah merk");
	}

	function update(){
		if(!httpmethod())
			response("Metode Akses Ilegal", 403);

		try {
            $this->db->where('id_merk_material', $_POST['id'])->update('merk_material', ['merk_material' => $_POST['merk_material']]);
        } catch (\Throwable $th) {
            response("Gagal, Terjadi kesalahan", 500);
        }
        response("Berhasil Mengupdate merk");
	}

	function delete(){
		if(!httpmethod())
			response("Metode Akses Ilegal", 403);

        try {
            $this->db->where_in('id_merk_material', $_POST['ids'])->delete('merk_material');
        } catch (\Throwable $th) {
            response("Gagal, Terjadi kesalahan", 500);

        }
        response("Berhasil Menghapus merk");
	}
}
