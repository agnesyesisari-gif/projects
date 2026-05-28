<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LaporanModel;
use App\Models\ProkerModel\ProkerModel;

class Laporan extends BaseController
{
    protected $laporanModel;
    protected $prokerModel;

    public function __construct()
    {
        $this->laporanModel = new LaporanModel();
        $this->prokerModel  = new ProkerModel();

        helper(['form', 'url']);

        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
    }

    /**
     * Daftar laporan
     */
    public function index()
    {
        $data = [
            'title'   => 'Daftar Laporan Program Kerja',
            'laporan' => $this->laporanModel->findAll(),
        ];

        return view('laporan/index', $data);
    }

    /**
     * Detail laporan
     */
    public function detail($id)
    {
        $laporan = $this->laporanModel->find($id);

        if (!$laporan) {
            return redirect()->to('/laporan')->with('error', 'Laporan tidak ditemukan');
        }

        $data = [
            'title'   => 'Detail Laporan: ' . $laporan['judul_laporan'],
            'laporan' => $laporan,
        ];

        return view('laporan/detail', $data);
    }

    /**
     * Form tambah laporan
     */
    public function tambah()
    {
        $data = [
            'title'      => 'Buat Laporan Baru',
            'proker'     => $this->prokerModel->findAll(),
            'kategori'   => \Config\Database::connect()->table('kategori_laporan')->get()->getResultArray(),
            'validation' => \Config\Services::validation(),
        ];

        return view('laporan/tambah', $data);
    }

    /**
     * Simpan laporan baru
     */
    public function simpan()
    {
        $rules = [
            'id_proker'       => 'required',
            'id_kategori'     => 'required',
            'judul_laporan'   => 'required',
            'tanggal_laporan' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $dataLaporan = [
            'id_proker'          => $this->request->getPost('id_proker'),
            'id_kategori'        => $this->request->getPost('id_kategori'),
            'judul_laporan'      => $this->request->getPost('judul_laporan'),
            'deskripsi_laporan'  => $this->request->getPost('deskripsi_laporan'),
            'tanggal_laporan'    => $this->request->getPost('tanggal_laporan'),
            'periode_mulai'      => $this->request->getPost('periode_mulai'),
            'periode_selesai'    => $this->request->getPost('periode_selesai'),
            'pencapaian'         => $this->request->getPost('pencapaian'),
            'kendala'            => $this->request->getPost('kendala'),
            'anggaran_rencana'   => $this->request->getPost('anggaran_rencana'),
            'anggaran_realisasi' => $this->request->getPost('anggaran_realisasi'),
            'created_by'         => session()->get('nama'),
        ];

        if ($this->laporanModel->save($dataLaporan)) {
            $laporanId = $this->laporanModel->getInsertID();
            return redirect()->to('/laporan/detail/' . $laporanId)->with('success', 'Laporan berhasil dibuat');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal membuat laporan');
    }

    /**
     * Form edit laporan
     */
    public function edit($id)
    {
        $laporan = $this->laporanModel->find($id);

        if (!$laporan) {
            return redirect()->to('/laporan')->with('error', 'Laporan tidak ditemukan');
        }

        $data = [
            'title'      => 'Edit Laporan: ' . $laporan['judul_laporan'],
            'laporan'    => $laporan,
            'proker'     => $this->prokerModel->findAll(),
            'kategori'   => \Config\Database::connect()->table('kategori_laporan')->get()->getResultArray(),
            'validation' => \Config\Services::validation(),
        ];

        return view('laporan/edit', $data);
    }

    /**
     * Update laporan
     */
    public function update($id)
    {
        $rules = [
            'judul_laporan'   => 'required',
            'tanggal_laporan' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $dataUpdate = [
            'judul_laporan'      => $this->request->getPost('judul_laporan'),
            'deskripsi_laporan'  => $this->request->getPost('deskripsi_laporan'),
            'tanggal_laporan'    => $this->request->getPost('tanggal_laporan'),
            'periode_mulai'      => $this->request->getPost('periode_mulai'),
            'periode_selesai'    => $this->request->getPost('periode_selesai'),
            'pencapaian'         => $this->request->getPost('pencapaian'),
            'kendala'            => $this->request->getPost('kendala'),
            'anggaran_rencana'   => $this->request->getPost('anggaran_rencana'),
            'anggaran_realisasi' => $this->request->getPost('anggaran_realisasi'),
            'updated_by'         => session()->get('nama'),
        ];

        if ($this->laporanModel->update($id, $dataUpdate)) {
            return redirect()->to('/laporan/detail/' . $id)->with('success', 'Laporan berhasil diupdate');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal mengupdate laporan');
    }

    /**
     * Ajukan laporan
     */
    public function ajukan($id)
    {
        if ($this->laporanModel->update($id, ['status' => 'diajukan'])) {
            return redirect()->to('/laporan')->with('success', 'Laporan berhasil diajukan');
        }

        return redirect()->to('/laporan')->with('error', 'Gagal mengajukan laporan');
    }

    /**
     * Setujui laporan
     */
    public function setujui($id)
    {
        $data = [
            'status'         => 'disetujui',
            'disetujui_oleh' => session()->get('nama'),
            'catatan'        => $this->request->getPost('catatan'),
        ];

        if ($this->laporanModel->update($id, $data)) {
            return redirect()->to('/laporan')->with('success', 'Laporan berhasil disetujui');
        }

        return redirect()->to('/laporan')->with('error', 'Gagal menyetujui laporan');
    }

    /**
     * Tolak laporan
     */
    public function tolak($id)
    {
        $data = [
            'status'  => 'ditolak',
            'catatan' => $this->request->getPost('catatan'),
        ];

        if ($this->laporanModel->update($id, $data)) {
            return redirect()->to('/laporan')->with('success', 'Laporan berhasil ditolak');
        }

        return redirect()->to('/laporan')->with('error', 'Gagal menolak laporan');
    }

    /**
     * Hapus laporan
     */
    public function hapus($id)
    {
        if ($this->laporanModel->delete($id)) {
            return redirect()->to('/laporan')->with('success', 'Laporan berhasil dihapus');
        }

        return redirect()->to('/laporan')->with('error', 'Gagal menghapus laporan');
    }

    /**
     * Tambah realisasi anggaran
     */
    public function tambahRealisasi($idLaporan)
    {
        $data = [
            'id_laporan'         => $idLaporan,
            'item_pengeluaran'   => $this->request->getPost('item_pengeluaran'),
            'deskripsi'          => $this->request->getPost('deskripsi'),
            'jumlah'             => $this->request->getPost('jumlah'),
            'harga_satuan'       => $this->request->getPost('harga_satuan'),
            'total_biaya'        => (int)$this->request->getPost('jumlah') * (int)$this->request->getPost('harga_satuan'),
            'tanggal_pengeluaran'=> $this->request->getPost('tanggal_pengeluaran'),
            'keterangan'         => $this->request->getPost('keterangan'),
        ];

        $db = \Config\Database::connect();
        if ($db->table('realisasi_anggaran')->insert($data)) {
            return redirect()->to('/laporan/detail/' . $idLaporan)->with('success', 'Realisasi anggaran berhasil ditambah');
        }

        return redirect()->to('/laporan/detail/' . $idLaporan)->with('error', 'Gagal menambah realisasi anggaran');
    }

    /**
     * Statistik laporan
     */
    public function statistik()
    {
        $data = [
            'title' => 'Statistik Laporan',
        ];

        return view('laporan/statistik', $data);
    }
}
