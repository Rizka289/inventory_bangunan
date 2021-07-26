<?php
$config['field_mapping']['login'] = array(
    'user' => 'user',
    'pass' => 'password'
);
$config['field_mapping']['user'] = array(
    'username' => 'user_name',
    'email' => 'user_email',
    'hp' => 'user_phone',
    'alamat' => 'user_address',
    'dibuat' => 'created_at',
    'password' => 'user_password',
    'avatar' => 'user_avatar',
    'role' => 'user_role',
    'registrar' => 'registrar'
);

$config['field_mapping']['supplier'] = array(
    'nama' => 'nama',
    'kota' => 'kota',
    'hp' => 'telepon',
    'alamat' => 'alamat',
);


$config['field_mapping']['inventory'] = array(
    'material' => 'id_nama_material',
    'merk' => 'id_merk_material',
    'satuan' => 'id_uom',
    'supplier' => 'id_supplier',
    'jumlah' => 'jumlah',
    'harga' => 'harga',
    'keterangan' => 'keterangan',
    'tanggal' => 'tanggal'
);