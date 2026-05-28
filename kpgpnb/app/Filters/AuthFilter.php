<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (!$session->get('logged_in')) {
            $session->setFlashdata('redirect_url', current_url());
            $session->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to('/login');
        }

        // Cek role jika ada argument
        if (!empty($arguments)) {
            $userRole = $session->get('role');
            if (!in_array($userRole, $arguments)) {
                $session->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini!');
                return redirect()->to('/dashboard');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
