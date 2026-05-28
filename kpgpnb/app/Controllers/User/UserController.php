<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\KegiatanModel;
use App\Models\JadwalModel\JadwalIbadahModel;
use App\Models\ProkerModel\ProkerModel;

class UserController extends BaseController
{
    protected $userModel;
    protected $kegiatanModel;
    protected $jadwalModel;
    protected $prokerModel;

    public function __construct()
    {
        $this->userModel    = new UserModel();
        $this->kegiatanModel = new KegiatanModel();
        $this->jadwalModel  = new JadwalIbadahModel();
        $this->prokerModel  = new ProkerModel();

        if (!session()->get('logged_in')) {
            redirect()->to('/login');
        }
    }

    public function index()
    {
        $data = [
            'title'          => 'Dashboard User',
            'jadwal_ibadah'  => $this->jadwalModel->where('tanggal >=', date('Y-m-d'))->orderBy('tanggal', 'ASC')->limit(5)->findAll(),
            'kegiatan'       => $this->kegiatanModel->orderBy('created_at', 'DESC')->limit(5)->findAll(),
            'program_aktif'  => $this->prokerModel->findAll(),
        ];

        return view('User/dashboard', $data);
    }

    public function jadwalIbadah()
    {
        $data = [
            'title'         => 'Jadwal Ibadah',
            'jadwal_ibadah' => $this->jadwalModel->orderBy('tanggal', 'ASC')->findAll(),
        ];

        return view('User/jadwal_ibadah', $data);
    }

    public function kegiatan()
    {
        $data = [
            'title'    => 'Kegiatan Pelayanan',
            'kegiatan' => $this->kegiatanModel->findAll(),
        ];

        return view('User/kegiatan', $data);
    }

    public function programKerja()
    {
        $data = [
            'title'        => 'Program Kerja',
            'program_kerja' => $this->prokerModel->findAll(),
        ];

        return view('User/program_kerja', $data);
    }

    public function profil()
    {
        $userId = session()->get('user_id');
        $data = [
            'title' => 'Profil User',
            'user'  => $this->userModel->find($userId),
        ];

        return view('User/profil', $data);
    }
}
