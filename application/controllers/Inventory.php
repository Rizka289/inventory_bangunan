<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory extends CI_Controller
{

	private $params = array(
		"pageName" => "Gudang",
		'navbar' => 'components/navbar/navbar_dore',
		'sidebar' => 'components/sidebar/sidebar_dore',
		'loadingAnim' => true,
		'adaThemeSelector' => true,
		'navbarConf' => array('adaSidebar' => true)
	);
	public function __construct()
	{
		parent::__construct();
		if(!is_login('admin'))
			response("Tidak memiliki akses", 403);

		$this->load->model('Inventory_model');
		
	}

	public function index()
	{
		$data = $this->params + [
			'resource' => array('main', 'dore', 'icons', 'form', 'datatables'),
			'subPageName' => 'List',
			'data_content' => array(
				'dtid' => 'dt-inventory',
				'dtTitle' => 'Daftar Barang di Gudang',
				'head' => array('No','Id', 'Material','Merk','Satuan','Supplier','Harga','Total')
			),
			'content' => array('components/compui/datatables.responsive'),
			'sidebarConf' => config_sidebar('admin', 1, 0)
		];

        $material = $this->db->get('nama_material')->result();
        $merk = $this->db->get('merk_material')->result();
        $supplier = $this->db->get('supplier')->result();
        $satuan = $this->db->get('uom')->result();

		$this->add_cachedJavascript('utils/datatables.renderer', 'file', 'body:end', [
			'tableid' => 'dt-inventory',
			'url_tambah_data' => 'inventory/add',
			'url_sumber_data' => 'inventory/list',
			'url_update_data' => 'inventory/update',
			'url_delete_data' => 'inventory/delete',
			'form' => 'forms/barang_masuk',
			'adaCheckbox' => "true",
			'row_scirpt' => "(d, i) => {
				return '<tr>' +
						'<td>' + parseInt(i+1) + '</td>'+
						'<td>' + d.id_inventory_bangunan + '</td>'+
						'<td>' + d.nama_material + '</td>'+
						'<td>' + d.merk_material + '</td>'+
						'<td>' + d.uom + '</td>'+
						'<td>' + d.nama + '</td>'+
						'<td>' + 'Rp.' + d.harga.rupiahFormat() + '</td>'+
						'<td>' + d.total + '</td>'+
					'</tr>';
			} ",
            'formdata' => array(
                'merk' => $merk,
                'material' => $material,
                'supplier' => $supplier,
                'satuan' => $satuan,
            ), 
            'extra_button' => array(
                array(
                    "text" => "Pindah Ke Stok",
                    'funct' => load_script('utils/kestok', true)
                )
			),
			'index_id' => 1,
			'ada_edit' => false
		]);
		$this->addViews('templates/backoffice_dore', $data);
		$this->render();
	}

	function list(){
        $res = $this->db->select('inventory_bangunan.id_inventory_bangunan,  inventory_bangunan.harga, inventory_bangunan.total')
            ->select('inventory_bangunan.tanggal, nama_material.nama_material, merk_material.merk_material, uom.uom, supplier.nama')
            ->from('inventory_bangunan')->join('merk_material', 'inventory_bangunan.id_merk_material = merk_material.id_merk_material')
            ->join('nama_material', 'nama_material.id_nama_material = inventory_bangunan.id_nama_material')
            ->join('supplier', 'inventory_bangunan.id_supplier = supplier.id_supplier')
            ->join('uom', 'uom.id_uom = inventory_bangunan.id_uom')->get()->result();
		response(['data' => $res]);
	}

	function add(){
		if(!httpmethod())
			response("Metode Akses Ilegal", 403);

		$this->Inventory_model->add_inventory($_POST);
		
	}

	function delete(){
		if(!httpmethod())
			response("Metode Akses Ilegal", 403);
		$this->Inventory_model->delete_inventory($_POST);
	}

	function masuk (){
		$data = $this->params + [
			'resource' => array('main', 'dore', 'icons', 'form', 'datatables'),
			'subPageName' => 'Barang Masuk',
			'data_content' => array(
				'dtid' => 'dt-inventory',
				'dtTitle' => 'Daftar Barang Masuk Gudang',
				'head' => array(
					'No',
					'Id',
					'Material',					
					'Merk',					
					'Satuan',					
					'Supplier',						
					'Harga',
					'Jumlah',
					'Sebelumnya',
                    'Total',
                    'Keterangan',	
					'Tanggal'
				)
			),
			'content' => array('components/compui/datatables.responsive'),
			'sidebarConf' => config_sidebar('admin', 1, 1)
		];

        $material = $this->db->get('nama_material')->result();
        $merk = $this->db->get('merk_material')->result();
        $supplier = $this->db->get('supplier')->result();
        $satuan = $this->db->get('uom')->result();

		$this->add_cachedJavascript('utils/datatables.renderer', 'file', 'body:end', [
			'tableid' => 'dt-inventory',
			'url_tambah_data' => 'inventory/add',
			'url_sumber_data' => 'inventory/list_masuk',
			'url_update_data' => 'inventory/update_masuk',
			'url_delete_data' => 'inventory/delete_masuk',
			'form' => 'forms/barang_masuk',
			'adaCheckbox' => "true",
			'row_scirpt' => "(d, i) => {
				return '<tr>' +
						'<td>' + parseInt(i+1) + '</td>'+
						'<td>' + d.log_id + '</td>'+
						'<td>' + d.nama_material + '</td>'+
						'<td>' + d.merk_material + '</td>'+
						'<td>' + d.uom + '</td>'+
						'<td>' + d.nama + '</td>'+
						'<td>' + 'Rp.' + d.harga.rupiahFormat() + '</td>'+
						'<td>' + d.jumlah + '</td>'+
						'<td>' + d.sebelumnya + '</td>'+
						'<td>' + d.total + '</td>'+
						'<td>' + d.keterangan + '</td>'+
						'<td>' + d.tanggal + '</td>'+
					'</tr>';
			} ",
			'editCallback' => "(data) => {
				$('#material option:contains(' + data[2] + ')').prop('selected', true);
				$('#merk option:contains(' + data[3] + ')').prop('selected', true);
				$('#satuan option:contains(' + data[4] + ')').prop('selected', true);
				$('#supplier option:contains(' + data[5] + ')').prop('selected', true);
				$('#jumlah').val(data[7])
				$('#harga').val(data[6].replace('Rp.', '').replaceAll('.', '').replace(',', ''))
				$('#keterangan').val(data[10])
				$('#id').val(data[1])
			}",
            'formdata' => array(
                'merk' => $merk,
                'material' => $material,
                'supplier' => $supplier,
                'satuan' => $satuan,
            ),
			'index_id' => 1
		]);
		$this->addViews('templates/backoffice_dore', $data);
		$this->render();
	}

	function list_masuk(){
		$res = $this->db->select('log_barang.log_id, log_barang.sebelumnya, log_barang.jumlah, log_barang.harga, log_barang.total, log_barang.keterangan')
            ->select('log_barang.tanggal, nama_material.nama_material, merk_material.merk_material, uom.uom, supplier.nama')
            ->from('log_barang')->join('merk_material', 'log_barang.id_merk_material = merk_material.id_merk_material')
            ->where('log_barang.jenis', 'masuk')
			->join('nama_material', 'nama_material.id_nama_material = log_barang.id_nama_material')
            ->join('supplier', 'log_barang.id_supplier = supplier.id_supplier')
            ->join('uom', 'uom.id_uom = log_barang.id_uom')->get()->result();
		response(['data' => $res]);
	}

	function update_masuk(){
		if(!httpmethod())
			response("Cara Akses Ilegal", 403);

		$this->Inventory_model->update_barang_masuk($_POST);
	}

	function delete_masuk(){
		if(!httpmethod())
			response("Cara Akses Ilegal", 403);

		$this->Inventory_model->delete_barang_masuk($_POST);

	}

	function kestok(){
		if(!httpmethod())
			response("Cara Akses Ilegal", 403);

		$this->Inventory_model->pindahkan_ke_stok($_POST);
	}

}
