<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (!$session->get('logged_in')) {
            $session->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to('/login');
        }

        $userRole = $session->get('role');

        if ($userRole !== 'admin' && $userRole !== 'super_admin') {
            $session->setFlashdata('error', 'Hanya administrator yang dapat mengakses halaman ini!');
            return redirect()->to('/dashboard');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}