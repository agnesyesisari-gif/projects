<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RegisterController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('UserModel');
        $this->load->model('JemaatModel');
        $this->load->library(['form_validation', 'session', 'email']);
        $this->load->helper(['url', 'form', 'security']);
    }

    /**
     * Tampilan form pendaftaran user baru
     */
    public function index() {
        // Redirect jika sudah login
        if ($this->session->userdata('logged_in')) {
            redirect('user/dashboard');
        }

        $data = [
            'title' => 'Pendaftaran Anggota Jemaat',
            'kelompok_pelayanan' => $this->JemaatModel->get_kelompok_pelayanan(),
            'wilayah' => $this->JemaatModel->get_wilayah()
        ];

        $this->load->view('auth/header', $data);
        $this->load->view('auth/register', $data);
        $this->load->view('auth/footer');
    }

    /**
     * Proses pendaftaran user baru
     */
    public function process_registration() {
        // Validasi CSRF token
        if (!$this->input->post($this->security->get_csrf_token_name()) || 
            $this->input->post($this->security->get_csrf_token_name()) !== $this->security->get_csrf_hash()) {
            $this->session->set_flashdata('error', 'Token CSRF tidak valid.');
            redirect('register');
        }

        // Set rules validasi
        $this->set_validation_rules();

        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, tampilkan error
            $this->session->set_flashdata('error', validation_errors());
            $this->session->set_flashdata('form_data', $this->input->post());
            redirect('register');
        } else {
            // Proses pendaftaran
            $registration_result = $this->process_user_registration();
            
            if ($registration_result['status']) {
                $this->session->set_flashdata('success', $registration_result['message']);
                
                // Send email verification jika diperlukan
                if ($this->input->post('email')) {
                    $this->send_verification_email($registration_result['user_id']);
                }
                
                redirect('auth/login');
            } else {
                $this->session->set_flashdata('error', $registration_result['message']);
                $this->session->set_flashdata('form_data', $this->input->post());
                redirect('register');
            }
        }
    }

    /**
     * Set rules validasi form
     */
    private function set_validation_rules() {
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|min_length[3]|max_length[100]|regex_match[/^[a-zA-Z\s\.]+$/]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|matches[confirm_password]');
        $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'required');
        $this->form_validation->set_rules('telepon', 'Nomor Telepon', 'required|numeric|min_length[10]|max_length[15]');
        $this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'required');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required|in_list[L,P]');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required|min_length[10]');
        $this->form_validation->set_rules('id_wilayah', 'Wilayah', 'required|numeric');
        $this->form_validation->set_rules('status_anggota', 'Status Anggota', 'required|in_list[anggota,simpatisan,calon_anggota]');
        
        // Custom error messages
        $this->form_validation->set_message('is_unique', 'Email {field} sudah terdaftar.');
        $this->form_validation->set_message('regex_match', '{field} hanya boleh mengandung huruf, spasi, dan titik.');
    }

    /**
     * Proses registrasi user
     */
    private function process_user_registration() {
        $this->db->trans_start(); // Start transaction

        try {
            // Data user untuk tabel users
            $user_data = [
                'nama_lengkap' => $this->input->post('nama_lengkap'),
                'email' => $this->input->post('email'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'telepon' => $this->input->post('telepon'),
                'role' => 'jemaat',
                'status' => 'active', // Token verification disabled
                'email_verified' => 1,
                'tanggal_daftar' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Insert ke tabel users
            $user_id = $this->UserModel->insert_user($user_data);

            if (!$user_id) {
                throw new Exception('Gagal menyimpan data user.');
            }

            // Data jemaat untuk tabel jemaat
            $jemaat_data = [
                'user_id' => $user_id,
                'nama_lengkap' => $this->input->post('nama_lengkap'),
                'tempat_lahir' => $this->input->post('tempat_lahir'),
                'tanggal_lahir' => $this->input->post('tanggal_lahir'),
                'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                'alamat' => $this->input->post('alamat'),
                'tanggal_bergabung' => date('Y-m-d'),
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Insert ke tabel jemaat
            $jemaat_id = $this->JemaatModel->insert_jemaat($jemaat_data);

            if (!$jemaat_id) {
                throw new Exception('Gagal menyimpan data jemaat.');
            }

            // Process kelompok pelayanan jika dipilih
            $kelompok_pelayanan = $this->input->post('kelompok_pelayanan');
            if (!empty($kelompok_pelayanan)) {
                foreach ($kelompok_pelayanan as $kelompok_id) {
                    $kelompok_data = [
                        'id_jemaat' => $jemaat_id,
                        'id_kelompok' => $kelompok_id,
                        'tanggal_bergabung' => date('Y-m-d')
                    ];
                    $this->JemaatModel->insert_kelompok_jemaat($kelompok_data);
                }
            }

            // Generate nomor anggota
            $nomor_anggota = $this->generate_nomor_anggota($jemaat_id, $this->input->post('id_wilayah'));
            $this->JemaatModel->update_nomor_anggota($jemaat_id, $nomor_anggota);

            $this->db->trans_complete(); // Complete transaction

            if ($this->db->trans_status