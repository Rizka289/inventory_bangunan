<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config['component']['dore']['sidebar'] = array(
    'admin' => array(
        'menus' => array(
            array('text' => 'Dashboard', 'icon' => 'iconsmind-Home', 'link' => base_url('dashboard')),
            array('text' => 'Catat Transaksi', 'link' =>  '#transaksi', 'icon' => 'iconsmind-File-Edit'),
            array('text' => 'Laporan', 'link' => '#laporan', 'icon' => 'iconsmind-Bar-Chart'),
        ),
        'subMenus' => array(
            array(
                'induk' => 'transaksi',
                'menus' => array(
                    array('text' => 'Masuk', 'link' => base_url('transaksi/masuk')),
                    array('text' => 'Keluar', 'link' => base_url('transaksi/keluar')),
                )
            ),
            array(
                'induk' => 'laporan',
                'menus' => array(
                    array('text' => 'Data transaksi', 'link' => base_url('laporan')),
                    array('text' => 'Pembayaran SPP', 'link' => base_url('laporan/spp')),
                    array('text' => 'Grafik', 'link' => base_url('laporan/grafik')),
                )
            )

        )
    ),
    'kepala sekolah' => array(
        'menus' => array(
            array('text' => 'Dashboard', 'icon' => 'iconsmind-Home', 'link' => base_url('dashboard')),
            array('text' => 'Laporan', 'link' =>  base_url('laporan'), 'icon' => 'iconsmind-Bar-Chart'),
            array('text' => 'User Management', 'link' =>  base_url('user/manage'), 'icon' => 'simple-icon-user'),
            array('text' => 'Siswa', 'link' =>  base_url('siswa'), 'icon' => 'iconsmind-Students'),
        )
        // 'subMenus' => array(
        //     array(
        //         'induk' => 'transaksi',
        //         'menus' => array(
        //             array('text' => 'Masuk', 'active' => true, 'link' => base_url('transaksi/masuk')),
        //             array('text' => 'Keluar', 'link' => base_url('transaksi/keluar')),
        //         )
        //     )
        // )
    )
);
