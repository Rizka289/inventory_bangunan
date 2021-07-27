<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory extends CI_Controller
{

	private $params = array(
		"pageName" => "Gudang",
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
				'dtTitle' => 'Daftar Barang di Gudang',
				'head' => array(
					'No',
					'Id',
					'Material',					
					'Merk',					
					'Satuan',					
					'Supplier',						
					'Harga',
                    'Total',
                    'Keterangan',	
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
						'<td>' + parseInt(i+1) + '</td>'+
						'<td>' + d.id_inventory_bangunan + '</td>'+
						'<td>' + d.nama_material + '</td>'+
						'<td>' + d.merk_material + '</td>'+
						'<td>' + d.uom + '</td>'+
						'<td>' + d.nama + '</td>'+
						'<td>' + 'Rp.' + d.harga.rupiahFormat() + '</td>'+
						'<td>' + d.total + '</td>'+
						'<td>' + d.keterangan + '</td>'+
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
			),
			'ada_edit' => false
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
			$barang = $this->db->where('id_nama_material', $input['id_nama_material'])
				->where('id_merk_material', $input['id_merk_material'])
				->where('id_uom', $input['id_uom'])->get('inventory_bangunan')->result();

			if(!empty($barang)){
				$input['sebelumnya'] = $barang[0]->total;
				$barang[0]->total = $input['jumlah'] + $barang[0]->total;
				$input['total'] = $barang[0]->total;
				$id = $barang[0]->id_inventory_bangunan;
				unset($barang[0]->id_inventory_bangunan);
				$average = ((($input['harga'] - $barang[0]->harga)/ ($barang[0]->jumlah + $input["jumlah"])) * $input['jumlah']) + $barang[0]->harga;
				$barang[0]->harga = $average;
				$barang[0]->jumlah += $input['jumlah'];
				$this->db->where('id_inventory_bangunan', $id)->update('inventory_bangunan', $barang[0]);
			}
			else{
				$input['total'] = $input['jumlah'];
				$input['sebelumnya'] = 0;
				$this->db->insert('inventory_bangunan', $input);
			}
			
			$input['jenis'] = 'masuk';
			$this->db->insert('log_barang', $input);

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

	function masuk (){
		$data = $this->params + [
			'resource' => array('main', 'dore', 'icons', 'form', 'datatables'),
			'subPageName' => 'Barang Masuk',
			'data_content' => array(
				'dtid' => 'dt-inventory',
				'dtTitle' => 'Daftar Barang di Gudang',
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
			'ada_hapus' => false
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
	
		$input = fieldmapping($_POST, 'inventory');
		$id = $_POST['id'];
		$log = $this->db
			->where('id_nama_material', $input['id_nama_material'])
			->where('id_merk_material', $input['id_merk_material'])
			->where('jenis', 'masuk')
			->where('id_uom', $input['id_uom'])
			->order_by('log_id', "ASC")
			->get('log_barang')->result_array();


		$log_edit = array_filter($log, function($var) use( $id){
			return($var['log_id'] == $id);
		});

		$log_edit_index = array_keys($log_edit)[0];
		$last_index = array_key_last($log);
		// var_dump($last_index);
		// var_dump($log_edit_index);
		// var_dump($log);die;
		$input['sebelumnya'] = $log_edit[$log_edit_index]['sebelumnya'];
		$input['total'] = $log[$log_edit_index]['sebelumnya'] + $input['jumlah'];
		$input['log_id'] = $log_edit[$log_edit_index]['log_id'];
		$input['jenis'] = 'masuk';
		$newItem = array($input);
		if($log_edit_index != $last_index){
			for ($i= $log_edit_index + 1; $i <= $last_index ; $i++) { 
				$temp = $log[$i];
				$prev = $i - ($log_edit_index + 1);
				$temp['sebelumnya'] = $newItem[$prev]['total'];
				$temp['total'] = $temp['sebelumnya'] + $log[$i]['jumlah'];
				$newItem[] = $temp;
			}
		}
		try {
			$this->db->where('id_nama_material', $input['id_nama_material'])
			->where('id_merk_material', $input['id_merk_material'])
			->where('id_uom', $input['id_uom'])
			->update('inventory_bangunan', ['total' => $newItem[count($newItem) - 1]['total']]);

			$this->db->update_batch('log_barang', $newItem, 'log_id');
		} catch (\Throwable $th) {
			response("Gagal, Terjadi Kesalahan", 500);
		}

		response("Berhasil memperbarui");
	}

}
