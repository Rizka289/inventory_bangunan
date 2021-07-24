<?php

class Auth
{
    function prepare(&$post, $mode = 'login')
    {
        switch ($mode) {
            case 'login':
                if (is_login())
                    response(['message' => 'Anda sudah login', 'type' => 'error'], 401);

                $input = fieldmapping($post, 'login');
                $newinput = $input;
                break;
            case 'registrasi':
                if (!is_login('ketua yayasan') && !is_login('kepala sekolah'))
                    response(['message' => 'Anda belum login', 'type' => 'error'], 401);

                $input = fieldmapping('user', $post);

                if ($input['_mode'] == 'edit') {
                    /** @var CI_Controller $ci */
                    $ci = &get_instance();
                    $user = $ci->db->where('username', $input['username'])->or_where('email', $input['email'])->get('users')->result();

                    if (!empty($user))
                        response(['message' => 'Username/ email sudah digunakan', 'type' => 'Error'], 500);

                    if (!empty($input['nohp'])) {
                        $user = $ci->db->where('nohp', $input['nohp'])->get('user_info')->result();
                        if (!empty($user))
                            response(['message' => 'Username/ email sudah digunakan', 'type' => 'Error'], 500);
                    }
                }
                $input['role'] = !isset($input['role']) || empty($input['role']) ? 'bendahara 2' : $input['role'];
                if (!in_array($input['role'], ['admin', 'staff'])) {
                    response(['message' => 'Role untuk user baru tidak valid', 'type' => 'error'], 400);
                }
                $input['isActive'] = !isset($input['isActive']) || empty($input['isActive']) ? 1 : $input['isActive'];
                $input['_pass'] = substr(uniqid(), 0, 8);
                $input['password'] = !empty($input['password']) ? password_hash($input['password'], PASSWORD_DEFAULT)  : password_hash($input['_pass'], PASSWORD_DEFAULT);
                $newinput = $input;
                break;
            case 'edit':
                if (!is_login())
                    response(['message' => 'Anda belum login', 'type' => 'error'], 401);

                $input = fieldmapping('edit-user', $post);

                /** @var CI_Controller $ci */
                $ci = &get_instance();
                $ci->load->helper('file_upload');
                if (sessiondata('login', 'username') != $input['username'] && sessiondata('login', 'email') != $input['email']) {
                    $user = $ci->db->where('username', $input['username'])->or_where('email', $input['email'])->get('users')->result();

                    if (!empty($user))
                        response(['message' => 'Username/ email sudah digunakan', 'type' => 'Error'], 500);
                }
                if (!empty($input['nohp']) && sessiondata('login', 'nohp') != $input['nohp']) {
                    $user = $ci->db->where('nohp', $input['nohp'])->get('user_info')->result();
                    if (!empty($user))
                        response(['message' => 'Username/ email sudah digunakan', 'type' => 'Error'], 500);
                }

                // $input['role'] = !isset($input['role']) || empty($input['role']) ? 'bendahara 2' : $input['role'];
                if (!in_array($input['role'], ['admin', 'staff'])) {
                    response(['message' => 'Role untuk user baru tidak valid', 'type' => 'error'], 400);
                }
                $input['isActive'] = 1;

                $input['password'] = !empty($input['password']) ? password_hash($input['password'], PASSWORD_DEFAULT)  : null;

                if (isset($_FILES['pp']) && !empty($_FILES['pp']))
                    $input['photo'] = upload_Image($_FILES['pp'], 'pp');

                $newinput = $input;
                break;
        }

        return $newinput;
    }
    function login($input)
    {
        if (is_login())
            response(['message' => 'Anda sudah login', 'type' => 'error'], 401);

        /** @var CI_Controller $ci */
        $ci = &get_instance();

        $user = $ci->db
            ->select('*')
            ->where('user_email', $input['user'])
            ->from('users')
            ->get()->row_array();

        $ci->db->reset_query();

        if (empty($user)) {
            response(['message' => 'User ' . $input['user'] . ' Tidak ditemukan', 'type' => 'error'], 400);
        } else {
            if (password_verify($input['password'], $user['user_password'])) {
                // setciobject($user);
                unset($user['user_password']);
                $user['session_dibuat'] = time();

                $ci->session->set_userdata(['login' => $user]);
                response("Berhasil Login");
            } else {
                response(['message' => 'Password untuk ' . $input['user'] . ' Salah', 'type' => 'error'], 400);
            }
        }
    }

    // function register($input)
    // {
    //     if (!is_login('ketua yayasan') && !is_login('kepala sekolah'))
    //         response(['message' => 'Anda belum login', 'type' => 'error'], 401);

    //     /** @var CI_Controller $ci */
    //     $ci = &get_instance();
    //     $ci->load->helper('mail_sender');
    //     try {
    //         $datauser = array(
    //             'username' => $input['username'],
    //             'password' => $input['password'],
    //             'email' => $input['email'],
    //             'role' => $input['role'],
    //             'isActive' => $input['isActive']
    //         );
    //         if ($ci->db->insert('users', $datauser)) {
    //             $data = array(
    //                 'id' => substr(uniqid(), 3, 5),
    //                 'user' => $input['username'],
    //                 'jabatan' => $input['jabatan'],
    //                 'nohp' => $input['nohp'],
    //                 'nama_lengkap' => $input['nama_lengkap']
    //             );
    //             $ci->db->insert('user_info', $data);

    //             $pesan = 'Email anda telah di daftarkan ke situs ' . base_url() . ', pada ' . waktu() . 'berikut detail akun anda. Username: ' . $input['username'] . ', Password: ' . $input['_pass'];
    //             sendemail($input['email'], $pesan, 'Registrasi Akun', 'Sistem Pencatat Keuangan BQN', 'kamscode@kamscode.tech');
    //             response(['message' => 'Berhasil Registrasi', 'type' => 'sucsess']);
    //         } else
    //             response(['message' => 'Gagal Melakukan Registrasi', 'type' => 'error'], 400);
    //     } catch (\Throwable $err) {
    //         response(['message' => 'Gagal Melakukan Registrasi', 'type' => 'error', 'err' => print_r($err, true)], 400);
    //     }
    // }
    // function update($input)
    // {
    //     if (!is_login())
    //         response(['message' => 'Anda belum login', 'type' => 'error'], 401);

    //     /** @var CI_Controller $ci */
    //     $ci = &get_instance();
    //     $ci->load->helper('mail_sender');
    //     var_dump($input);
    //     $dataUser = [];
    //     $dataUserInfo = [];
    //     $old_username = $input['_username'];
    //     $iduser = $input['id_info'];
    //     $mode = $input['_mode'];
    //     unset($input['_username'], $input['_mode'], $input['id_info'], $input['_pass']);
    //     if (empty($input['password']))
    //         unset($input['password']);
    //     foreach ($input as $k => $v) {
    //         if ($k == 'username' || $k == 'email' || $k == 'role' || $k == 'isActive')
    //             $dataUser[$k] = $v;
    //         else
    //             $dataUserInfo[$k] = $v;
    //     }

    //     if (!is_login('admin'))
    //         unset($input['role']);
    //     $dataUserInfo['user'] = $dataUser['username'];
    //     if(!empty($dataUserInfo['password']))
    //         $dataUser['password'] = $dataUserInfo['password'];
    //     unset($dataUserInfo['password']);
    //     if(isset($dataUserInfo['photo']) && !empty($dataUserInfo['photo']) && !in_array(sessiondata('login', 'photo'), array('default.jpg', 'default.png')))
    //         unlink(ASSETS_PATH . 'public/assets/img/profile/' . sessiondata('login', 'photo'));
    //     try {
    //         $ci->db->where('username', $old_username)->update('users', $dataUser);
    //         $ci->db->reset_query();
    //         $ci->db->where('id', $iduser)->update('user_info', $dataUserInfo);

    //         if($mode == 'edit-pr'){
    //             if(isset($dataUser['email']) && !empty($dataUser['email'])){
    //                 $userdata = sessiondata();
    //                 $userdata['email'] = $dataUser['email'];
    //                 $ci->session->set_userdata('login', $userdata);
    //             }
    //             if(isset($dataUser['username']) && !empty($dataUser['username'])){
    //                 $userdata = sessiondata();
    //                 $userdata['username'] = $dataUser['username'];
    //                 $ci->session->set_userdata('login', $userdata);
    //             }
    //         }

    //         $pesan = 'Akun anda di situs ' . base_url() . ' talah di update, pada ' . waktu();
    //         sendemail($input['email'], $pesan, 'Akun Update', 'Sistem Pencatat Keuangan BQN', 'kamscode@kamscode.tech');
    //         response(['message' => 'Berhasil update data user', 'type' => 'success']);
    //     } catch (\Throwable $th) {
    //         response(['message' => 'Gagal, terjadi kesalahan', 'type' => 'error', 'err' => print_r($th, true)], 500);
    //     }
    // }
}
