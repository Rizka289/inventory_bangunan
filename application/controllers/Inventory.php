<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory extends CI_Controller
{

	private $params = array(
		"pageName" => "Inventory Banguanan",
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
			'subPageName' => 'List',
			'data_content' => array(
				'dtid' => 'dt-inventory',
				'dtTitle' => 'Daftar Inventory Bangunan',
				'head' => array(
					'Id',
					'Material',					
					'Merk',					
					'Satuan',					
					'Supplier',					
					'Jumlah',					
					'Harga',
                    'Total',
                    'Keterangan',		
                    'Tanggal',		
				)
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
						'<td>' + d.id_inventory_bangunan + '</td>'+
						'<td>' + d.nama_material + '</td>'+
						'<td>' + d.merk_material + '</td>'+
						'<td>' + d.uom + '</td>'+
						'<td>' + d.nama + '</td>'+
						'<td>' + d.jumlah + '</td>'+
						'<td>' + 'Rp.' + d.harga.rupiahFormat() + '</td>'+
						'<td>' + d.total + '</td>'+
						'<td>' + d.keterangan + '</td>'+
						'<td>' + d.tanggal + '</td>'+
					'</tr>';
			} ",
			'editCallback' => "(data) => {
				$('#material option:contains(' + data[1] + ')').prop('selected', true);
				$('#merk option:contains(' + data[2] + ')').prop('selected', true);
				$('#satuan option:contains(' + data[3] + ')').prop('selected', true);
				$('#supplier option:contains(' + data[4] + ')').prop('selected', true);
				$('#jumlah').val(data[5])
				$('#harga').val(data[6])
				$('#keterangan').val(data[8])
				$('#id').val(data[0])
			}",
            'formdata' => array(
                'merk' => $merk,
                'material' => $material,
                'supplier' => $supplier,
                'satuan' => $satuan,
            ), 
            'extra_button' => array(
                array(
                    "text" => "Pindah Ke Stok",
                    'funt' => "(e, dt, node, config) => {

                    }"
                )
            )
		]);
		$this->addViews('templates/backoffice_dore', $data);
		$this->render();
	}

	function list(){
        $res = $this->db->select('inventory_bangunan.id_inventory_bangunan, inventory_bangunan.jumlah, inventory_bangunan.harga, inventory_bangunan.total, inventory_bangunan.keterangan')
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

		try {
            $input = fieldmapping($_POST, 'inventory', ['tanggal' => waktu(null, MYSQL_DATE_FORMAT)]);
            $this->db->insert('inventory_bangunan', $input);
        } catch (\Throwable $th) {
            response("Gagal, Terjadi kesalahan", 500);
        }
    
        response("Berhasil menambah inventory");
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
        response("Berhasil Mengupdate inventory");
	}

	function delete(){
		if(!httpmethod())
			response("Metode Akses Ilegal", 403);

        try {
            $this->db->where_in('id_supplier', $_POST['ids'])->delete('supplier');
        } catch (\Throwable $th) {
            response("Gagal, Terjadi kesalahan", 500);

        }
        response("Berhasil Menghapus inventory");
	}
}
