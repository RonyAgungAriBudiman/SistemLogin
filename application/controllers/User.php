<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

    //cek session login dan cek role/level/hakakses
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
    }

    public function index()
    {
        $data['judul'] = 'My Profile';
        $data['user'] = $this->db->get_where('user', ['Email' => $this->session->userdata('email')])->row_array();
        //var_dump($data['user']);
        //die;
        //echo 'Selamat datang ' . $data['user']['Email'];
        $this->load->view('templates/main_header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('user/index', $data);
        $this->load->view('templates/main_footer');
    }

    public function edit()
    {
        $data['judul'] = 'Edit Profile';
        $data['user'] = $this->db->get_where('user', ['Email' => $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');

        if ($this->form_validation->run() == false) {

            $this->load->view('templates/main_header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('user/edit', $data);
            $this->load->view('templates/main_footer');
        } else {
            $nama  = $this->input->post('nama');
            $email = $this->input->post('email');

            //cek jika ada gambar
            $upload_image = $_FILES['image']['name'];
            if ($upload_image) {

                $config['allowed_types'] = 'gif|jpg|png';
                $config['upload_path'] = './assets/img/profile/';
                $config['max_size']     = '2048';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {

                    $old_image = $data['user']['Image'];
                    if ($old_image != 'default.jpg') {
                        unlink(FCPATH . 'assets/img/profile/' . $old_image);
                    }

                    $new_image = $this->upload->data('file_name');
                    $this->db->set('Image', $new_image);
                } else {
                    echo $this->upload->dispay_errors();
                }
            }


            $this->db->set('Nama', $nama);
            $this->db->where('Email', $email);
            $this->db->update('user');



            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Profil berhasil diupdate!.</div>');
            redirect('user');
        }
    }

    public function ubahpassword()
    {
        $data['judul'] = 'Ubah Password';
        $data['user'] = $this->db->get_where('user', ['Email' => $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('current_password', 'Password', 'required|trim');
        $this->form_validation->set_rules(
            'new_password1',
            'New Password',
            'required|trim|min_length[3]|matches[new_password2]',
            [
                'matches' => 'Password tidak Cocok!.',
                'min_length' => 'Password terlalu pendek!.'
            ]
        );
        $this->form_validation->set_rules(
            'new_password2',
            'Confirm Password',
            'required|trim|min_length[3]|matches[new_password1]',
            [
                'matches' => 'Password tidak Cocok!.',
                'min_length' => 'Password terlalu pendek!.'
            ]
        );

        if ($this->form_validation->run() == false) {

            $this->load->view('templates/main_header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('user/ubahpassword', $data);
            $this->load->view('templates/main_footer');
        } else {
            //terima post current password
            $current_password = $this->input->post('current_password');
            $new_password     = $this->input->post('new_password1');

            //cek jika password tidak sesuai
            if (!password_verify($current_password, $data['user']['Password'])) {

                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                Password yang anda masukan salah!.</div>');
                redirect('user/ubahpassword');
            } else {
                if ($current_password == $new_password) {

                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                    New Password tidak boleh sama dengan Password !.</div>');
                    redirect('user/ubahpassword');
                } else {
                    // jika password sudah ok
                    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                    $this->db->set('Password', $password_hash);
                    $this->db->where('Email', $this->session->userdata('email'));
                    $this->db->update('user');

                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                    Password berhasil diubah!.</div>');
                    redirect('user/ubahpassword');
                }
            }
        }
    }
}
