<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{

    private $params = array(
        "pageName" => "Utils",
        'navbar' => 'components/navbar/navbar_dore',
        'sidebar' => 'components/sidebar/sidebar_dore',
        'loadingAnim' => true,
		'adaThemeSelector' => true,
        'navbarConf' => array('adaSidebar' => true)
    );
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Satuan_model');
       
    }

    public function index()
    {
        if (!is_login('admin'))
            response("Tidak memiliki akses", 403);

        $data = $this->params + [
            'resource' => array('main', 'dore', 'icons', 'form', 'datatables'),
            'subPageName' => 'User Management',
            'data_content' => array(
                'dtid' => 'dt-user',
                'dtTitle' => 'Daftar User',
                'head' => array(
                    'Id',
                    'Nama',
                    'Email',
                    'Nomor HP',
                    'Alamat',
                    'Role',
                    'Tgl Registrasi',
                    'Registrar', 
                    'Photo'
                )
            ),
            'content' => array('components/compui/datatables.responsive'),
            'sidebarConf' => config_sidebar('admin', 4, 3)
        ];
        $this->add_cachedJavascript('utils/datatables.renderer', 'file', 'body:end', [
            'tableid' => 'dt-user',
            'url_tambah_data' => 'users/add',
            'url_sumber_data' => 'users/list',
            'url_update_data' => 'users/update',
            'url_delete_data' => 'users/delete',
            'form' => 'forms/tambah_user',
            'adaCheckbox' => "true",
            'row_scirpt' => "(d, i) => {
				return '<tr>' +
						'<td>' + d.id_user + '</td>'+
						'<td>' + d.user_name + '</td>'+
						'<td>' + d.user_email + '</td>'+
						'<td>' + d.user_phone + '</td>'+
						'<td>' + d.user_address + '</td>'+
						'<td>' + d.user_role.capitalize() + '</td>'+
						'<td>' + d.created_at + '</td>'+
						'<td>' + d.registrar + '</td>'+
						'<td style=text-align:center> <img style=width:80px;height:auto; src=' + path + 'public/img/profile/' + d.user_avatar + '></td>'+
					'</tr>';
			} ",
            'editCallback' => "(data) => {
				$('#id').val(data[0])
				$('#username').val(data[1])
				$('#email').val(data[2])
				$('#hp').val(data[3])
				$('#alamat').val(data[4])
			}"
        ]);
        $this->addViews('templates/backoffice_dore', $data);
        $this->render();
    }

    function akun (){
        if(!is_login())
            redirect(base_url());
        $sidebarConf ['menus'][0]['active'] = true;
        $data = $this->params + array(
            'sidebarConf' => sessiondata('login', 'user_role') == 'admin' ? config_sidebar('admin', 4, 5) : config_sidebar('staff', 2, 0),
            'content' => array('pages/users'),
            'resource' => array('main', 'dore', 'form', 'icons'),
            'subPageName' => 'Akun Saya'
        );

        
        // $this->add_cachedJavascript('js/pages/laporan.js');
        $this->add_cachedStylesheet('pages/profile');
        $this->add_javascript(['src' => base_url('public/assets/vendor/lightbox/js/lightbox.min.js'), 'pos' => 'body:end', 'type' => 'file']);
        $this->add_stylesheet(['src' => base_url('public/assets/vendor/lightbox/css/lightbox.min.css'), 'pos' => 'head', 'type' => 'file']);
        $this->addViews('templates/backoffice_dore', $data);
        $this->add_cachedJavascript('pages/profile');
        $this->render();
    }

    function list()
    {
        $res = $this->db->where("registrar != 'default'", null, false)->get('users')->result();
        response(['data' => $res]);
    }

    function add()
    {
        if (!httpmethod())
            response("Metode Akses Ilegal", 403);
        $pass = $_POST['hp'];
        $data = fieldmapping($_POST, 'user', ['dibuat' => waktu(), 'role' => 'staff', 'avatar' => 'default.jpg', 'registrar' => sessiondata('login', 'user_name'), 'password' => password_hash($pass, PASSWORD_DEFAULT)]);
        try {
            $this->db->insert('users', $data);
        } catch (\Throwable $th) {
            response("Terjadi kesalahan", 500);
        }
        // $this->load->helper('mailsender');
        // $res = sendemail($data['user_email'], "Anda telah di daftarkan sebagai " . $data['user_role'] . " di Inventory Barang, passwod default anda adalah <b>" . $pass . "</b>, segera rubah password anda", "Register Inventory Barang", $data['registrar'] . " - Admin Inventory Barang", false, 'dev.kamscode@kamscode.tech');

        // if (!$res['sts'])
        //     response(['message' => "Gagal Mengirim Email", 'err' => $res['message']], 500);

        response("Berhasil, Mendaftarkan User Baru");
    }

    function update_profile(){
        $input = fieldmapping($_POST, 'user', array('dibuat' => sessiondata('login', 'created_at')));
        
        if(isset($_FILES['pp']) && !empty($_FILES['pp'])){
            $this->load->helper('upload');
            $sebelumnya = sessiondata('login', 'user_avatar') == 'default.jpg' ? null : sessiondata('login', 'user_avatar');
            $res = upload_image($_FILES['pp'], 'img/profile', ['sebelum' => $sebelumnya]);

            if($res[1])
                $input['user_avatar'] = $res[0];
            else
                $input['user_avatar'] = sessiondata('login', 'user_avatar');

        }
        else
            $input['user_avatar'] = sessiondata('login', 'user_avatar');

        if(isset($input['user_password']) && !empty($input['user_password']))
            $input['user_password'] = password_hash($input['user_password'], PASSWORD_DEFAULT);
        else
            unset($input['user_password']);

        try {
            $this->db->where('id_user', $_POST['id'])->update('users', $input);
        } catch (\Throwable $th) {
            response("Gagal, Terjadi kesalahan", 500);
        }
        
        if(isset($input['user_password']))
            unset($input['user_password']);

        $input['id_user'] = sessiondata('login', 'id_user');
        $this->session->set_userdata('login', $input);

        response("Berhasil update profile");
    }

    function update()
    {
        if (!httpmethod())
            response("Metode Akses Ilegal", 403);

        $data = fieldmapping($_POST, 'user');
        try {
            $this->db->where('id_user', $_POST['id'])
                ->update('users', $data);
        } catch (\Throwable $th) {
            response("Terjadi kesalahan", 500);
        }
        response("Berhasil, Update Data User");
    }

    function delete()
    {
        if (!httpmethod())
            response("Metode Akses Ilegal", 403);
        try {
            $this->db->where_in('id_user', $_POST['ids'])
                ->delete('users');
        } catch (\Throwable $th) {
            response("Terjadi kesalahan", 500);
        }
        response("Berhasil, Menghapus User");
    }
}
