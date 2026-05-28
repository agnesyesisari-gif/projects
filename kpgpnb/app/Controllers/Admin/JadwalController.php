<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\JadwalModel\JadwalIbadahModel;

class JadwalController extends BaseController
{
    protected $jadwalModel;

    public function __construct()
    {
        $this->jadwalModel = new JadwalIbadahModel();
        helper(['form', 'url']);
    }

    private function checkAdmin()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }
        return null;
    }

    /**
     * Menampilkan jadwal mingguan
     */
    public function index()
    {
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');

        $data = [
            'title'           => 'Jadwal Ibadah Mingguan',
            'active_menu'     => 'jadwal',
            'tanggal_aktif'   => $tanggal,
            'jadwal_mingguan' => $this->jadwalModel->where('tanggal >=', $tanggal)->orderBy('tanggal', 'ASC')->findAll(),
            'hari_ini'        => date('Y-m-d'),
        ];

        return view('jadwal/index', $data);
    }

    /**
     * Menampilkan form tambah jadwal
     */
    public function tambah()
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $data = [
            'title'       => 'Tambah Jadwal Ibadah',
            'active_menu' => 'jadwal',
            'validation'  => \Config\Services::validation(),
        ];

        return view('jadwal/tambah', $data);
    }

    /**
     * Proses tambah jadwal
     */
    public function prosesTambah()
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $rules = [
            'tanggal'          => 'required|valid_date',
            'waktu'            => 'required',
            'jenis_ibadah_id'  => 'required',
            'pemimpin_ibadah_id' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'jenis_ibadah_id'    => $this->request->getPost('jenis_ibadah_id'),
            'tanggal'            => $this->request->getPost('tanggal'),
            'waktu'              => $this->request->getPost('waktu'),
            'tempat'             => $this->request->getPost('tempat'),
            'pemimpin_ibadah_id' => $this->request->getPost('pemimpin_ibadah_id'),
            'pemusik'            => $this->request->getPost('pemusik'),
            'pemandu_pujian'     => $this->request->getPost('pemandu_pujian'),
            'operator_LCD'       => $this->request->getPost('operator_LCD'),
            'operator_sound'     => $this->request->getPost('operator_sound'),
            'penatua'            => $this->request->getPost('penatua'),
            'diaken'             => $this->request->getPost('diaken'),
            'tema'               => $this->request->getPost('tema'),
            'bacaan_alkitab'     => $this->request->getPost('bacaan_alkitab'),
            'keterangan'         => $this->request->getPost('keterangan'),
            'created_at'         => date('Y-m-d H:i:s'),
            'created_by'         => session()->get('user_id'),
        ];

        if ($this->jadwalModel->save($data)) {
            return redirect()->to('/jadwal')->with('success', 'Jadwal berhasil ditambahkan');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan jadwal');
    }

    /**
     * Menampilkan form edit jadwal
     */
    public function edit($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $jadwal = $this->jadwalModel->find($id);
        if (!$jadwal) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title'      => 'Edit Jadwal Ibadah',
            'active_menu'=> 'jadwal',
            'jadwal'     => $jadwal,
            'validation' => \Config\Services::validation(),
        ];

        return view('jadwal/edit', $data);
    }

    /**
     * Proses edit jadwal
     */
    public function prosesEdit($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $rules = [
            'tanggal'            => 'required|valid_date',
            'waktu'              => 'required',
            'jenis_ibadah_id'    => 'required',
            'pemimpin_ibadah_id' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'jenis_ibadah_id'    => $this->request->getPost('jenis_ibadah_id'),
            'tanggal'            => $this->request->getPost('tanggal'),
            'waktu'              => $this->request->getPost('waktu'),
            'tempat'             => $this->request->getPost('tempat'),
            'pemimpin_ibadah_id' => $this->request->getPost('pemimpin_ibadah_id'),
            'pemusik'            => $this->request->getPost('pemusik'),
            'pemandu_pujian'     => $this->request->getPost('pemandu_pujian'),
            'operator_LCD'       => $this->request->getPost('operator_LCD'),
            'operator_sound'     => $this->request->getPost('operator_sound'),
            'penatua'            => $this->request->getPost('penatua'),
            'diaken'             => $this->request->getPost('diaken'),
            'tema'               => $this->request->getPost('tema'),
            'keterangan'         => $this->request->getPost('keterangan'),
            'updated_at'         => date('Y-m-d H:i:s'),
            'updated_by'         => session()->get('user_id'),
        ];

        if ($this->jadwalModel->update($id, $data)) {
            return redirect()->to('/jadwal')->with('success', 'Jadwal berhasil diupdate');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal mengupdate jadwal');
    }

    /**
     * Hapus jadwal
     */
    public function hapus($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        if ($this->jadwalModel->delete($id)) {
            return redirect()->to('/jadwal')->with('success', 'Jadwal berhasil dihapus');
        }

        return redirect()->to('/jadwal')->with('error', 'Gagal menghapus jadwal');
    }

    /**
     * API untuk mobile app
     */
    public function apiJadwal()
    {
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');
        $jadwal  = $this->jadwalModel->where('tanggal >=', $tanggal)->orderBy('tanggal', 'ASC')->findAll();

        return $this->response->setJSON([
            'status'  => 'success',
            'data'    => $jadwal,
            'tanggal' => $tanggal,
        ]);
    }
}