<?php

namespace App\Libraries;

use App\Models\UserModel;

class AuthLibrary
{
    protected $userModel;
    protected $session;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = \Config\Services::session();
    }
    
    public function isLoggedIn()
    {
        return $this->session->get('logged_in') === true;
    }
    
    public function isAdmin()
    {
        return $this->isLoggedIn() && $this->session->get('role') === 'admin';
    }
    
    public function isPendeta()
    {
        return $this->isLoggedIn() && $this->session->get('role') === 'pendeta';
    }
    
    public function isPengurus()
    {
        return $this->isLoggedIn() && $this->session->get('role') === 'pengurus';
    }
    
    public function isJemaat()
    {
        return $this->isLoggedIn() && $this->session->get('role') === 'jemaat';
    }
    
    public function hasPermission($requiredRole)
    {
        if (!$this->isLoggedIn()) {
            return false;
        }
        
        $userRole = $this->session->get('role');
        
        // Hierarchy: admin > pendeta > pengurus > jemaat
        $hierarchy = ['admin', 'pendeta', 'pengurus', 'jemaat'];
        
        $userLevel = array_search($userRole, $hierarchy);
        $requiredLevel = array_search($requiredRole, $hierarchy);
        
        return $userLevel <= $requiredLevel;
    }
    
    public function checkRememberMe()
    {
        $cookie = \Config\Services::request()->getCookie('remember_token');
        
        if ($cookie && !$this->isLoggedIn()) {
            $user = $this->userModel->where('remember_token', $cookie)
                                   ->where('remember_expires >', date('Y-m-d H:i:s'))
                                   ->first();
            
            if ($user) {
                $sessionData