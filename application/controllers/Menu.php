<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends CI_Controller
{

    //cek session login dan cek role/level/hakakses
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
    }

    public function index()
    {
        $data['judul'] = 'Menu Management';
        $data['user'] = $this->db->get_where('user', ['Email' => $this->session->userdata('email')])->row_array(); //ambil data berdasarkan email
        $data['menu'] = $this->db->get_where('menu')->result_array(); //tampilkan semua data
        //var_dump($data['user']);
        //die;
        //echo 'Selamat datang ' . $data['user']['Email'];

        //set rules
        $this->form_validation->set_rules('menu', 'Menu', 'required');

        if ($this->form_validation->run() == false) {

            $this->load->view('templates/main_header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('templates/main_footer');
        } else {
            $this->db->insert('menu', ['menu' => $this->input->post('menu')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Menu berhasil ditambahkan.</div>');
            redirect('menu');
        }
    }

    public function submenu()
    {

        $data['judul']   = 'Submenu Management';
        $data['user']    = $this->db->get_where('user', ['Email' => $this->session->userdata('email')])->row_array(); //ambil data berdasarkan email

        $this->load->model('Menu_model', 'menu'); //panggil model nya
        $data['subMenu'] = $this->menu->getSubMenu();

        $data['menu'] = $this->db->get('menu')->result_array(); //menampilkan data menu

        //set rules
        $this->form_validation->set_rules('menu_id', 'Menu', 'required');
        $this->form_validation->set_rules('title', 'Submenu title', 'required');
        $this->form_validation->set_rules('url', 'Url', 'required');
        $this->form_validation->set_rules('icon', 'Icon', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/main_header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('menu/submenu', $data);
            $this->load->view('templates/main_footer');
        } else {
            $data = [
                'menu_id' => $this->input->post('menu_id'),
                'title' => $this->input->post('title'),
                'url' => $this->input->post('url'),
                'icon' => $this->input->post('icon'),
                'is_active' => $this->input->post('is_active')
            ];

            $this->db->insert('sub_menu', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Submenu berhasil ditambahkan.</div>');
            redirect('menu/submenu');
        }
    }
}
