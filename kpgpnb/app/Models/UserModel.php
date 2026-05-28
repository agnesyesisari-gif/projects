<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'email',
        'username',
        'password',
        'nama',
        'jabatan',
        'alamat',
        'telepon',
        'foto_profil',
        'id_komisi',
        'role',
        'status_akun',
        'last_login',
        'last_ip',
        'login_attempts',
        'created_by',
        'updated_by'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    protected $validationRules = [
        'email'    => 'required|valid_email|is_unique[users.email,id,{id}]',
        'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username,id,{id}]',
        'nama'     => 'required|min_length[3]|max_length[100]',
        'telepon'  => 'permit_empty|min_length[10]|max_length[20]',
        'role'     => 'required|in_list[admin,komisi,pengurus,jemaat]',
        'status_akun' => 'permit_empty|in_list[aktif,nonaktif]'
    ];
    
    protected $validationMessages = [
        'email' => [
            'required' => 'Email harus diisi',
            'valid_email' => 'Email tidak valid',
            'is_unique' => 'Email sudah terdaftar'
        ],
        'username' => [
            'required' => 'Username harus diisi',
            'min_length' => 'Username minimal 3 karakter',
            'max_length' => 'Username maksimal 30 karakter',
            'is_unique' => 'Username sudah digunakan'
        ],
        'nama' => [
            'required' => 'Nama lengkap harus diisi',
            'min_length' => 'Nama minimal 3 karakter',
            'max_length' => 'Nama maksimal 100 karakter'
        ]
    ];
    
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];
    
    /**
     * Hash password sebelum insert/update
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['data']['password']);
        }
        
        return $data;
    }
    
    /**
     * Get all users with role information
     */
    public function getAllUsers($filters = [], $limit = null, $offset = 0)
    {
        $builder = $this->db->table('users u');
        $builder->select('u.*, k.nama_komisi');
        $builder->join('komisi k', 'u.id_komisi = k.id_komisi', 'left');
        
        if (!empty($filters['status_akun'])) {
            $builder->where('u.status_akun', $filters['status_akun']);
        }
        
        if (!empty($filters['id_komisi'])) {
            $builder->where('u.id_komisi', $filters['id_komisi']);
        }
        
        if (!empty($filters['role'])) {
            $builder->where('u.role', $filters['role']);
        }
        
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('u.nama', $filters['search'])
                ->orLike('u.email', $filters['search'])
                ->orLike('u.username', $filters['search'])
                ->orLike('u.telepon', $filters['search'])
                ->groupEnd();
        }
        
        $builder->where('u.deleted_at', null);
        $builder->orderBy('u.created_at', 'DESC');
        
        if ($limit) {
            $builder->limit($limit, $offset);
        }
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Get user by ID with complete information
     */
    public function getUserById($id)
    {
        $builder = $this->db->table('users u');
        $builder->select('u.*, k.nama_komisi, k.kode_komisi');
        $builder->join('komisi k', 'u.id_komisi = k.id_komisi', 'left');
        $builder->where('u.id', $id);
        $builder->where('u.deleted_at', null);
        
        return $builder->get()->getRowArray();
    }
    
    /**
     * Get user by email
     */
    public function getUserByEmail($email)
    {
        return $this->where('email', $email)
                   ->where('deleted_at', null)
                   ->first();
    }
    
    /**
     * Get user by username
     */
    public function getUserByUsername($username)
    {
        return $this->where('username', $username)
                   ->where('deleted_at', null)
                   ->first();
    }
    
    /**
     * Get user roles
     */
    public function getUserRoles($userId)
    {
        $builder = $this->db->table('user_roles ur');
        $builder->select('ur.*, r.nama_role, r.deskripsi, r.permissions');
        $builder->join('roles r', 'ur.role_id = r.id');
        $builder->where('ur.user_id', $userId);
        $builder->orderBy('r.id', 'ASC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Add role to user
     */
    public function addUserRole($userId, $roleId)
    {
        $data = [
            'user_id' => $userId,
            'role_id' => $roleId,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => session()->get('id') ?? 1
        ];
        
        $builder = $this->db->table('user_roles');
        return $builder->insert($data);
    }
    
    /**
     * Remove role from user
     */
    public function removeUserRole($userRoleId)
    {
        $builder = $this->db->table('user_roles');
        return $builder->delete(['id' => $userRoleId]);
    }
    
    /**
     * Check if user has specific role
     */
    public function hasRole($userId, $roleName)
    {
        $builder = $this->db->table('user_roles ur');
        $builder->select('ur.id');
        $builder->join('roles r', 'ur.role_id = r.id');
        $builder->where('ur.user_id', $userId);
        $builder->where('r.nama_role', $roleName);
        
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Check if user has permission
     */
    public function hasPermission($userId, $permission)
    {
        $builder = $this->db->table('user_roles ur');
        $builder->select('r.permissions');
        $builder->join('roles r', 'ur.role_id = r.id');
        $builder->where('ur.user_id', $userId);
        
        $roles = $builder->get()->getResultArray();
        
        foreach ($roles as $role) {
            $permissions = json_decode($role['permissions'], true) ?? [];
            if (in_array($permission, $permissions)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Update last login info
     */
    public function updateLastLogin($userId, $ipAddress = null)
    {
        $data = [
            'last_login' => date('Y-m-d H:i:s'),
            'last_ip'    => $ipAddress ?? \Config\Services::request()->getIPAddress(),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->update($userId, $data);
    }
    
    /**
     * Get user activity log
     */
    public function getUserActivity($userId, $limit = 50)
    {
        $builder = $this->db->table('user_activity_log');
        $builder->where('user_id', $userId);
        $builder->orderBy('created_at', 'DESC');
        $builder->limit($limit);
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Log user activity
     */
    public function logActivity($userId, $activity, $details = null)
    {
        $data = [
            'user_id' => $userId,
            'activity' => $activity,
            'details' => $details ? json_encode($details) : null,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $builder = $this->db->table('user_activity_log');
        return $builder->insert($data);
    }
    
    /**
     * Get user statistics
     */
    public function getUserStatistics()
    {
        return [
            'total_users'    => $this->where('deleted_at', null)->countAllResults(),
            'active_users'   => $this->where('status_akun', 'aktif')->where('deleted_at', null)->countAllResults(),
            'inactive_users' => $this->where('status_akun', 'nonaktif')->where('deleted_at', null)->countAllResults(),
            'today_logins'   => $this->where('DATE(last_login)', date('Y-m-d'))->where('deleted_at', null)->countAllResults()
        ];
    }
    
    /**
     * Get users by komisi
     */
    public function getUsersByKomisi($idKomisi, $onlyActive = true)
    {
        $builder = $this->where('id_komisi', $idKomisi)->where('deleted_at', null);
        
        if ($onlyActive) {
            $builder->where('status_akun', 'aktif');
        }
        
        return $builder->orderBy('nama', 'ASC')->findAll();
    }
    
    /**
     * Get users with specific role
     */
    public function getUsersByRole($roleName)
    {
        $builder = $this->db->table('users u');
        $builder->select('u.*, r.nama_role');
        $builder->join('user_roles ur', 'u.id = ur.user_id');
        $builder->join('roles r', 'ur.role_id = r.id');
        $builder->where('r.nama_role', $roleName);
        $builder->where('u.status_akun', 'aktif');
        $builder->where('u.deleted_at', null);
        $builder->orderBy('u.nama', 'ASC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Change user password
     */
    public function changePassword($userId, $newPassword)
    {
        $data = [
            'password'   => password_hash($newPassword, PASSWORD_DEFAULT),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->update($userId, $data);
    }
    
    /**
     * Activate user account - DISABLED
     */
    public function activateAccount($token)
    {
        return false;
    }
    
    /**
     * Update user profile
     */
    public function updateProfile($userId, $data)
    {
        // Remove sensitive fields
        unset($data['password_hash']);
        unset($data['email']);
        unset($data['username']);
        
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $userId;
        
        return $this->update($userId, $data);
    }
    
    /**
     * Update user photo
     */
    public function updatePhoto($userId, $photoPath)
    {
        $user = $this->find($userId);
        if ($user && !empty($user['foto_profil']) && file_exists(FCPATH . $user['foto_profil'])) {
            unlink(FCPATH . $user['foto_profil']);
        }
        
        return $this->update($userId, [
            'foto_profil' => $photoPath,
            'updated_at'  => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Get user permissions
     */
    public function getUserPermissions($userId)
    {
        $permissions = [];
        
        $builder = $this->db->table('user_roles ur');
        $builder->select('r.permissions');
        $builder->join('roles r', 'ur.role_id = r.id');
        $builder->where('ur.user_id', $userId);
        
        $roles = $builder->get()->getResultArray();
        
        foreach ($roles as $role) {
            $rolePermissions = json_decode($role['permissions'], true) ?? [];
            $permissions = array_merge($permissions, $rolePermissions);
        }
        
        return array_unique($permissions);
    }
    
    /**
     * Get user dashboard data
     */
    public function getUserDashboardData($userId)
    {
        $data = [];
        
        // Get user info
        $data['user'] = $this->getUserById($userId);
        
        // Get user roles
        $data['roles'] = $this->getUserRoles($userId);
        
        // Get recent activities
        $data['recent_activities'] = $this->getUserActivity($userId, 10);
        
        // Get user's komisi programs count
        if ($data['user']['id_komisi']) {
            $prokerModel = new ProkerModel();
            $data['program_count'] = $prokerModel->where('id_komisi', $data['user']['id_komisi'])
                                               ->where('status', 'berjalan')
                                               ->countAllResults();
        }
        
        // Get user's assigned tasks/programs
        $data['assigned_programs'] = $this->getAssignedPrograms($userId);
        
        return $data;
    }
    
    /**
     * Get programs assigned to user
     */
    public function getAssignedPrograms($userId)
    {
        $builder = $this->db->table('program_kerja pk');
        $builder->select('pk.*, d.nama_departemen');
        $builder->join('komisi K', 'pk.id_komisi = K.id_komisi');
        $builder->where('pk.id_penanggungjawab', $userId);
        $builder->orWhere('pk.created_by', $userId);
        $builder->where('pk.status !=', 'selesai');
        $builder->orderBy('pk.prioritas', 'DESC');
        $builder->orderBy('pk.tanggal_mulai', 'ASC');
        $builder->limit(5);
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Soft delete user
     */
    public function softDelete($userId)
    {
        return $this->update($userId, [
            'status_akun' => 'nonaktif',
            'deleted_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
            'updated_by'  => session()->get('id') ?? 1
        ]);
    }
    
    public function restore($userId)
    {
        return $this->update($userId, [
            'deleted_at'  => null,
            'status_akun' => 'aktif',
            'updated_at'  => date('Y-m-d H:i:s'),
            'updated_by'  => session()->get('id') ?? 1
        ]);
    }
}