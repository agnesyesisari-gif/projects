<?php

namespace App\Models;

use CodeIgniter\Model;

class PasswordHistoryModel extends Model
{
    protected $table = 'password_history';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $createdField = 'changed_at';
    protected $updatedField = '';
    protected $allowedFields = [
        'user_id', 'password_hash', 'changed_at', 'changed_by',
        'ip_address', 'user_agent', 'is_forced_change'
    ];

    /**
     * Get last password change for user
     */
    public function getLastChange($userId)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('changed_at', 'DESC')
                    ->first();
    }

    /**
     * Get user password history
     */
    public function getUserHistory($userId, $perPage = 20, $page = 1)
    {
        return $this->select('password_history.*, users.username, users.email')
                    ->join('users', 'users.id = password_history.changed_by', 'left')
                    ->where('password_history.user_id', $userId)
                    ->orderBy('password_history.changed_at', 'DESC')
                    ->paginate($perPage, 'default', $page);
    }

    /**
     * Check if password was used before
     */
    public function wasPasswordUsed($userId, $passwordHash, $lastN = 5)
    {
        $history = $this->where('user_id', $userId)
                        ->orderBy('changed_at', 'DESC')
                        ->limit($lastN)
                        ->findAll();

        foreach ($history as $record) {
            if ($record['password_hash'] === $passwordHash) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get password change frequency
     */
    public function getChangeFrequency($userId)
    {
        $changes = $this->where('user_id', $userId)
                        ->orderBy('changed_at', 'ASC')
                        ->findAll();

        if (count($changes) < 2) {
            return null;
        }

        $intervals = [];
        for ($i = 1; $i < count($changes); $i++) {
            $interval = strtotime($changes[$i]['changed_at']) - strtotime($changes[$i-1]['changed_at']);
            $intervals[] = $interval;
        }

        return [
            'count' => count($changes),
            'avg_interval_days' => array_sum($intervals) / count($intervals) / (60 * 60 * 24),
            'last_change' => end($changes)['changed_at']
        ];
    }
}