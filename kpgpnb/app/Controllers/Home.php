<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\JadwalModel\JadwalIbadahModel;
use App\Models\ProkerModel\ProkerModel;
use App\Models\KegiatanModel;
use App\Models\LogActivityModel;

class Home extends BaseController
{
    protected $userModel;
    protected $jadwalModel;
    protected $prokerModel;
    protected $kegiatanModel;
    protected $logModel;

    public function __construct()
    {
        $this->userModel    = new UserModel();
        $this->jadwalModel  = new JadwalIbadahModel();
        $this->prokerModel  = new ProkerModel();
        $this->kegiatanModel = new KegiatanModel();
        $this->logModel     = new LogActivityModel();
    }

    /**
     * Halaman utama — redirect ke dashboard atau login
     */
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $userRole = session()->get('role');
        $userId   = session()->get('user_id');

        $data = [
            'title'      => 'Dashboard - Sistem Informasi Gereja',
            'activeMenu' => 'dashboard',
            'userRole'   => $userRole,
            'pageTitle'  => 'Dashboard',
        ];

        if ($userRole === 'admin') {
            $data['totalUsers']    = $this->userModel->where('deleted_at', null)->countAllResults();
            $data['totalProker']   = $this->prokerModel->countAll();
            $data['totalKegiatan'] = $this->kegiatanModel->countAll();
            $data['jadwalMendatang'] = $this->jadwalModel
                ->where('tanggal >=', date('Y-m-d'))
                ->orderBy('tanggal', 'ASC')
                ->limit(5)
                ->findAll();
        } else {
            $data['jadwalMendatang'] = $this->jadwalModel
                ->where('tanggal >=', date('Y-m-d'))
                ->orderBy('tanggal', 'ASC')
                ->limit(5)
                ->findAll();
            $data['kegiatanTerbaru'] = $this->kegiatanModel
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->findAll();
        }

        return view('User/dashboard', $data);
    }
}
