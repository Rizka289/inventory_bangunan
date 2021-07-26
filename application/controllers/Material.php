<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Material extends CI_Controller
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
			'subPageName' => 'Kelola Material',
			'data_content' => array(
				'dtid' => 'dt-material',
				'dtTitle' => 'Daftar Material',
				'head' => array(
					'Id',
					'Material',					
				)
			),
			'content' => array('components/compui/datatables.responsive'),
			'sidebarConf' => config_sidebar('admin', 3)
		];
		$this->add_cachedJavascript('utils/datatables.renderer', 'file', 'body:end', [
			'tableid' => 'dt-material',
			'url_tambah_data' => 'material/add',
			'url_sumber_data' => 'material/list',
			'url_update_data' => 'material/update',
			'url_delete_data' => 'material/delete',
			'form' => 'forms/tambah_material',
			'adaCheckbox' => "true",
			'row_scirpt' => "(d, i) => {
				return '<tr>' +
						'<td>' + d.id_nama_material + '</td>'+
						'<td>' + d.nama_material + '</td>'+
					'</tr>';
			} ",
			'editCallback' => "(data) => {
				$('#nama-material').val(data[1])
				$('#id').val(data[0])
			}"
		]);
		$this->addViews('templates/backoffice_dore', $data);
		$this->render();
	}

	function list(){
		$res = $this->db->get('nama_material')->result();
		response(['data' => $res]);
	}

	function add(){
		if(!httpmethod())
			response("Metode Akses Ilegal", 403);

		try {
            unset($_POST['id']);
            $this->db->insert('nama_material', $_POST);
        } catch (\Throwable $th) {
            response("Gagal, Terjadi kesalahan", 500);
        }
    
        response("Berhasil menambah material");
	}

	function update(){
		if(!httpmethod())
			response("Metode Akses Ilegal", 403);

		try {
            $this->db->where('id_nama_material', $_POST['id'])->update('nama_material', ['nama_material' => $_POST['nama_material']]);
        } catch (\Throwable $th) {
            response("Gagal, Terjadi kesalahan", 500);
        }
        response("Berhasil Mengupdate material");
	}

	function delete(){
		if(!httpmethod())
			response("Metode Akses Ilegal", 403);

        try {
            $this->db->where_in('id_nama_material', $_POST['ids'])->delete('nama_material');
        } catch (\Throwable $th) {
            response("Gagal, Terjadi kesalahan", 500);

        }
        response("Berhasil Menghapus material");
	}
}
