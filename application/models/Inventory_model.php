<?php
defined('BASEPATH') or exit('No direct script accesss allowed');

class Inventory_model extends CI_Model{

    function add_inventory($post){
        try {
            $input = fieldmapping($post, 'inventory', ['tanggal' => waktu(null, MYSQL_DATE_FORMAT)]);
			$barang = $this->db->where('id_nama_material', $input['id_nama_material'])
				->where('id_merk_material', $input['id_merk_material'])
				->where('id_uom', $input['id_uom'])->get('inventory_bangunan')->row();

			if(!empty($barang)){
				$input['sebelumnya'] = $barang->total;
				$barang->total = $input['jumlah'] + $barang->total;
				$input['total'] = $barang->total;
				$id = $barang->id_inventory_bangunan;
				unset($barang->id_inventory_bangunan);
				$average = ((($input['harga'] - $barang->harga)/ ($barang->total + $input["jumlah"])) * $input['jumlah']) + $barang->harga;
				$barang->harga = $average;
				$this->db->where('id_inventory_bangunan', $id)->update('inventory_bangunan', $barang[0]);
			}
			else{
				$input['total'] = $input['jumlah'];
				$inventory = $input;
				$input['sebelumnya'] = 0;
				unset($inventory['keterangan'], $inventory['jumlah']);
				$this->db->insert('inventory_bangunan', $inventory);
			}
			
			$input['jenis'] = 'masuk';
			$this->db->insert('log_barang', $input);

        } catch (\Throwable $th) {
            response("Gagal, Terjadi kesalahan", 500);
        }
    
        response("Berhasil menambah inventory");
    }


    function delete_inventory($post){
        $ids = $post['ids'];
		
        try {
			$this->db->where_in('id_inventory_bangunan', $ids)->delete('inventory_bangunan');
        } catch (\Throwable $th) {
            response("Gagal, Terjadi kesalahan", 500);
        }
        response("Berhasil Menghapus data dari inventory");
    }

    function update_barang_masuk($post){
        $input = fieldmapping($post, 'inventory');
		$id = $post['id'];
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

    function delete_barang_masuk($post){
        $ids = $post['ids'];
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

    function pindahkan_ke_stok($post){
        $ids = $post['ids'];
		$jumlah = $post['jumlah'];
        $keterangan = $_POST['keterangan'];

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
					'keterangan' => $keterangan[$id],
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