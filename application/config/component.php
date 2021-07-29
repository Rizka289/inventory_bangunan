<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config['component']['dore']['sidebar'] = array(
    'admin' => array(
        'menus' => array(
            array('text' => 'Dashboard', 'icon' => 'iconsmind-Home', 'link' => base_url('dashboard')),
            array('text' => 'Gudang', 'link' =>  '#inventory', 'icon' => 'iconsmind-Warehouse'),
            array('text' => 'Stok Barang', 'link' => base_url('stok'), 'icon' => 'iconsmind-Shop'),
            array('text' => 'Laporan', 'link' =>  '#laporan', 'icon' => 'iconsmind-File-Share'),
            array('text' => 'Utilitas', 'link' => '#util', 'icon' => 'simple-icon-equalizer'),
        ),
        'subMenus' => array(
            array(
                'induk' => 'inventory',
                'menus' => array(
                    array('text' => 'Daftar Barang', 'link' => base_url('inventory'), 'icon' => 'iconsmind-Box-Full'),
                    array('text' => 'Catat Transaksi Masuk', 'link' => base_url('inventory/masuk'), 'icon' => 'iconsmind-Inbox-Into'),
                )
            ),
            array(
                'induk' => 'util',
                'menus' => array(
                    array('text' => 'Kelola Material', 'link' => base_url('material'), 'icon' => 'iconsmind-File-Edit'),
                    array('text' => 'Kelola Satuan', 'link' => base_url('satuan'), 'icon' => 'iconsmind-Tag'),
                    array('text' => 'Kelola Merk', 'link' => base_url('merk'), 'icon' => 'iconsmind-Tag-4'),
                    array('text' => 'Kelola Supplier', 'link' => base_url('supplier'), 'icon' => 'iconsmind-User'),
                    array('text' => 'User Management', 'link' => base_url('users'), 'icon' => 'simple-icon-people'),
                    array('text' => 'Akun Saya', 'link' => base_url('users/akun'), 'icon' => 'simple-icon-user'),

                )
            ),
            array(
                'induk' => 'laporan',
                'menus' => array(
                    array('text' => 'Barang Masuk', 'link' => base_url('report/masuk'), 'icon' => 'iconsmind-Inbox-Into'),
                    array('text' => 'Barang Keluar', 'link' => base_url('report/keluar'), 'icon' => 'iconsmind-Inbox-Out'),
                    array('text' => 'Pengambilan Barang', 'link' => base_url('report/pengambilan')),
                    array('text' => 'Pengembalian Barang', 'link' => base_url('report/kembali')),
                )
            ),

        )
    ),
    'staff' => array(
        'menus' => array(
            array('text' => 'Stok Barang', 'link' => base_url('stok'), 'icon' => 'iconsmind-Shop'),
            array('text' => 'Laporan', 'link' =>  '#laporan', 'icon' => 'iconsmind-File-Share'),
            array('text' => 'Utilitas', 'link' => '#util', 'icon' => 'simple-icon-equalizer'),
        ),
        array(
            'induk' => 'laporan',
            'menus' => array(
                array('text' => 'Barang Masuk', 'link' => base_url('report/masuk'), 'icon' => 'iconsmind-Inbox-Into'),
                array('text' => 'Barang Keluar', 'link' => base_url('report/keluar'), 'icon' => 'iconsmind-Inbox-Out'),

            )
        ),
        array(
            'induk' => 'util',
            'menus' => array(
                array('text' => 'Akun Saya', 'link' => base_url('users/akun'), 'icon' => 'simple-icon-user'),
            )
        ),

    )

);
