<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Loadhelper extends BaseController
{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->loadGlobalHelpers();
    }

    protected function loadGlobalHelpers()
    {
        helper(['url', 'form', 'cookie', 'security', 'text']);
    }

    protected function loadAdditionalHelpers($helpers = [])
    {
        if (!empty($helpers)) {
            helper($helpers);
        }
    }

    protected function checkLogin()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        return true;
    }

    protected function checkRole($allowedRoles = [])
    {
        $role = session()->get('role');
        if (!in_array($role, (array) $allowedRoles)) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini.');
            return redirect()->to('/dashboard');
        }
        return true;
    }

    protected function renderView($view, $data = [])
    {
        return view($view, $data);
    }

    protected function logActivity($description, $module = '')
    {
        $logModel = new \App\Models\LogActivityModel();
        $logModel->insert([
            'user_id'     => session()->get('user_id'),
            'description' => $description,
            'module'      => $module,
            'ip_address'  => $this->request->getIPAddress(),
            'created_at'  => date('Y-m-d H:i:s'),
        ]);
    }
}
