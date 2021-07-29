<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stok extends CI_Controller
{
    private $params = array(
        "pageName" => "Stok",
        'navbar' => 'components/navbar/navbar_dore',
        'sidebar' => 'components/sidebar/sidebar_dore',
        'loadingAnim' => true,
		'adaThemeSelector' => true,
        'navbarConf' => array('adaSidebar' => true)
    );
    public function __construct()
    {
        parent::__construct();
        if (!is_login())
            response("Tidak memiliki akses", 403);
    }

    public function index()
    {
        $data = $this->params + [
            'resource' => array('main', 'dore', 'icons', 'form', 'datatables'),
            'subPageName' => 'List',
            'data_content' => array(
                'dtid' => 'dt-stok',
                'dtTitle' => 'Daftar Barang',
                'hide' => array('Id Induk', 'Total'),
                'head' => array('No', 'Id', 'Material', 'Merk', 'Satuan', 'Supplier','Harga', 'Stok', 'Id Induk', 'Total')
            ),
            'content' => array('components/compui/datatables.responsive'),
            'sidebarConf' => config_sidebar(sessiondata('login', 'user_role'), 2)
        ];

        $this->add_cachedJavascript('utils/datatables.renderer', 'file', 'body:end', [
            'tableid' => 'dt-stok',
            'url_tambah_data' => 'stok/add',
            'url_sumber_data' => 'stok/list',
            'url_update_data' => 'stok/update',
            'url_delete_data' => 'stok/delete',
            'form' => 'forms/barang_masuk',
            'adaCheckbox' => "true",
            'row_scirpt' => "(d, i) => {
				return '<tr>' +
						'<td>' + parseInt(i+1) + '</td>'+
						'<td>' + d.id_inventory_stok + '</td>'+
						'<td>' + d.nama_material + '</td>'+
						'<td>' + d.merk_material + '</td>'+
						'<td>' + d.uom + '</td>'+
						'<td>' + d.nama + '</td>'+
						'<td>' + 'Rp.' + d.harga.rupiahFormat() + '</td>'+
						'<td>' + d.stok + '</td>'+
						'<td class=d-none>' + d.id_inventory_bangunan + '</td>'+
						'<td class=d-none>' + d.total + '</td>'+
					'</tr>';
			} ",
            'extra_button' => sessiondata('login', 'user_role') == 'admin' ? 
                array(
                    array(
                        "text" => "Ambil dari Gudang",
                        'funct' => load_script('utils/kestok', true, ['custom' => true, 'total' => 9, 'id' => 8])
                    ),
                    array(
                        "text" => "Kembalikan ke Gudang",
                        'funct' => load_script('utils/stok.kembali', true)
                    ),
                    array(
                        "text" => "Jual",
                        'funct' => load_script('utils/jual.stok', true)
                    )
                ):
                array(
                    array(
                        "text" => "Jual",
                        'funct' => load_script('utils/jual.stok', true)
                    )
                ),
            'index_id' => 1,
            'ada_edit' => false,
            'ada_tambah' => false,
            'ada_hapus' => sessiondata('login', 'user_role') == 'admin'
        ]);
        $this->addViews('templates/backoffice_dore', $data);
        $this->render();
    }

    function list(){
        $res = $this->db->select("stok.id_inventory_stok, stok.stok, material.nama_material, merk.merk_material")
            ->select('uom.uom, supplier.nama')
            ->select('bangunan.harga, bangunan.total, bangunan.id_inventory_bangunan')
            ->from('inventory_stok stok')
            ->join('inventory_bangunan bangunan', 'bangunan.id_inventory_bangunan = stok.id_inventory_bangunan')
            ->join('nama_material material', 'material.id_nama_material = bangunan.id_nama_material')
            ->join('merk_material merk', 'merk.id_merk_material = bangunan.id_merk_material')
            ->join('uom', 'bangunan.id_uom = uom.id_uom')
            ->join("supplier", 'supplier.id_supplier = supplier.id_supplier')
            ->order_by('id_inventory_stok')
            ->get()->result();

        response(['data' => $res]);
    }


    function delete(){
        $ids = $_POST['ids'];
        $updateBangunan = [];
        $insertLog = [];
        $stoks =  $this->db->select('*')->from('inventory_stok')->where_in('id_inventory_stok', $ids)->get()->result_array();
        $gudang = $this->db->get('inventory_bangunan')->result_array();
        foreach ($stoks as $stok){
            $barang = array_filter($gudang, function($var) use ($stok) {
                return $var['id_inventory_bangunan'] == $stok['id_inventory_bangunan'];
            });

            $barang = array_values($barang);
            $updateBangunan[] = array(
                'id_inventory_bangunan' => $barang[0]['id_inventory_bangunan'],
                'total' => $barang[0]['total'] + $stok['stok'],
            );
            $insertLog[] = array(
                'id_nama_material' => $barang[0]['id_nama_material'],
                'id_merk_material' => $barang[0]['id_merk_material'],
                'id_supplier' => $barang[0]['id_supplier'],
                'id_uom' => $barang[0]['id_uom'],
                'harga' => $barang[0]['harga'],
                'jumlah' =>$stok['stok'],
                'sebelumnya' => $barang[0]['total'],
                'total' => $barang[0]['total'] +$stok['stok'],
                'keterangan' => 'Mengembalikan Barang Ke Gudang dengan menghapus', 
                'tanggal' => waktu(null, MYSQL_DATE_FORMAT),
                'jenis' => 'kembali'
            );
        }

        try {
            if(!empty($updateBangunan))
                $this->db->update_batch('inventory_bangunan', $updateBangunan, 'id_inventory_bangunan');
           
            $this->db->where_in('id_inventory_stok', $ids)->delete('inventory_stok');
            $this->db->insert_batch('log_barang', $insertLog);

        } catch (\Throwable $th) {
            response("Gagal, Terjadi Kesalahan", 500);
        }
        
        response("Berhasil Mengembalikan Barang");
    }

    function kegudang(){
        if(!is_login('admin'))
            response("Tidak memiliki akses", 403);
        

        if(!httpmethod())
            response("Cara akses ilegal!", 403);

        $ids_bangunan = $_POST['ids_bangunan'];
        $ids_stok = $_POST['ids_stok'];
        $jumlah = $_POST['jumlah'];
        $keterangan = $_POST['keterangan'];


        $updateBangunan = [];
        $updateStok = [];
        $deleteStok = [];
        $insertLog = [];
        $gudang = $this->db->select('*')->from('inventory_bangunan')->where_in('id_inventory_bangunan', array_values($ids_bangunan))->get()->result_array();
        $stoks = $this->db->select('id_inventory_stok, stok')->from('inventory_stok')->where_in('id_inventory_stok', $ids_stok)->get()->result_array();

        foreach($ids_stok as $id){
            $barang = array_filter($gudang, function($var) use ($ids_bangunan, $id) {
                return $var['id_inventory_bangunan'] == $ids_bangunan[$id];
            });
            
            $stok = array_filter($stoks, function($var) use ( $id) {
                return $var['id_inventory_stok'] == $id;
            });

            $barang = array_values($barang);
            $stok = array_values($stok);

            $updateBangunan[] = array(
                'id_inventory_bangunan' => $ids_bangunan[$id],
                'total' => $barang[0]['total'] + $jumlah[$id]
            );

            $insertLog[] = array(
                'id_nama_material' => $barang[0]['id_nama_material'],
                'id_merk_material' => $barang[0]['id_merk_material'],
                'id_supplier' => $barang[0]['id_supplier'],
                'id_uom' => $barang[0]['id_uom'],
                'harga' => $barang[0]['harga'],
                'jumlah' => $jumlah[$id],
                'sebelumnya' => $barang[0]['total'],
                'total' => $barang[0]['total'] + $jumlah[$id],
                'keterangan' => $keterangan[$id], 
                'tanggal' => waktu(null, MYSQL_DATE_FORMAT),
                'jenis' => 'kembali'
            );

            if($stok[0]['stok'] - $jumlah[$id] == 0){
                $deleteStok[] = $id;
            }else{
                 $updateStok[] = array(
                    'id_inventory_stok' => $id,
                    'stok' => $stok[0]['stok'] - $jumlah[$id]
                );
            }
           
        }

        
        try {
            if(!empty($updateBangunan))
                $this->db->update_batch('inventory_bangunan', $updateBangunan, 'id_inventory_bangunan');
            
            if(!empty($updateStok))
                $this->db->update_batch('inventory_stok', $updateStok, 'id_inventory_stok');

            if(!empty($deleteStok))
                $this->db->where_in('id_inventory_stok', $deleteStok)->delete('inventory_stok');
            
            $this->db->insert_batch('log_barang', $insertLog);

        } catch (\Throwable $th) {
            response("Gagal, Terjadi Kesalahan", 500);
        }
        
        response("Berhasil Mengembalikan Barang");
    }

    function jual(){
        $ids = $_POST['ids'];
        $jumlah = $_POST['jumlah'];
        $keterangan = $_POST['keterangan'];
        $updateStok = [];
        $insertLog = [];
        $stoks =  $this->db->select('*')->from('inventory_stok')->where_in('id_inventory_stok', $ids)->get()->result_array();
        $gudang = $this->db->get('inventory_bangunan')->result_array();
        
        foreach ($stoks as $stok){
            $barang = array_filter($gudang, function($var) use ($stok) {
                return $var['id_inventory_bangunan'] == $stok['id_inventory_bangunan'];
            });

            $barang = array_values($barang);

            $updateStok[] = array(
                'id_inventory_stok' => $stok['id_inventory_stok'],
                'stok' => $stok['stok'] - $jumlah[$stok['id_inventory_stok']],
            );
            $insertLog[] = array(
                'id_nama_material' => $barang[0]['id_nama_material'],
                'id_merk_material' => $barang[0]['id_merk_material'],
                'id_supplier' => $barang[0]['id_supplier'],
                'id_uom' => $barang[0]['id_uom'],
                'harga' => $barang[0]['harga'],
                'jumlah' => $jumlah[$stok['id_inventory_stok']],
                'sebelumnya' => $stok['stok'],
                'total' => $stok['stok'] - $jumlah[$stok['id_inventory_stok']],
                'keterangan' => $keterangan[$stok['id_inventory_stok']], 
                'tanggal' => waktu(null, MYSQL_DATE_FORMAT),
                'jenis' => 'keluar'
            );
        }

        try {
            if(!empty($updateStok))
                $this->db->update_batch('inventory_stok', $updateStok, 'id_inventory_stok');

            $this->db->insert_batch('log_barang', $insertLog);

        } catch (\Throwable $th) {
            response("Gagal, Terjadi Kesalahan", 500);
        }
        
        response("Berhasil Mengembalikan Barang");
    }
}
