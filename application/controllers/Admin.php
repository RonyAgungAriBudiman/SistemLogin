<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    //cek session login dan cek role/level/hakakses
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
    }

    public function index()
    {
        $data['judul'] = 'Dashboard';
        $data['user'] = $this->db->get_where('user', ['Email' => $this->session->userdata('email')])->row_array();
        //var_dump($data['user']);
        //die;
        //echo 'Selamat datang ' . $data['user']['Email'];
        $this->load->view('templates/main_header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('templates/main_footer');
    }

    public function role()
    {

        $data['judul'] = 'Role';
        //data session
        $data['user'] = $this->db->get_where('user', ['Email' => $this->session->userdata('email')])->row_array();
        //data role
        $data['role'] = $this->db->get('user_role')->result_array();

        $this->load->view('templates/main_header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/role', $data);
        $this->load->view('templates/main_footer');
    }

    public function roleaccess($role_id)
    {
        $data['judul'] = 'Role Access';
        //data session
        $data['user'] = $this->db->get_where('user', ['Email' => $this->session->userdata('email')])->row_array();
        //data role
        $data['role'] = $this->db->get_where('user_role', ['Id' => $role_id])->row_array();
        //data menu
        $this->db->where('id!=', 1);
        $data['menu'] = $this->db->get('menu')->result_array();

        $this->load->view('templates/main_header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/role-access', $data);
        $this->load->view('templates/main_footer');
    }

    public function changeaccess()
    {

        // echo "ada";
        $menu_id = $this->input->post('menuId');
        $role_id = $this->input->post('roleId');

        $data = [
            'role_id' => $role_id,
            'menu_id' => $menu_id
        ];

        $result = $this->db->get_where('user_access_menu', $data);

        if ($result->num_rows() < 1) {
            $this->db->insert('user_access_menu', $data);
        } else {
            $this->db->delete('user_access_menu', $data);
        }

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
        Akses dirubah!. </div>');
    }
}
