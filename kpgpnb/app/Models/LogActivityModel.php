<?php

namespace App\Models;

use CodeIgniter\Model;

class LogActivityModel extends Model
{
    protected $table = 'log_activities';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'user_id', 'user_name', 'module', 'activity_type', 
        'description', 'ip_address', 'user_agent', 'data_before', 
        'data_after', 'created_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';
    
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    /**
     * Get logs dengan filter
     */
    public function getFilteredLogs($filters = [], $perPage = 20, $page = 1)
    {
        $builder = $this->db->table($this->table . ' la');
        $builder->select('la.*, u.fullname as user_fullname');
        $builder->join('users u', 'u.id = la.user_id', 'left');
        
        // Apply filters
        if (!empty($filters['user_id'])) {
            $builder->where('la.user_id', $filters['user_id']);
        }
        
        if (!empty($filters['activity_type'])) {
            $builder->where('la.activity_type', $filters['activity_type']);
        }
        
        if (!empty($filters['module'])) {
            $builder->where('la.module', $filters['module']);
        }
        
        $builder->orderBy('la.created_at', 'DESC');
        
        // Pagination
        if ($perPage > 0) {
            $offset = ($page - 1) * $perPage;
            $builder->limit($perPage, $offset);
        }
        
        $query = $builder->get();
        return $query->getResultArray();
    }

    /**
     * Count filtered logs
     */
    public function countFilteredLogs($filters = [])
    {
        $builder = $this->db->table($this->table . ' la');
        
        // Apply filters (sama seperti di atas)
        if (!empty($filters['user_id'])) {
            $builder->where('la.user_id', $filters['user_id']);
        }
        
        if (!empty($filters['activity_type'])) {
            $builder->where('la.activity_type', $filters['activity_type']);
        }
        
        if (!empty($filters['module'])) {
            $builder->where('la.module', $filters['module']);
        }
        
        return $builder->countAllResults();
    }

    /**
     * Get logs hari ini
     */
    public function countTodayLogs()
    {
        return $this->where('DATE(created_at)', date('Y-m-d'))->countAllResults();
    }

    /**
     * Get jumlah user aktif
     */
    public function countActiveUsers()
    {
        $builder = $this->db->table($this->table);
        $builder->select('COUNT(DISTINCT user_id) as total');
        $builder->where('DATE(created_at)', date('Y-m-d'));
        $query = $builder->get();
        $result = $query->getRowArray();
        
        return $result ? $result['total'] : 0;
    }

    /**
     * Get top activities
     */
    public function getTopActivities($limit = 5)
    {
        $builder = $this->db->table($this->table);
        $builder->select('activity_type, COUNT(*) as total');
        $builder->groupBy('activity_type');
        $builder->orderBy('total', 'DESC');
        $builder->limit($limit);
        $query = $builder->get();
        
        return $query->getResultArray();
    }

    /**
     * Search logs
     */
    public function searchLogs($keyword, $limit = 20)
    {
        $builder = $this->db->table($this->table . ' la');
        $builder->select('la.*, u.fullname as user_fullname');
        $builder->join('users u', 'u.id = la.user_id', 'left');
        
        $builder->groupStart()
            ->like('la.user_name', $keyword)
            ->orLike('la.module', $keyword)
            ->orLike('la.activity_type', $keyword)
            ->orLike('la.description', $keyword)
            ->orLike('u.fullname', $keyword)
            ->groupEnd();
        
        $builder->orderBy('la.created_at', 'DESC');
        $builder->limit($limit);
        
        $query = $builder->get();
        return $query->getResultArray();
    }

    /**
     * Get related logs
     */
    public function getRelatedLogs($userId, $module, $limit = 5)
    {
        $builder = $this->db->table($this->table);
        
        $builder->where('user_id', $userId)
                ->where('module', $module)
                ->orderBy('created_at', 'DESC')
                ->limit($limit);
        
        $query = $builder->get();
        return $query->getResultArray();
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats($period = 'today')
    {
        $builder = $this->db->table($this->table);
        
        switch ($period) {
            case 'today':
                $builder->where('DATE(created_at)', date('Y-m-d'));
                break;
            case 'week':
                $builder->where('YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)');
                break;
            case 'month':
                $builder->where('MONTH(created_at)', date('m'))
                        ->where('YEAR(created_at)', date('Y'));
                break;
            case 'year':
                $builder->where('YEAR(created_at)', date('Y'));
                break;
        }
        
        $total = $builder->countAllResults();
        
        // Get by activity type
        $builder->select('activity_type, COUNT(*) as count');
        $builder->groupBy('activity_type');
        $query = $builder->get();
        $byType = $query->getResultArray();
        
        // Get by module
        $builder2 = $this->db->table($this->table);
        
        switch ($period) {
            case 'today':
                $builder2->where('DATE(created_at)', date('Y-m-d'));
                break;
            case 'week':
                $builder2->where('YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)');
                break;
            case 'month':
                $builder2->where('MONTH(created_at)', date('m'))
                        ->where('YEAR(created_at)', date('Y'));
                break;
            case 'year':
                $builder2->where('YEAR(created_at)', date('Y'));
                break;
        }
        
        $builder2->select('module, COUNT(*) as count');
        $builder2->groupBy('module');
        $builder2->orderBy('count', 'DESC');
        $query2 = $builder2->get();
        $byModule = $query2->getResultArray();
        
        return [
            'total' => $total,
            'by_type' => $byType,
            'by_module' => $byModule,
            'period' => $period
        ];
    }
}