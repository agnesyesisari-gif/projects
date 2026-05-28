<?php

namespace App\Controllers;

use App\Models\JadwalModel\JadwalIbadahModel;
use App\Models\KegiatanModel;
use App\Models\ProkerModel\ProkerModel;

class CalendarController extends BaseController
{
    protected $jadwalModel;
    protected $kegiatanModel;
    protected $prokerModel;
    protected $helpers = ['form', 'url'];

    public function __construct()
    {
        $this->jadwalModel   = new JadwalIbadahModel();
        $this->kegiatanModel = new KegiatanModel();
        $this->prokerModel   = new ProkerModel();
    }

    public function index()
    {
        $month = $this->request->getGet('month') ?? date('n');
        $year  = $this->request->getGet('year') ?? date('Y');

        $jadwal = $this->jadwalModel
            ->where('MONTH(tanggal)', $month)
            ->where('YEAR(tanggal)', $year)
            ->orderBy('tanggal', 'ASC')
            ->findAll();

        $data = [
            'title'      => 'Kalender Kegiatan Gereja',
            'activeMenu' => 'calendar',
            'month'      => $month,
            'year'       => $year,
            'jadwal'     => $jadwal,
            'validation' => \Config\Services::validation()
        ];

        return view('calendar/index', $data);
    }

    public function getEvents()
    {
        $start = $this->request->getGet('start');
        $end   = $this->request->getGet('end');

        $jadwal = $this->jadwalModel
            ->where('tanggal >=', $start)
            ->where('tanggal <=', $end)
            ->findAll();

        $events = [];
        foreach ($jadwal as $item) {
            $events[] = [
                'id'    => $item['id'],
                'title' => $item['nama_ibadah'] ?? $item['tema'] ?? 'Ibadah',
                'start' => $item['tanggal'],
            ];
        }

        return $this->response->setJSON($events);
    }

    public function detail($id)
    {
        $event = $this->jadwalModel->find($id);

        if (!$event) {
            return redirect()->to('/calendar')->with('error', 'Kegiatan tidak ditemukan.');
        }

        $data = [
            'title'      => 'Detail Kegiatan',
            'activeMenu' => 'calendar',
            'event'      => $event,
        ];

        return view('calendar/detail', $data);
    }

    public function apiThisWeek()
    {
        $start = date('Y-m-d', strtotime('monday this week'));
        $end   = date('Y-m-d', strtotime('sunday this week'));

        $events = $this->jadwalModel
            ->where('tanggal >=', $start)
            ->where('tanggal <=', $end)
            ->findAll();

        return $this->response->setJSON(['success' => true, 'data' => $events]);
    }

    public function apiToday()
    {
        $events = $this->jadwalModel->where('tanggal', date('Y-m-d'))->findAll();

        return $this->response->setJSON(['success' => true, 'data' => $events]);
    }

    private function getMonthName($month)
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $months[$month] ?? '';
    }
}
