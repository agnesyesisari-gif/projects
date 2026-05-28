<?php

namespace App\Models;

use CodeIgniter\Model;

class ApprovalModel extends Model
{
    protected $table = 'approvals';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $allowedFields = [
        'title', 'description', 'type', 'related_id', 'related_table',
        'requester_id', 'requester_name', 'status', 'priority',
        'required_approval_level', 'approval_team',
        'approved_by', 'approved_at', 'approval_notes',
        'rejected_by', 'rejected_at', 'rejection_reason',
        'revised_by', 'revised_at', 'revision_notes', 'revision_deadline',
        'effective_date', 'metadata'
    ];

    /**
     * Get pending count for dashboard
     */
    public function getPendingCount($userRole = null, $userId = null)
    {
        $builder = $this->where('status', 'pending');

        if ($userRole != 'admin' && $userRole != 'super_admin') {
            // Filter berdasarkan otorisasi user
            $builder->groupStart()
                    ->where('requester_id', $userId)
                    ->orWhereIn('id', function($subquery) use ($userId) {
                        $subquery->select('approval_id')
                                ->from('approval_assignments')
                                ->where('user_id', $userId);
                    })
                    ->groupEnd();
        }

        return $builder->countAllResults();
    }

    /**
     * Get pending ibadah requests
     */
    public function getPendingIbadah($userRole = null, $userId = null, $limit = 5)
    {
        $builder = $this->where('status', 'pending')
                        ->where('type', 'ibadah')
                        ->orderBy('created_at', 'DESC');

        if ($userRole != 'admin' && $userRole != 'super_admin') {
            $builder->groupStart()
                    ->where('requester_id', $userId)
                    ->orWhereIn('id', function($subquery) use ($userId) {
                        $subquery->select('approval_id')
                                ->from('approval_assignments')
                                ->where('user_id', $userId);
                    })
                    ->groupEnd();
        }

        return $builder->limit($limit)->findAll();
    }

    /**
     * Get pending program kerja requests
     */
    public function getPendingProgram($userRole = null, $userId = null, $limit = 5)
    {
        $builder