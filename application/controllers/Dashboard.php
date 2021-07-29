<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    private $pageInfo = array('pageName' => 'Dashboard');
    function __construct()
    {
        parent::__construct();
        if (!is_login())
            redirect(base_url());
    }
    function index()
    {   
        $query = $this->db->select("if(jenis = 'keluar', 1, 0) keluar, if(jenis = 'masuk', 1, 0) masuk", FALSE)
            ->from('log_barang');

        $subQuery = $query->get_compiled_select();
        $barang = $this->db->query("SELECT SUM(keluar) keluar, SUM(masuk) masuk FROM($subQuery) T")->row();
        $gudang = $this->db->select('COUNT(*) gudang')->get('inventory_bangunan')->row();
        $toko = $this->db->select('COUNT(*) toko')->get('inventory_stok')->row();


        $query = $this->db
            ->select('jenis')
            ->select('if(MONTH(tanggal) = 01, 1, 0) januari')->select('if(MONTH(tanggal) = 02, 1, 0) februari')
            ->select('if(MONTH(tanggal) = 03, 1, 0) maret')->select('if(MONTH(tanggal) = 04, 1, 0) april')
            ->select('if(MONTH(tanggal) = 05, 1, 0) mei')->select('if(MONTH(tanggal) = 06, 1, 0) juni')
            ->select('if(MONTH(tanggal) = 07, 1, 0) juli')->select('if(MONTH(tanggal) = 08, 1, 0) agustus')
            ->select('if(MONTH(tanggal) = 09, 1, 0) september')->select('if(MONTH(tanggal) = 10, 1, 0) oktober')
            ->select('if(MONTH(tanggal) = 11, 1, 0) november')->select('if(MONTH(tanggal) = 12, 1, 0) desember')
            ->from('log_barang');

        $sub = $query->get_compiled_select();
        $masuk = $this->db->query("SELECT SUM(januari) januari, SUM(februari) februari, SUM(maret) maret, SUM(april) april, SUM(mei) mei, 
        SUM(juni) juni, SUM(juli) juli, SUM(agustus) agustus, SUM(september) september, SUM(oktober) oktober, SUM(november) november, SUM(desember) desember
        FROM($sub) T WHERE T.jenis = 'masuk'"
        )->row();

        $keluar = $this->db->query("SELECT SUM(januari) januari, SUM(februari) februari, SUM(maret) maret, SUM(april) april, SUM(mei) mei, 
        SUM(juni) juni, SUM(juli) juli, SUM(agustus) agustus, SUM(september) september, SUM(oktober) oktober, SUM(november) november, SUM(desember) desember
        FROM($sub) T WHERE T.jenis = 'keluar'"
        )->row();

        $data = $this->pageInfo + array(
            'resource' => array('main', 'dore', 'icons'),
            'sidebar' => 'components/sidebar/sidebar_dore',
            'navbar' => 'components/navbar/navbar_dore',
            'adaThemeSelector' => true,
            'sidebarConf' => config_sidebar(sessiondata('login', 'user_role')),
            'navbarConf' => array(
                'adaSidebar' => true
            ),
            'content' => array('pages/dashboard'),
            'data_content' => array(
                'resume' => array(
                    'masuk' => $barang->masuk,
                    'keluar' => $barang->keluar,
                    'gudang' => $gudang->gudang,
                    'toko' => $toko->toko
                ),
                'chart' => array(),
            )
        );
        $this->add_javascript(['pos'=> 'body:end', 'src' => 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.0/chart.min.js', 'type' => 'file']);
        $this->addViews('templates/backoffice_dore', $data);
        $this->add_cachedJavascript('pages/dashboard.chart', 'file', 'body:end', array('masuk' => $masuk, 'keluar' => $keluar));
        $this->render();
    }
}
