<?php

namespace App\Libraries;

use App\Models\UserModel;
use App\Models\LogActivityModel;
use App\Entities\UserEntity;

class AuthLib
{
   
    protected $session;
    protected $request;
    protected $response;
    protected $userModel;
    protected $activityModel;

    protected $user;

    const ROLE_ADMIN    = 'admin';
    const ROLE_PENDETA  = 'pendeta';
    const ROLE_PENGURUS = 'pengurus';
    const ROLE_JEMAAT   = 'jemaat';
    const ROLE_GUEST    = 'guest';
    
    const PERM_MANAGE_USERS = 'manage_users';
    const PERM_MANAGE_IBADAH = 'manage_ibadah';
    const PERM_MANAGE_PROGRAM = 'manage_program';
    const PERM_VIEW_REPORTS = 'view_reports';
    const PERM_MANAGE_KEGIATAN = 'manage_kegiatan';
    const PERM_APPROVE_KEGIATAN = 'approve_kegiatan';

    
    private $rolePermissions = [
        self::ROLE_ADMIN => [
            self::PERM_MANAGE_IBADAH,
            self::PERM_MANAGE_PROGRAM,
            self::PERM_VIEW_REPORTS,
            self::PERM_MANAGE_KEGIATAN
        ],
        self::ROLE_PENDETA => [
            self::PERM_MANAGE_IBADAH,
            self::PERM_MANAGE_KEGIATAN,
            self::PERM_APPROVE_KEGIATAN,
            self::PERM_VIEW_REPORTS
        ],
        self::ROLE_PENGURUS => [
            self::PERM_MANAGE_KEGIATAN,
            self::PERM_VIEW_REPORTS
        ],
        self::ROLE_JEMAAT => [
            self::PERM_VIEW_REPORTS
        ],
        self::ROLE_GUEST => []
    ];

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->userModel    = new UserModel();
        $this->activityModel = new LogActivityModel();
        
        $this->checkUser();
    }

    public function isLoggedIn(): bool
    {
        return $this->session->get('logged_in') === true;
    }

    private function checkUser(): void
    {
        if ($this->session->get('logged_in')) {
            $userId = $this->session->get('user_id');
            if ($userId) {
                $this->user = $this->userModel->find($userId);
            }
        }
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getUserId(): ?int
    {
        return $this->user ? (int)$this->user['id'] : null;
    }

    public function getUserRole(): string
    {
        return $this->user ? ($this->user['role'] ?? self::ROLE_GUEST) : self::ROLE_GUEST;
    }

    public function hasRole(string $role): bool
    {
        return $this->getUserRole() === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        $userRole = $this->getUserRole();
        return in_array($userRole, $roles);
    }

    public function can(string $permission): bool
    {
        $role = $this->getUserRole();
        
        if (!isset($this->rolePermissions[$role])) {
            return false;
        }
        
        return in_array($permission, $this->rolePermissions[$role]);
    }

    public function canMultiple(array $permissions, bool $requireAll = false): bool
    {
        if ($requireAll) {
            foreach ($permissions as $permission) {
                if (!$this->can($permission)) {
                    return false;
                }
            }
            return true;
        }
        
        foreach ($permissions as $permission) {
            if ($this->can($permission)) {
                return true;
            }
        }
        
        return false;
    }

    public function attemptLogin(string $username, string $password, bool $remember = false): bool
    {
        $user = $this->userModel
            ->where('username', $username)
            ->orWhere('email', $username)
            ->where('status_akun', 'aktif')
            ->first();
        
        if (!$user) {
            $this->logActivity(null, 'login_failed', 'User not found: ' . $username);
            return false;
        }
        
        if (!password_verify($password, $user['password'])) {
            $this->logActivity($user['id'], 'login_failed', 'Invalid password');
            return false;
        }
        
        $this->userModel->update($user['id'], [
            'last_login'      => date('Y-m-d H:i:s'),
            'login_attempts'  => 0
        ]);
        
        $this->setUserSession($user);
        
        $this->logActivity($user['id'], 'login_success', 'User logged in');
        
        return true;
    }

    public function register(array $data)
    {
        // Set default role for registration
        if (!isset($data['role'])) {
            $data['role'] = self::ROLE_JEMAAT;
        }
        
        // Hash password
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        // Set default values
        $data['is_active'] = 0; // Need admin approval
        $data['email_verified'] = 0;
        $data['created_at'] = date('Y-m-d H:i:s');
        
        try {
            $userId = $this->userModel->insert($data);
            
            if ($userId) {
                $this->logActivity($userId, 'user_registered', 'New user registration');
                
                // Send verification email if email provided
                if (isset($data['email']) && filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $this->sendVerificationEmail($userId, $data['email']);
                }
                
                return $userId;
            }
        } catch (\Exception $e) {
            log_message('error', 'Registration failed: ' . $e->getMessage());
        }
        
        return false;
    }

    public function logout(): void
    {
        if ($this->user) {
            $this->logActivity($this->user['id'], 'logout', 'User logged out');
        }
        
        $this->session->destroy();
    }

    public function changePassword(int $userId, string $currentPassword, string $newPassword): bool
    {
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            return false;
        }
        
        if (!password_verify($currentPassword, $user['password'])) {
            return false;
        }
        
        $updated = $this->userModel->update($userId, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);
        
        if ($updated) {
            $this->logActivity($userId, 'password_changed', 'Password changed successfully');
            return true;
        }
        
        return false;
    }

    public function resetPassword(string $email): bool
    {
        // Password reset functionality disabled
        return false;
    }

    public function updateProfile(int $userId, array $data): bool
    {
        unset($data['password'], $data['role'], $data['status_akun']);
        
        $updated = $this->userModel->update($userId, $data);
        
        if ($updated) {
            $this->logActivity($userId, 'profile_updated', 'Profile updated');
            return true;
        }
        
        return false;
    }

    private function setUserSession($user): void
    {
        $sessionData = [
            'user_id'   => $user['id'],
            'username'  => $user['username'],
            'email'     => $user['email'],
            'role'      => $user['role'],
            'nama'      => $user['nama'] ?? $user['username'],
            'logged_in' => true
        ];
        
        $this->session->set($sessionData);
    }

    public function logActivity(?int $userId, string $action, string $description, array $data = []): void
    {
        $activityData = [
            'user_id'     => $userId,
            'action'      => $action,
            'description' => $description,
            'ip_address'  => $this->request->getIPAddress(),
            'user_agent'  => $this->request->getUserAgent()->getAgentString(),
            'data'        => json_encode($data),
            'created_at'  => date('Y-m-d H:i:s')
        ];
        
        $this->activityModel->insert($activityData);
    }

    public function getUserActivities(int $userId, int $limit = 50): array
    {
        return $this->activityModel->where('user_id', $userId)
                                  ->orderBy('created_at', 'DESC')
                                  ->limit($limit)
                                  ->findAll();
    }

    private function sendVerificationEmail(int $userId, string $email): bool
    {
        // Email verification disabled
        return false;
    }

    private function sendPasswordResetEmail(string $email, string $token): bool
    {
        // Password reset email disabled
        return false;
    }

    public function getAllRoles(): array
    {
        return [
            self::ROLE_ADMIN => 'Admin Gereja',
            self::ROLE_PENDETA => 'Pendeta',
            self::ROLE_PENGURUS => 'Pengurus',
            self::ROLE_JEMAAT => 'Jemaat',
            self::ROLE_GUEST => 'Tamu'
        ];
    }

    public function getRolePermissions(string $role): array
    {
        return $this->rolePermissions[$role] ?? [];
    }

    public function canManageIbadah(): bool
    {
        return $this->can(self::PERM_MANAGE_IBADAH);
    }

    public function canManageProgram(): bool
    {
        return $this->can(self::PERM_MANAGE_PROGRAM);
    }

    public function canManageKegiatan(): bool
    {
        return $this->can(self::PERM_MANAGE_KEGIATAN);
    }

    public function canViewReports(): bool
    {
        return $this->can(self::PERM_VIEW_REPORTS);
    }
}