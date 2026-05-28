<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\RoleModel;

class UserController extends BaseController
{
    protected $userModel;
    protected $roleModel;
    protected $helpers = ['form', 'url', 'auth'];

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
        
        // Middleware/Filter untuk authentication
        $this->checkLogin();
    }

    /**
     * Check jika user sudah login
     */
    private function checkLogin()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
    }

    /**
     * Menampilkan daftar semua user
     */
    public function index()
    {
        // Check role permission
        if (!in_array(session()->get('role'), ['admin', 'super_admin'])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses!');
        }

        $data = [
            'title' => 'Manajemen User',
            'users' => $this->userModel->getUsersWithRoles(),
            'roles' => $this->roleModel->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('admin/users/index', $data);
    }

    /**
     * Menampilkan form tambah user
     */
    public function create()
    {
        if (!in_array(session()->get('role'), ['admin', 'super_admin'])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses!');
        }

        $data = [
            'title' => 'Tambah User Baru',
            'roles' => $this->roleModel->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('admin/users/create', $data);
    }

    /**
     * Menyimpan user baru
     */
    public function store()
    {
        if (!in_array(session()->get('role'), ['admin', 'super_admin'])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses!');
        }

        // Validation rules
        $rules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'password_confirmation' => 'required|matches[password]',
            'role_id' => 'required|numeric',
            'phone' => 'permit_empty|min_length[10]|max_length[15]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Hash password
        $password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

        $data = [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
            'password' => $password,
            'role_id' => $this->request->getPost('role_id'),
            'phone' => $this->request->getPost('phone'),
            'jabatan_gereja' => $this->request->getPost('jabatan_gereja'),
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => session()->get('user_id')
        ];

        if ($this->userModel->save($data)) {
            return redirect()->to('/admin/users')->with('success', 'User berhasil ditambahkan!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan user!');
        }
    }

    /**
     * Menampilkan detail user
     */
    public function show($id = null)
    {
        $user = $this->userModel->getUserWithRole($id);
        
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan!');
        }

        // Check permission (hanya admin atau user sendiri)
        if (session()->get('role') !== 'admin' && 
            session()->get('role') !== 'super_admin' && 
            session()->get('user_id') != $id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses!');
        }

        $data = [
            'title' => 'Detail User',
            'user' => $user,
            'role' => $this->roleModel->find($user['role_id'])
        ];

        return view('admin/users/show', $data);
    }

    /**
     * Menampilkan form edit user
     */
    public function edit($id = null)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan!');
        }

        // Check permission
        if (session()->get('role') !== 'admin' && 
            session()->get('role') !== 'super_admin' && 
            session()->get('user_id') != $id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses!');
        }

        $data = [
            'title' => 'Edit User',
            'user' => $user,
            'roles' => $this->roleModel->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('admin/users/edit', $data);
    }

    /**
     * Update user
     */
    public function update($id = null)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan!');
        }

        // Check permission
        if (session()->get('role') !== 'admin' && 
            session()->get('role') !== 'super_admin' && 
            session()->get('user_id') != $id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses!');
        }

        // Validation rules
        $rules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
            'username' => "required|min_length[3]|max_length[50]|is_unique[users.username,id,{$id}]",
            'role_id' => 'required|numeric',
            'phone' => 'permit_empty|min_length[10]|max_length[15]'
        ];

        // Jika password diisi, validasi password
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
            $rules['password_confirmation'] = 'matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id' => $id,
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email' => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
            'role_id' => $this->request->getPost('role_id'),
            'phone' => $this->request->getPost('phone'),
            'jabatan_gereja' => $this->request->getPost('jabatan_gereja'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => session()->get('user_id')
        ];

        // Update password jika diisi
        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        if ($this->userModel->save($data)) {
            return redirect()->to('/admin/users')->with('success', 'User berhasil diupdate!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate user!');
        }
    }

    /**
     * Delete user
     */
    public function delete($id = null)
    {
        if (!in_array(session()->get('role'), ['admin', 'super_admin'])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses!');
        }

        // Cegah delete diri sendiri
        if (session()->get('user_id') == $id) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan!');
        }

        if ($this->userModel->delete($id)) {
            return redirect()->to('/admin/users')->with('success', 'User berhasil dihapus!');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus user!');
        }
    }

    /**
     * Ubah status user (active/inactive)
     */
    public function toggleStatus($id = null)
    {
        if (!in_array(session()->get('role'), ['admin', 'super_admin'])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses!');
        }

        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan!');
        }

        $newStatus = $user['status'] == 'active' ? 'inactive' : 'active';
        
        $data = [
            'id' => $id,
            'status' => $newStatus,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => session()->get('user_id')
        ];

        if ($this->userModel->save($data)) {
            $statusText = $newStatus == 'active' ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->to('/admin/users')->with('success', "User berhasil {$statusText}!");
        } else {
            return redirect()->back()->with('error', 'Gagal mengubah status user!');
        }
    }

    /**
     * Profile user (untuk user login sendiri)
     */
    public function profile()
    {
        $id = session()->get('user_id');
        $user = $this->userModel->getUserWithRole($id);
        
        $data = [
            'title' => 'Profil Saya',
            'user' => $user,
            'role' => $this->roleModel->find($user['role_id'])
        ];

        return view('users/profile', $data);
    }

    /**
     * Update profile
     */
    public function updateProfile()
    {
        $id = session()->get('user_id');
        $user = $this->userModel->find($id);

        $rules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
            'username' => "required|min_length[3]|max_length[50]|is_unique[users.username,id,{$id}]",
            'phone' => 'permit_empty|min_length[10]|max_length[15]'
        ];

        // Jika password diisi
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
            $rules['password_confirmation'] = 'matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id' => $id,
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email' => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
            'phone' => $this->request->getPost('phone'),
            'jabatan_gereja' => $this->request->getPost('jabatan_gereja'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Update password jika diisi
        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        if ($this->userModel->save($data)) {
            // Update session data
            $sessionData = [
                'user_id' => $id,
                'nama_lengkap' => $data['nama_lengkap'],
                'email' => $data['email'],
                'username' => $data['username'],
                'role' => $user['role_id'], // Tetap gunakan role yang sama
                'isLoggedIn' => true
            ];
            session()->set($sessionData);

            return redirect()->to('/profile')->with('success', 'Profil berhasil diupdate!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate profil!');
        }
    }
    
}