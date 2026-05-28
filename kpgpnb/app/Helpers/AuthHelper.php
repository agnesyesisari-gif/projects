<?php

namespace App\Helpers;

use App\Models\ActivityLogModel;

class AuthHelper
{
    public function setUserSession($user)
    {
        $sessionData = [
            'id' => $user['id'],
            'nama_lengkap' => $user['nama_lengkap'],
            'email' => $user['email'],
            'role' => $user['role'],
            'isLoggedIn' => true,
            'login_time' => time()
        ];

        session()->set($sessionData);
    }

    public function redirectByRole($role)
    {
        switch ($role) {
            case 'admin':
                return redirect()->to('/admin/dashboard');
            case 'pendeta':
                return redirect()->to('/pendeta/dashboard');
            case 'sekretaris':
                return redirect()->to('/sekretaris/dashboard');
            case 'bendahara':
                return redirect()->to('/bendahara/dashboard');
            case 'pemusik':
                return redirect()->to('/pemusik/dashboard');
            case 'jemaat':
            default:
                return redirect()->to('/dashboard');
        }
    }

    public function isLoggedIn()
    {
        return session()->get('isLoggedIn');
    }

    public function checkRole($role)
    {
        $userRole = session()->get('role');
        if (is_array($role)) {
            return in_array($userRole, $role);
        }
        return $userRole === $role;
    }

    public function restrictAccess($allowedRoles)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('/login');
        }

        if (!$this->checkRole($allowedRoles)) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini.');
            return redirect()->to('/dashboard');
        }
    }

    public function logActivity($userId, $action, $description)
    {
        $activityLogModel = new ActivityLogModel();
        
        $data = [
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'ip_address' => $this->getClientIP(),
            'user_agent' => $this->getUserAgent(),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $activityLogModel->insert($data);
    }

    public function sendResetEmail($email, $token)
    {
        // Reset password email functionality disabled
        return false;
    }

    private function getClientIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    private function getUserAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    }

    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    public function getUserData()
    {
        if (!$this->isLoggedIn()) {
            return null;
        }

        return [
            'id' => session()->get('id'),
            'nama_lengkap' => session()->get('nama_lengkap'),
            'email' => session()->get('email'),
            'role' => session()->get('role')
        ];
    }
}