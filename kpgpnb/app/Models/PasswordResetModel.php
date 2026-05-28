<?php

namespace App\Models;

use CodeIgniter\Model;

class PasswordResetModel extends Model
{
    protected $table = 'password_resets';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'used_at';
    protected $allowedFields = [
        'user_id', 'expires_at', 'used', 'used_at',
        'ip_address', 'user_agent'
    ];

    /**
     * Password reset functionality disabled
     */
    public function cleanExpiredTokens()
    {
        return true;
    }

    /**
     * Get valid token - DISABLED
     */
    public function getValidToken($token)
    {
        return null;
    }
}