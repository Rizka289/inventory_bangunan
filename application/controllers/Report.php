<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report extends CI_Controller
{

	private $params = array(
		"pageName" => "Laporan",
		'navbar' => 'components/navbar/navbar_dore',
		'sidebar' => 'components/sidebar/sidebar_dore',
		'loadingAnim' => true,
		'adaThemeSelector' => true,
		'navbarConf' => array('adaSidebar' => true)
	);
    private $max_tanggal;
    private $min_tanggal;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Satuan_model');
		if(!is_login('admin'))
			response("Tidak memiliki akses", 403);

	}

    function masuk(){
        $data = $this->params + [
            'resource' => array('main', 'dore', 'icons', 'form', 'datatables'),
            'subPageName' => 'Barang Masuk',
            'data_content' => array(
                'dtid' => 'dt-report-masuk',
                'dtTitle' => 'Daftar Barang Masuk',
                'toolbar' => 'components/compui/filter.tanggal',
                'head' => array('No', 'Tanggal', 'Id Barang', 'Material', 'Merk', 'Satuan', 'Supplier','Harga', 'Jumlah', 'Sebelumnya', 'Total Stok')
            ),
            'content' => array('components/compui/datatables.responsive'),
            'sidebarConf' => config_sidebar(sessiondata('login', 'user_role'), 3, 0)
        ];

        $tanggal = $this->db->select("MAX(tanggal) max, MIN(tanggal) min")
            ->where('jenis', 'masuk')
            ->from('log_barang')->get()->row_array();
        $this->max_tanggal = $tanggal['max'];   
        $this->min_tanggal = $tanggal['min'];   

        $this->add_cachedJavascript('utils/datatables.renderer', 'file', 'body:end', [
            'tableid' => 'dt-report-masuk',
            'url_tambah_data' => 'stok/add',
            'url_sumber_data' => 'report/list/masuk',
            'url_update_data' => 'stok/update',
            'url_delete_data' => 'stok/delete',
            'form' => 'forms/barang_masuk',
            'adaCheckbox' => "true",
            'row_scirpt' => "(d, i) => {
				return '<tr>' +
						'<td>' + parseInt(i+1) + '</td>'+
						'<td>' + d.tanggal + '</td>'+
						'<td>' + d.id_bangunan + '</td>'+
						'<td>' + d.nama_material + '</td>'+
						'<td>' + d.merk_material + '</td>'+
						'<td>' + d.uom + '</td>'+
						'<td>' + d.nama + '</td>'+
						'<td>' + 'Rp.' + d.harga.rupiahFormat() + '</td>'+
						'<td>' + d.jumlah + '</td>'+
						'<td>' + d.sebelumnya + '</td>'+
						'<td>' + d.total + '</td>'+

					'</tr>';
			} ",
            'extra_button' => array(
                array('nonCustom' => true, 'button' => array(
                        'extend' => 'pdfHtml5',
                        'text' => 'Simpan ke PDF',
                        'title' => 'Laporan Barang Masuk',
                        'pageSize' => 'A4',
                        
                )),
            ),
            'index_id' => 1,
            'ada_edit' => false,
            'ada_tambah' => false,
            'ada_hapus' => false,
            'ada_filter_tanggal' => true,
            'maxTanggal' => $this->max_tanggal,
            'minTanggal' => $this->min_tanggal,
        ]);
        $this->addViews('templates/backoffice_dore', $data);
        $this->render();

    }
    function keluar(){
        $data = $this->params + [
            'resource' => array('main', 'dore', 'icons', 'form', 'datatables'),
            'subPageName' => 'Barang Keluar',
            'data_content' => array(
                'dtid' => 'dt-report-keluar',
                'dtTitle' => 'Daftar Barang Keluar',
                'toolbar' => 'components/compui/filter.tanggal',
                'head' => array('No', 'Tangal', 'Id Barang', 'Material', 'Merk', 'Satuan', 'Supplier','Harga', 'Jumlah', 'Sebelumnya', 'Total Stok')
            ),
            'content' => array('components/compui/datatables.responsive'),
            'sidebarConf' => config_sidebar(sessiondata('login', 'user_role'), 3, 1)
        ];

        $tanggal = $this->db->select("MAX(tanggal) max, MIN(tanggal) min")
            ->where('jenis', 'keluar')
            ->from('log_barang')->get()->row_array();
        $this->max_tanggal = $tanggal['max'];   
        $this->min_tanggal = $tanggal['min'];  
        
        $this->add_cachedJavascript('utils/datatables.renderer', 'file', 'body:end', [
            'tableid' => 'dt-report-keluar',
            'url_tambah_data' => 'stok/add',
            'url_sumber_data' => 'report/list/keluar',
            'url_update_data' => 'stok/update',
            'url_delete_data' => 'stok/delete',
            'form' => 'forms/barang_keluar',
            'adaCheckbox' => "true",
            'row_scirpt' => "(d, i) => {
				return '<tr>' +
						'<td>' + parseInt(i+1) + '</td>'+
						'<td>' + d.tanggal+ '</td>'+
						'<td>' + d.id_bangunan + '</td>'+
						'<td>' + d.nama_material + '</td>'+
						'<td>' + d.merk_material + '</td>'+
						'<td>' + d.uom + '</td>'+
						'<td>' + d.nama + '</td>'+
						'<td>' + 'Rp.' + d.harga.rupiahFormat() + '</td>'+
						'<td>' + d.jumlah + '</td>'+
						'<td>' + d.sebelumnya + '</td>'+
						'<td>' + d.total + '</td>'+

					'</tr>';
			} ",
            'extra_button' => array(
                array('nonCustom' => true, 'button' => array(
                        'extend' => 'pdfHtml5',
                        'text' => 'Simpan ke PDF',
                        'title' => 'Laporan Barang Keluar (dari Stok)',
                        'pageSize' => 'A4',
                        
                )),
            ),
            'index_id' => 1,
            'ada_edit' => false,
            'ada_tambah' => false,
            'ada_hapus' => false,
            'ada_filter_tanggal' => true,
            'maxTanggal' => $this->max_tanggal,
            'minTanggal' => $this->min_tanggal,
        ]);
        $this->addViews('templates/backoffice_dore', $data);
        $this->render();
    }

    function pengambilan(){
        $data = $this->params + [
            'resource' => array('main', 'dore', 'icons', 'form', 'datatables'),
            'subPageName' => 'Barang Dipindah dari Gudang',
            'data_content' => array(
                'dtid' => 'dt-report-pindah',
                'dtTitle' => 'Daftar Barang yang Diambil dari Gudang',
                'toolbar' => 'components/compui/filter.tanggal',
                'head' => array('No', 'Tangal', 'Id Barang', 'Material', 'Merk', 'Satuan', 'Supplier','Harga', 'Jumlah', 'Sebelumnya', 'Total Stok')
            ),
            'content' => array('components/compui/datatables.responsive'),
            'sidebarConf' => config_sidebar(sessiondata('login', 'user_role'), 3, 2)
        ];

        $tanggal = $this->db->select("MAX(tanggal) max, MIN(tanggal) min")
            ->where('jenis', 'keluar')
            ->from('log_barang')->get()->row_array();
        $this->max_tanggal = $tanggal['max'];   
        $this->min_tanggal = $tanggal['min'];  
        
        $this->add_cachedJavascript('utils/datatables.renderer', 'file', 'body:end', [
            'tableid' => 'dt-report-pindah',
            'url_tambah_data' => 'stok/add',
            'url_sumber_data' => 'report/list/pindah',
            'url_update_data' => 'stok/update',
            'url_delete_data' => 'stok/delete',
            'form' => 'forms/barang_keluar',
            'adaCheckbox' => "true",
            'row_scirpt' => "(d, i) => {
				return '<tr>' +
						'<td>' + parseInt(i+1) + '</td>'+
						'<td>' + d.tanggal+ '</td>'+
						'<td>' + d.id_bangunan + '</td>'+
						'<td>' + d.nama_material + '</td>'+
						'<td>' + d.merk_material + '</td>'+
						'<td>' + d.uom + '</td>'+
						'<td>' + d.nama + '</td>'+
						'<td>' + 'Rp.' + d.harga.rupiahFormat() + '</td>'+
						'<td>' + d.jumlah + '</td>'+
						'<td>' + d.sebelumnya + '</td>'+
						'<td>' + d.total + '</td>'+

					'</tr>';
			} ",
            'extra_button' => array(
                array('nonCustom' => true, 'button' => array(
                        'extend' => 'pdfHtml5',
                        'text' => 'Simpan ke PDF',
                        'title' => 'Laporan Barang yang Diambil dari Gudang',
                        'pageSize' => 'A4',
                        
                )),
            ),
            'index_id' => 1,
            'ada_edit' => false,
            'ada_tambah' => false,
            'ada_hapus' => false,
            'ada_filter_tanggal' => true,
            'maxTanggal' => $this->max_tanggal,
            'minTanggal' => $this->min_tanggal,
        ]);
        $this->addViews('templates/backoffice_dore', $data);
        $this->render();
    }

    function kembali(){
        $data = $this->params + [
            'resource' => array('main', 'dore', 'icons', 'form', 'datatables'),
            'subPageName' => 'Barang Dikembalikan ke Gudang',
            'data_content' => array(
                'dtid' => 'dt-report-kembali',
                'dtTitle' => 'Daftar Barang yang Dikembalikan ke Gudang',
                'toolbar' => 'components/compui/filter.tanggal',
                'head' => array('No', 'Tangal', 'Id Barang', 'Material', 'Merk', 'Satuan', 'Supplier','Harga', 'Jumlah', 'Sebelumnya', 'Total Stok')
            ),
            'content' => array('components/compui/datatables.responsive'),
            'sidebarConf' => config_sidebar(sessiondata('login', 'user_role'), 3, 2)
        ];

        $tanggal = $this->db->select("MAX(tanggal) max, MIN(tanggal) min")
            ->where('jenis', 'keluar')
            ->from('log_barang')->get()->row_array();
        $this->max_tanggal = $tanggal['max'];   
        $this->min_tanggal = $tanggal['min'];  
        
        $this->add_cachedJavascript('utils/datatables.renderer', 'file', 'body:end', [
            'tableid' => 'dt-report-kembali',
            'url_tambah_data' => 'stok/add',
            'url_sumber_data' => 'report/list/kembali',
            'url_update_data' => 'stok/update',
            'url_delete_data' => 'stok/delete',
            'form' => 'forms/barang_keluar',
            'adaCheckbox' => "true",
            'row_scirpt' => "(d, i) => {
				return '<tr>' +
						'<td>' + parseInt(i+1) + '</td>'+
						'<td>' + d.tanggal+ '</td>'+
						'<td>' + d.id_bangunan + '</td>'+
						'<td>' + d.nama_material + '</td>'+
						'<td>' + d.merk_material + '</td>'+
						'<td>' + d.uom + '</td>'+
						'<td>' + d.nama + '</td>'+
						'<td>' + 'Rp.' + d.harga.rupiahFormat() + '</td>'+
						'<td>' + d.jumlah + '</td>'+
						'<td>' + d.sebelumnya + '</td>'+
						'<td>' + d.total + '</td>'+

					'</tr>';
			} ",
            'extra_button' => array(
                array('nonCustom' => true, 'button' => array(
                        'extend' => 'pdfHtml5',
                        'text' => 'Simpan ke PDF',
                        'title' => 'Laporan Barang yang Dikembalikan ke Gudang',
                        'pageSize' => 'A4',
                        
                )),
            ),
            'index_id' => 1,
            'ada_edit' => false,
            'ada_tambah' => false,
            'ada_hapus' => false,
            'ada_filter_tanggal' => true,
            'maxTanggal' => $this->max_tanggal,
            'minTanggal' => $this->min_tanggal,
        ]);
        $this->addViews('templates/backoffice_dore', $data);
        $this->render();
    }

    function list($tipe){
        $get = $_GET;
        $q = $this->db->select('nama_material.nama_material, merk_material.merk_material, supplier.nama, uom.uom')
            ->select('log_barang.jumlah, log_barang.harga,  log_barang.tanggal, log_barang.total, log_barang.sebelumnya, log_barang.keterangan')
            ->select('bangunan.id_inventory_bangunan id_bangunan')
            ->from('log_barang')
            ->join('inventory_bangunan bangunan', '(bangunan.id_nama_material = log_barang.id_nama_material AND bangunan.id_merk_material = log_barang.id_merk_material  AND bangunan.id_uom = log_barang.id_uom)')
            ->join('nama_material', 'nama_material.id_nama_material = log_barang.id_nama_material')
            ->join('merk_material', 'merk_material.id_merk_material = log_barang.id_merk_material')
            ->join('supplier', 'supplier.id_supplier = log_barang.id_supplier')
            ->join('uom', 'uom.id_uom = log_barang.id_uom')
            ->where('jenis', $tipe);

        if(isset($get['start']) && !empty($get['start']) && isset($get['end']) && !empty($get['end'])){
            $q->where("log_barang.tanggal BETWEEN '" . $get['start'] . "' AND '" . $get['end'] . "'", '', FALSE);
        }

        response(['data' => $q->get()->result()]);
    }
}