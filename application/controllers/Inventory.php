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
                    'funct' => "(e, dt, node, config) => {
						$(node).prop('disabled', true);
                            var data = instance.dataTables[config.data.tableid].rows({ selected: true }).data().toArray()
                            if(data.length == 0){
                                alert('Pilih Data yang ingin dipindahkan');
                                $(node).prop('disabled', false);
                                return;
                            }
                            var res = confirm('Yakin Ingin Memindahkan Data .?');
                            if(!res){
                                $(node).prop('disabled', false);
                                return;
                            }
                            // $('#pros-loading').show();
							$(node).prop('disabled', false);

							var inputs = [];
                            var barang = data.map(d => [d[index_id], d[2] + ' ' + d[3] + ', Satuan ' + d[4], d[7]]);
							barang.forEach(b => {
								inputs.push({
									label: 'Jumlah untuk ' + b[1], type: 'text', id:'jumlah-' + b[0],  attr: 'min=1 max='+ b[2]+' required data-rule-number=true autocomplete=off', name: 'jumlah['+b[0]+']' 
								});
								inputs.push({
									type: 'hidden', value: b[0], name: 'ids[]' 
								});

							})

							var modalConf = {
								pos: 'def', 
								size: 'modal-md', 
								submit: 'inventory/kestok',
							};

							tambahHandler(config.data, false, null, inputs, modalConf);
							
                    }"
                )
			),
			'index_id' => 1,
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
				$inventory = $input;
				$input['sebelumnya'] = 0;
				$this->db->insert('inventory_bangunan', $inventory);
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
		$ids = $_POST['ids'];
		// $dihapus = $this->db->where_in('id_inventory_bangunan', $ids)
		// 	->select('id_nama_material, id_merk_material, id_uom')
		// 	->from('inventory_bangunan')
		// 	->group_by('id_nama_material, id_merk_material, id_uom')
		// 	->get()->result_array();

        try {
			$this->db->where_in('id_inventory_bangunan', $ids)->delete('inventory_bangunan');

			// foreach($dihapus as $v){
			// 	$this->db->where('id_nama_material', $v['id_nama_material'])
			// 	->where('id_merk_material', $v['id_merk_material'])
			// 	->where('id_uom', $v['id_uom'])
			// 	->delete('log_barang');
			// }
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
		}else{
			$newItem[0]['sebelumnya'] = $log[count($log) - 2]['total'];
			$newItem[0]['total'] = $newItem[0]['sebelumnya'] + $newItem[0]['jumlah'];
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

	function delete_masuk(){
		if(!httpmethod())
			response("Cara Akses Ilegal", 403);

		$ids = $_POST['ids'];
		$log_dihapus = $this->db->where_in('log_id', $ids)
			->select('id_nama_material, id_merk_material, id_uom')
			->from('log_barang')
			->group_by('id_nama_material, id_merk_material, id_uom')
			->get()->result();

		$this->db->where_in('log_id', $ids)->delete('log_barang');

		$barangDihapus = [];
		$updateLog = [];
		$updateBarang = [];

		// Update Log
		foreach($log_dihapus as $dihapus){
			$temp = $this->db->where('id_nama_material', $dihapus->id_nama_material)
					->where('id_merk_material', $dihapus->id_merk_material)
					->where('id_uom', $dihapus->id_uom)
					->order_by('log_id', 'ASC')
					->get('log_barang')->result_array();
			$total = 0;
			if(empty($temp))
				$barangDihapus[] = array('material' => $dihapus->id_nama_material, 'merk' => $dihapus->id_merk_material, 'uom' => $dihapus->id_uom);
			else{
				foreach($temp as $k => $v){
					$update = $v;
					if($k == 0){
						$update['sebelumnya'] = 0;
						$update['total'] = $v['jumlah'];
					}
					else{
						$update['sebelumnya'] = $updateLog[$k - 1]['total'];
						$update['total'] = $updateLog[$k - 1]['total'] + $v['jumlah'];
					}
					$total += $update['total'];
					$updateLog[] = $update;
				}
				$updateBarang[] = ['total' => $total, 'material' => $dihapus->id_nama_material, 'merk' => $dihapus->id_merk_material, 'uom' => $dihapus->id_uom];
			}

		}

		try {
			if(!empty($barangDihapus)){
				foreach($barangDihapus as $hapus){
					$this->db->where('id_nama_material', $hapus['material'])
						->where('id_merk_material', $hapus['merk'])
						->where('id_uom', $hapus['uom'])
						->delete('inventory_bangunan');
				}
			}
			if(!empty($updateBarang)){
				foreach($updateBarang as $update){
					$this->db->where('id_nama_material', $update['material'])
						->where('id_merk_material', $update['merk'])
						->where('id_uom', $update['uom'])
						->update('inventory_barang', ['total' => $update['total']]);
				}
			}
	
			if(!empty($updateLog)){
				$this->db->update_batch('log_barang', $updateLog, 'log_id');
			}
		} catch (\Throwable $th) {
			response("Gagal, Terjadi kesalahan", 500);
		}

		response("Berhasil");

	}

	function kestok(){
		if(!httpmethod())
			response("Cara Akses Ilegal", 403);

		$ids = $_POST['ids'];
		$jumlah = $_POST['jumlah'];

		$log = [];
		$update = [];
		try {
			foreach($ids as $id){
				$jualan = $this->db->select('inventory_stok.stok, inventory_stok.id_inventory_stok, inventory_bangunan.*')
					->where('inventory_bangunan.id_inventory_bangunan', $id)
					->from('inventory_stok')
					->join('inventory_bangunan', 'inventory_stok.id_inventory_bangunan = inventory_bangunan.id_inventory_bangunan','right')
					->get()
					->row_array();
	
				if(empty($jualan['stok'])){
					$this->db->insert('inventory_stok', ['id_inventory_bangunan' => $id, 'stok' => $jumlah[$id]]);
				}else{
					$this->db->where('id_inventory_stok', $jualan['id_inventory_stok'])
						->update('inventory_stok', ['stok' => ($jualan['stok'] + $jumlah[$id])]);
				}
	
				$log[] = array(
					'id_nama_material' => $jualan['id_nama_material'],
					'id_merk_material' => $jualan['id_merk_material'],
					'id_uom' => $jualan['id_uom'],
					'id_supplier' => $jualan['id_supplier'],
					'jumlah' => $jumlah[$id],
					'sebelumnya' => $jualan['total'],
					'harga' => $jualan['harga'],
					'total' => $jualan['total'] - $jumlah[$id],
					'keterangan' => $jualan['keterangan'],
					'jenis' => 'pindah',
					'tanggal'=> waktu(null, MYSQL_DATE_FORMAT)
				);
				$update[] = array(
					'id_inventory_bangunan' => $id,
					'total' => $jualan['total'] - $jumlah[$id]
				);
	
			}
	
			$this->db->update_batch('inventory_bangunan', $update, 'id_inventory_bangunan');
			$this->db->insert_batch('log_barang', $log);
		} catch (\Throwable $th) {
			response("Gagal, Terjadi kesalahan", 500);
		}
		response("Berhasil");
	}

}
