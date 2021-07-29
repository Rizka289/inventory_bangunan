<?php

if (!method_exists($this, 'upload_image')) {
    function upload_image($gambar, $tujuan, $conf = null)
    {
        $config = array(
            'default' => null,
            'asal' => null,
            'maks' => 6000000,
            'isEnkripsi' => true,
        );
        if (!empty($conf))
            foreach ($conf as $k => $v)
                $config[$k] = $v;


        $nama_file = $gambar['name'];
        $ukuran_file = $gambar['size'];
        $error = $gambar['error'];
        $tmp = $gambar['tmp_name'];
        $format_sesuai = ['jpg', 'jpeg', 'png'];
        $format_file = explode('.', $nama_file);
        $format_file = strtolower(end($format_file));
        if (!in_array($format_file, $format_sesuai)) {
            response(['message' => 'Gagal, Pilih file yang Valid jpg/jpeg/png'], 500);
        } elseif ($ukuran_file > $config['maks']) {
            response(['message' => 'Gagal, size file yang dipilih terlalu besar'], 500);
        } else {
            if ($config['isEnkripsi']) {
                $nama_image = random(10);
                $nama_image .= ".";
                $nama_image .= $format_file;
            } else
                $nama_image =  $config['name'] . '.' . $format_file;
            try {
                move_uploaded_file($tmp,  get_path('static', $tujuan . '/'. $nama_image));
                if (isset($config['sebelum']) && !empty($config['sebelum'])) {
                    unlink(get_path('static', $tujuan . '/' . $config['sebelum']));
                }
            } catch (\Exception $err) {
                return ['', false];
            }
            
            return [$nama_image, true];
        }
    }
}
