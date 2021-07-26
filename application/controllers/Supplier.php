<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier extends CI_Controller
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
			'subPageName' => 'Kelola Supplier',
			'data_content' => array(
				'dtid' => 'dt-supplier',
				'dtTitle' => 'Daftar Supplier Material',
				'head' => array(
					'Id',
					'Nama Lengkap',					
					'Alamat',					
					'Kota',					
					'Telepon',					
				)
			),
			'content' => array('components/compui/datatables.responsive'),
			'sidebarConf' => config_sidebar('admin', 4, 2)
		];
		$this->add_cachedJavascript('utils/datatables.renderer', 'file', 'body:end', [
			'tableid' => 'dt-supplier',
			'url_tambah_data' => 'supplier/add',
			'url_sumber_data' => 'supplier/list',
			'url_update_data' => 'supplier/update',
			'url_delete_data' => 'supplier/delete',
			'form' => 'forms/tambah_supplier',
			'adaCheckbox' => "true",
			'row_scirpt' => "(d, i) => {
				return '<tr>' +
						'<td>' + d.id_supplier + '</td>'+
						'<td>' + d.nama + '</td>'+
						'<td>' + d.alamat + '</td>'+
						'<td>' + d.kota + '</td>'+
						'<td>' + d.telepon + '</td>'+
					'</tr>';
			} ",
			'editCallback' => "(data) => {
				$('#nama').val(data[1])
				$('#alamat').val(data[2])
				$('#kota').val(data[3])
				$('#hp').val(data[4])
				$('#id').val(data[0])
			}",
		]);
		$this->addViews('templates/backoffice_dore', $data);
		$this->render();
	}

	function list(){
		$res = $this->db->get('supplier')->result();
		response(['data' => $res]);
	}

	function add(){
		if(!httpmethod())
			response("Metode Akses Ilegal", 403);

		try {
            $input = fieldmapping($_POST, 'supplier');
            $this->db->insert('supplier', $input);
        } catch (\Throwable $th) {
            response("Gagal, Terjadi kesalahan", 500);
        }
    
        response("Berhasil menambah supplier");
	}

	function update(){
		if(!httpmethod())
			response("Metode Akses Ilegal", 403);

		try {
            $input = fieldmapping($_POST, 'supplier');
            $this->db->where('id_supplier', $_POST['id'])->update('supplier', $input);
        } catch (\Throwable $th) {
            response("Gagal, Terjadi kesalahan", 500);
        }
        response("Berhasil Mengupdate supplier");
	}

	function delete(){
		if(!httpmethod())
			response("Metode Akses Ilegal", 403);

        try {
            $this->db->where_in('id_supplier', $_POST['ids'])->delete('supplier');
        } catch (\Throwable $th) {
            response("Gagal, Terjadi kesalahan", 500);

        }
        response("Berhasil Menghapus supplier");
	}
}
