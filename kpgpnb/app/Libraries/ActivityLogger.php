<?php

namespace App\Libraries;

use App\Models\LogActivityModel;

class ActivityLogger
{
    protected $logModel;
    
    public function __construct()
    {
        $this->logModel = new LogActivityModel();
    }
    
    /**
     * Log aktivitas
     */
    public function log($userId, $module, $activityType, $description, $dataBefore = null, $dataAfter = null)
    {
        $user = auth()->user();
        
        $data = [
            'user_id' => $userId,
            'user_name' => $user ? $user->username : 'System',
            'module' => $module,
            'activity_type' => $activityType,
            'description' => $description,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->logModel->insert($data);
    }
    
    /**
     * Log create
     */
    public function logCreate($userId, $module, $itemName, $dataAfter = null)
    {
        return $this->log(
            $userId,
            $module,
            'create',
            "Menambahkan {$module}: {$itemName}",
            null,
            $dataAfter
        );
    }
    
    /**
     * Log update
     */
    public function logUpdate($userId, $module, $itemName, $dataBefore, $dataAfter)
    {
        return $this->log(
            $userId,
            $module,
            'update',
            "Mengubah {$module}: {$itemName}",
            $dataBefore,
            $dataAfter
        );
    }
    
    /**
     * Log delete
     */
    public function logDelete($userId, $module, $itemName, $dataBefore = null)
    {
        return $this->log(
            $userId,
            $module,
            'delete',
            "Menghapus {$module}: {$itemName}",
            $dataBefore,
            null
        );
    }
    
    /**
     * Log login
     */
    public function logLogin($userId, $username)
    {
        return $this->log(
            $userId,
            'auth',
            'login',
            "Login ke sistem",
            null,
            ['username' => $username]
        );
    }
    
    /**
     * Log logout
     */
    public function logLogout($userId, $username)
    {
        return $this->log(
            $userId,
            'auth',
            'logout',
            "Logout dari sistem",
            null,
            ['username' => $username]
        );
    }
}