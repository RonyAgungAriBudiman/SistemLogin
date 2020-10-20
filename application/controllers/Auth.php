<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }


    public function index()
    {
        if ($this->session->userdata('email')) {
            redirect('user');
        }

        //buat role validasi
        $this->form_validation->set_rules(
            'email',
            'Email',
            'trim|required|valid_email',
            [

                'valid_email' => 'Email tidak valid!.'
            ]
        );
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == false) {

            $data['judul'] = 'Login';
            $this->load->view('templates/header', $data);
            $this->load->view('auth/login');
            $this->load->view('templates/footer');
        } else {
            // jika validasi sukses
            $this->_login();
        }
    }

    private function _login()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->db->get_where('user', ['Email' => $email])->row_array();
        // var_dump($user); menampilkan isi data
        // die;


        //jika usernya ada
        if ($user) {
            //jika user aktif
            if ($user['IsActive'] == 1) {
                //cek password
                if (password_verify($password, $user['Password'])) {

                    $data = [

                        'email' => $user['Email'],
                        'roleid' => $user['RoleId']
                    ];
                    $this->session->set_userdata($data);
                    if ($user['RoleId'] == 1) {
                        redirect('admin');
                    } else {
                        redirect('user');
                    }
                } else {

                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                    Maaf password anda salah!.</div>');
                    redirect('auth');
                }
            } else {

                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                Email belum aktivasi!.</div>');
                redirect('auth');
            }
        } else {

            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Email tidak terdaftar!.</div>');
            redirect('auth');
        }
    }

    public function registrasi()
    {
        if ($this->session->userdata('email')) {
            redirect('user');
        }
        //buat role validasi
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', ['is_unique' => 'Alamat email sudah digunakan.']);
        $this->form_validation->set_rules(
            'password1',
            'Password',
            'required|trim|min_length[3]|matches[password2]',
            [
                'matches' => 'Password tidak Cocok!.',
                'min_length' => 'Password terlalu pendek!.'
            ]
        );
        $this->form_validation->set_rules(
            'password2',
            'Password',
            'required|trim|matches[password1]',
            [

                'matches' => 'Password tidak Cocok!.'
            ]
        );

        //jika validasi false 
        if ($this->form_validation->run() == false) {

            $data['judul'] = 'Registrasi';
            $this->load->view('templates/header', $data);
            $this->load->view('auth/registrasi');
            $this->load->view('templates/footer');
        } else {
            $email  = $this->input->post('email', true);
            $data   = [
                'Nama'      => htmlspecialchars($this->input->post('nama', true)),
                'Email'     => htmlspecialchars($email),
                'Image'     => 'default.jpg',
                'Password'  => password_hash($this->input->post('password1', true), PASSWORD_DEFAULT),
                'RoleId'    => 2,
                'IsActive'  => 0,
                'DateCreated' => time()
            ];

            //siapkan token
            $token = base64_encode(random_bytes(32));
            $user_token = [
                'email' => $email,
                'token' => $token,
                'date_created' => time()
            ];

            $this->db->insert('user', $data);
            $this->db->insert('user_token', $user_token);

            $this->_sendEmail($token, 'verify');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Selamat! Akun anda berhasil dibuat. Silahkan Aktivasi.</div>');
            redirect('auth');
        }
    }

    private function _sendEmail($token, $type)
    {
        $config = [
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_user' => 'appenline@gmail.com',
            'smtp_pass' => '<?=1234567890?>',
            'smtp_port' => '465',
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'newline'   => "\r\n"
        ];

        $this->load->library('email', $config);
        $this->email->initialize($config);

        $this->email->from('appenline@gmail.com', 'Aplikasi Pendaftaran Online');
        $this->email->to($this->input->post('email', true));

        if ($type == 'verify') {

            $this->email->subject('Aktivasi Akun');
            $this->email->message('klik link ini untuk verifikasi akun anda : <a href="' . base_url() . 'auth/verify?email=' . $this->input->post('email') . '&token=' . urlencode($token) . ' " >Aktifkan</a> ');
        } else if ($type == 'forgot') {
            $this->email->subject('Reset Password');
            $this->email->message('klik link berikut untuk reset password : <a href="' . base_url() . 'auth/resetpassword?email=' . $this->input->post('email') . '&token=' . urlencode($token) . ' " >Reset</a> ');
        }

        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            die;
        }
    }

    public function verify()
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        $user = $this->db->get_where('user', ['Email' => $email])->row_array();

        if ($user) {
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();
            if ($user_token) {
                if (time() - $user_token['date_created'] < (60 * 60 * 24)) {
                    $this->db->set('IsActive', 1);
                    $this->db->where('Email', $email);
                    $this->db->update('user');

                    $this->db->delete('user_token', ['Email' => $email]);

                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                    ' . $email . ' sudah aktif. Silahkan login. </div>');
                    redirect('auth');
                } else {
                    $this->db->delete('user', ['Email' => $email]);
                    $this->db->delete('user_token', ['Email' => $email]);

                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                    Aktivasi gagal! Token kadaluarsa. </div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                Aktivasi gagal! Token salah. </div>');
                redirect('auth');
            }
        } else {

            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Aktivasi gagal! Email salah. </div>');
            redirect('auth');
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('roleid');

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
        Terima kasih. Anda berhasil logout!. </div>');
        redirect('auth');
    }

    public function blocked()
    {

        $this->load->view('auth/blocked');
    }

    public function forgotpassword()
    {
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        if ($this->form_validation->run() == false) {

            $data['judul'] = 'Forgot Password';
            $this->load->view('templates/header', $data);
            $this->load->view('auth/forgot-password');
            $this->load->view('templates/footer');
        } else {
            $email = $this->input->post('email');
            $user  = $this->db->get_where('user', ['Email' => $email, 'IsActive' => 1])->row_array();

            if ($user) {

                $token = base64_encode(random_bytes(32));
                $user_token = [
                    'email' => $email,
                    'token' => $token,
                    'date_created' => time()
                ];

                $this->db->insert('user_token', $user_token);
                $this->_sendEmail($token, 'forgot');

                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                Silahkan chek email untuk reset password </div>');
                redirect('auth/forgotpassword');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                Email tidak terdaftar atau tidak aktif. </div>');
                redirect('auth/forgotpassword');
            }
        }
    }

    public function resetpassword()
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        $user = $this->db->get_where('user', ['Email' => $email])->row_array();

        if ($user) {
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();
            if ($user_token) {

                $this->session->set_userdata('reset_email', $email);
                $this->changepassword();
            } else {

                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                Reset password gagal, Token salah!. </div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Reset password gagal, Email salah!. </div>');
            redirect('auth');
        }
    }

    public function changepassword()
    {
        if (!$this->session->userdata('reset_email')) {
            redirect('auth');
        }

        $this->form_validation->set_rules(
            'password1',
            'Password',
            'trim|required|min_length[3]|matches[password2]',
            [
                'matches' => 'Password tidak Cocok!.',
                'min_length' => 'Password terlalu pendek!.'
            ]
        );

        $this->form_validation->set_rules(
            'password2',
            'Repeat Password',
            'trim|required|min_length[3]|matches[password1]',
            [
                'matches' => 'Password tidak Cocok!.'
            ]
        );

        if ($this->form_validation->run() == false) {

            $data['judul'] = 'Change Password';
            $this->load->view('templates/header', $data);
            $this->load->view('auth/change-password');
            $this->load->view('templates/footer');
        } else {

            $password = password_hash($this->input->post('password1'), PASSWORD_DEFAULT);
            $email    = $this->session->userdata('reset_email');

            $this->db->set('Password', $password);
            $this->db->where('Email', $email);
            $this->db->update('user');

            $this->session->unset_userdata('reset_email');

            $this->db->delete('user_token', ['email' => $email]);

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Password berhasil direset, Silahkan login!. </div>');
            redirect('auth');
        }
    }
}
