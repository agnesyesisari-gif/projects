<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Pdf extends BaseController
{
    protected $dompdf;
    protected $tcpdf;
    
    public function __construct()
    {
        parent::__construct();
        
        // Load library PDF
        $this->loadLibraryPdf();
    }
    
    /**
     * Load library PDF (DOMPDF dan TCPDF)
     */
    private function loadLibraryPdf()
    {
        // Load DOMPDF
        if (!class_exists('Dompdf\Dompdf')) {
            require_once(APPPATH . 'ThirdParty/dompdf/autoload.inc.php');
        }
        
        // Load TCPDF
        if (!class_exists('TCPDF')) {
            require_once(APPPATH . 'ThirdParty/tcpdf/tcpdf.php');
        }
        
        // Inisialisasi DOMPDF
        $this->dompdf = new \Dompdf\Dompdf();
        
        // Set options DOMPDF
        $options = $this->dompdf->getOptions();
        $options->setDefaultFont('Arial');
        $options->setIsRemoteEnabled(true);
        $this->dompdf->setOptions($options);
    }
    
    /**
     * Generate PDF Jadwal Ibadah
     */
    public function jadwalIbadah($periode = 'bulan')
    {
        // Cek login
        if (!$this->checkLogin()) {
            return redirect()->to('/auth/login');
        }
        
        // Load model
        $worshipModel = new \App\Models\JadwalModel\JadwalIbadahModel();
        
        // Tentukan periode
        $startDate = '';
        $endDate = '';
        $titlePeriod = '';
        
        switch ($periode) {
            case 'minggu':
                $startDate = date('Y-m-d', strtotime('monday this week'));
                $endDate = date('Y-m-d', strtotime('sunday this week'));
                $titlePeriod = 'Minggu Ini (' . date('d M Y', strtotime($startDate)) . ' - ' . date('d M Y', strtotime($endDate)) . ')';
                break;
                
            case 'bulan':
                $startDate = date('Y-m-01');
                $endDate = date('Y-m-t');
                $titlePeriod = 'Bulan ' . $this->getMonthName(date('m')) . ' ' . date('Y');
                break;
                
            case 'tahun':
                $startDate = date('Y-01-01');
                $endDate = date('Y-12-31');
                $titlePeriod = 'Tahun ' . date('Y');
                break;
                
            default:
                if (preg_match('/^\d{4}-\d{2}$/', $periode)) {
                    $startDate = $periode . '-01';
                    $endDate = date('Y-m-t', strtotime($startDate));
                    $titlePeriod = 'Bulan ' . $this->getMonthName(date('m', strtotime($startDate))) . ' ' . date('Y', strtotime($startDate));
                } else {
                    $startDate = date('Y-m-01');
                    $endDate = date('Y-m-t');
                    $titlePeriod = 'Bulan ' . $this->getMonthName(date('m')) . ' ' . date('Y');
                }
                break;
        }
        
        // Get data jadwal ibadah
        $schedules = $worshipModel->where('tanggal >=', $startDate)->where('tanggal <=', $endDate)->findAll();
        
        // Data untuk PDF
        $data = [
            'title' => 'Jadwal Ibadah',
            'subtitle' => $titlePeriod,
            'schedules' => $schedules,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'generated_date' => date('d F Y H:i:s'),
            'generated_by' => session()->get('nama_lengkap') ?? 'System',
            'church_info' => $this->getChurchInfo()
        ];
        
        // Pilih metode PDF
        $method = $this->request->getGet('method') ?? 'dompdf';
        
        if ($method == 'tcpdf') {
            return $this->generatePdfTCPDF('jadwal_ibadah', $data, 'Laporan Jadwal Ibadah.pdf');
        } else {
            return $this->generatePdfDOMPDF('pdf/jadwal_ibadah', $data, 'Laporan Jadwal Ibadah.pdf');
        }
    }
    
    /**
     * Generate PDF Jadwal Ibadah Harian
     */
    public function jadwalHarian($date = null)
    {
        if (!$this->checkLogin()) {
            return redirect()->to('/auth/login');
        }
        
        $worshipModel = new \App\Models\JadwalModel\JadwalIbadahModel();
        
        $date = $date ?? date('Y-m-d');
        
        $schedules = $worshipModel->where('tanggal', $date)->findAll();
        
        $data = [
            'title' => 'Jadwal Ibadah Harian',
            'date' => $date,
            'date_indonesian' => $this->indonesianDate($date, true),
            'schedules' => $schedules,
            'generated_date' => date('d F Y H:i:s'),
            'generated_by' => session()->get('nama_lengkap') ?? 'System',
            'church_info' => $this->getChurchInfo()
        ];
        
        return $this->generatePdfDOMPDF('pdf/jadwal_harian', $data, 'Jadwal_Ibadah_' . $date . '.pdf');
    }
    
    /**
     * Generate PDF Program Kerja
     */
    public function programKerja($tahun = null, $komisi_id = null)
    {
        if (!$this->checkLogin()) {
            return redirect()->to('/auth/login');
        }
        
        $programModel = new \App\Models\ProkerModel\ProkerModel();
        
        $tahun = $tahun ?? date('Y');
        $filter = [
            'tahun' => $tahun,
            'komisi_id' => $komisi_id
        ];
        
        $programs = $programModel->findAll();
        
        // Get komisi name jika filter by komisi
        $komisiName = 'Semua Komisi';
        if ($komisi_id) {
            $komsModel = new \App\Models\ProkerModel\KomisiModel();
            $komisi = $komsModel->find($komisi_id);
            $komisiName = $komisi ? $komisi['nama_komisi'] : 'Komisi Tidak Ditemukan';
        }
        
        $data = [
            'title' => 'Program Kerja Gereja',
            'subtitle' => 'Tahun ' . $tahun . ' - ' . $komisiName,
            'programs' => $programs,
            'tahun' => $tahun,
            'komisi_name' => $komisiName,
            'total_programs' => count($programs),
            'total_anggaran' => array_sum(array_column($programs, 'anggaran')),
            'generated_date' => date('d F Y H:i:s'),
            'generated_by' => session()->get('nama_lengkap') ?? 'System',
            'church_info' => $this->getChurchInfo()
        ];
        
        return $this->generatePdfDOMPDF('pdf/program_kerja', $data, 'Program_Kerja_' . $tahun . '.pdf');
    }
    
    /**
     * Generate PDF Detail Program Kerja
     */
    public function detailProgram($id)
    {
        if (!$this->checkLogin()) {
            return redirect()->to('/auth/login');
        }
        
        $programModel = new \App\Models\ProkerModel\ProkerModel();
        
        $program = $programModel->find($id);
        
        if (!$program) {
            session()->setFlashdata('error', 'Program kerja tidak ditemukan.');
            return redirect()->back();
        }
        
        $data = [
            'title' => 'Detail Program Kerja',
            'program' => $program,
            'activities' => [],
            'generated_date' => date('d F Y H:i:s'),
            'generated_by' => session()->get('nama_lengkap') ?? 'System',
            'church_info' => $this->getChurchInfo(),
        ];
        
        return $this->generatePdfDOMPDF('pdf/detail_program', $data, 'Detail_Program_' . $program->kode_program . '.pdf');
    }
    
    /**
     * Generate PDF Laporan Kehadiran Ibadah
     */
    public function laporanKehadiran($start_date = null, $end_date = null)
    {
        if (!$this->checkLogin()) {
            return redirect()->to('/auth/login');
        }
        
        $kehadiranModel = new \App\Models\KehadiranModel();
        $jadwalModel    = new \App\Models\JadwalModel\JadwalIbadahModel();
        
        $start_date = $start_date ?? $this->request->getGet('start_date') ?? date('Y-m-01');
        $end_date   = $end_date ?? $this->request->getGet('end_date') ?? date('Y-m-t');
        
        $attendanceData  = $kehadiranModel->where('tanggal >=', $start_date)->where('tanggal <=', $end_date)->findAll();
        $worshipSchedules = $jadwalModel->where('tanggal >=', $start_date)->where('tanggal <=', $end_date)->findAll();
        
        $stats = [
            'total_services'    => count($worshipSchedules),
            'total_attendance'  => count($attendanceData),
            'average_attendance'=> count($worshipSchedules) > 0 ? round(count($attendanceData) / count($worshipSchedules), 2) : 0,
            'max_attendance'    => 0,
            'min_attendance'    => 0
        ];
        
        $data = [
            'title' => 'Laporan Kehadiran Ibadah',
            'subtitle' => 'Periode ' . $this->indonesianDate($start_date) . ' - ' . $this->indonesianDate($end_date),
            'attendance_data' => $attendanceData,
            'worship_schedules' => $worshipSchedules,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'stats' => $stats,
            'generated_date' => date('d F Y H:i:s'),
            'generated_by' => session()->get('nama_lengkap') ?? 'System',
            'church_info' => $this->getChurchInfo()
        ];
        
        return $this->generatePdfDOMPDF('pdf/laporan_kehadiran', $data, 'Laporan_Kehadiran_' . $start_date . '_' . $end_date . '.pdf');
    }
    
    /**
     * Generate PDF Anggaran Program Kerja
     */
    public function anggaranProgram($tahun = null)
    {
        if (!$this->checkLogin()) {
            return redirect()->to('/auth/login');
        }
        
        $programModel = new \App\Models\ProkerModel\ProkerModel();
        $komisiModel  = new \App\Models\ProkerModel\KomisiModel();
        
        $tahun = $tahun ?? date('Y');
        
        // Get all komisi
        $komisi = $komisiModel->findAll();
        $budgetData = [];
        
        foreach ($komisi as $koms) {
            $programs = $programModel->where('id_komisi', $koms['id_komisi'])->findAll();
            
            $totalBudget     = 0;
            $usedBudget      = 0;
            
            foreach ($programs as $program) {
                $totalBudget += $program['anggaran'] ?? 0;
                $usedBudget  += $program['realisasi_anggaran'] ?? 0;
            }
            
            $budgetData[] = [
                'nama_komisi'      => $koms['nama_komisi'],
                'total_budget'     => $totalBudget,
                'used_budget'      => $usedBudget,
                'remaining_budget' => $totalBudget - $usedBudget,
                'program_count'    => count($programs)
            ];
        }
        
        $data = [
            'title' => 'Laporan Anggaran Program Kerja',
            'subtitle' => 'Tahun ' . $tahun,
            'budget_data' => $budgetData,
            'tahun' => $tahun,
            'total_all_budget' => array_sum(array_column($budgetData, 'total_budget')),
            'total_all_used' => array_sum(array_column($budgetData, 'used_budget')),
            'total_all_remaining' => array_sum(array_column($budgetData, 'remaining_budget')),
            'generated_date' => date('d F Y H:i:s'),
            'generated_by' => session()->get('nama_lengkap') ?? 'System',
            'church_info' => $this->getChurchInfo()
        ];
        
        return $this->generatePdfDOMPDF('pdf/anggaran_program', $data, 'Anggaran_Program_' . $tahun . '.pdf');
    }
    
    /**
     * Generate PDF menggunakan DOMPDF
     */
    private function generatePdfDOMPDF($view, $data = [], $filename = 'document.pdf', $paper = 'A4', $orientation = 'portrait', $stream = true)
    {
        // Load view dengan data
        $html = view($view, $data);
        
        // Load HTML ke DOMPDF
        $this->dompdf->loadHtml($html);
        
        // Set paper size dan orientation
        $this->dompdf->setPaper($paper, $orientation);
        
        // Render PDF
        $this->dompdf->render();
        
        // Log aktivitas
        $this->logActivity('Generate PDF: ' . $filename, 'PDF Generator');
        
        // Output PDF
        if ($stream) {
            $this->dompdf->stream($filename, [
                'Attachment' => 0 // 0 = tampilkan di browser, 1 = download
            ]);
        } else {
            return $this->dompdf->output();
        }
    }
    
    /**
     * Generate PDF menggunakan TCPDF
     */
    private function generatePdfTCPDF($view, $data = [], $filename = 'document.pdf')
    {
        // Create new TCPDF object
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($data['church_info']['name'] ?? 'Sistem Gereja');
        $pdf->SetTitle($data['title']);
        $pdf->SetSubject($data['title']);
        $pdf->SetKeywords('Gereja, Ibadah, Program Kerja, Laporan');
        
        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Set margins
        $pdf->SetMargins(15, 15, 15);
        
        // Add a page
        $pdf->AddPage();
        
        // Set font
        $pdf->SetFont('helvetica', '', 10);
        
        // Load view content
        $html = view('pdf/tcpdf/' . $view, $data);
        
        // Write HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
        
        // Close and output PDF document
        $pdf->Output($filename, 'I');
        
        // Log aktivitas
        $this->logActivity('Generate PDF TCPDF: ' . $filename, 'PDF Generator');
        
        exit();
    }
    
    /**
     * Generate PDF multiple pages (untuk laporan panjang)
     */
    public function generateMultiPageReport($type, $params = [])
    {
        if (!$this->checkLogin()) {
            return redirect()->to('/auth/login');
        }
        
        $data = [];
        $filename = '';
        
        switch ($type) {
            case 'annual_report':
                $filename = 'Laporan_Tahunan_' . date('Y') . '.pdf';
                $data = $this->prepareAnnualReportData($params);
                break;
                
            case 'financial_report':
                $filename = 'Laporan_Keuangan_' . ($params['tahun'] ?? date('Y')) . '.pdf';
                $data = $this->prepareFinancialReportData($params);
                break;
                
            case 'member_directory':
                $filename = 'Direktori_Jemaat_' . date('Ymd') . '.pdf';
                $data = $this->prepareMemberDirectoryData($params);
                break;
        }
        
        return $this->generatePdfDOMPDF('pdf/multi_page/' . $type, $data, $filename, 'A4', 'portrait', true);
    }
    
    /**
     * Preview PDF sebelum download
     */
    public function preview($type, $id = null)
    {
        if (!$this->checkLogin()) {
            return redirect()->to('/auth/login');
        }
        
        $data = [
            'type' => $type,
            'id' => $id,
            'preview_mode' => true
        ];
        
        switch ($type) {
            case 'jadwal_ibadah':
                $jadwalModel = new \App\Models\JadwalModel\JadwalIbadahModel();
                $data['schedules'] = $jadwalModel->where('tanggal >=', date('Y-m-01'))->where('tanggal <=', date('Y-m-t'))->findAll();
                $data['title'] = 'Preview Jadwal Ibadah';
                break;
                
            case 'program_kerja':
                $programModel = new \App\Models\ProkerModel\ProkerModel();
                $data['programs'] = $programModel->findAll();
                $data['title'] = 'Preview Program Kerja';
                break;
        }
        
        $data['church_info'] = $this->getChurchInfo();
        
        return view('pdf/preview', $data);
    }
    
    /**
     * Download PDF dengan parameter
     */
    public function download()
    {
        if (!$this->checkLogin()) {
            return redirect()->to('/auth/login');
        }
        
        $type = $this->request->getGet('type');
        $params = $this->request->getGet();
        
        switch ($type) {
            case 'jadwal_ibadah':
                return $this->jadwalIbadah($params['periode'] ?? 'bulan');
                
            case 'program_kerja':
                return $this->programKerja($params['tahun'] ?? date('Y'), $params['department_id'] ?? null);
                
            case 'laporan_kehadiran':
                return $this->laporanKehadiran($params['start_date'] ?? null, $params['end_date'] ?? null);
                
            case 'anggaran':
                return $this->anggaranProgram($params['tahun'] ?? date('Y'));
                
            default:
                session()->setFlashdata('error', 'Jenis laporan tidak valid.');
                return redirect()->back();
        }
    }
    
    /**
     * Get church information
     */
    private function getChurchInfo()
    {
        $settingsModel = model('SettingsModel');
        $settings = $settingsModel->getAllSettings();
        
        $churchInfo = [];
        foreach ($settings as $setting) {
            $churchInfo[$setting->key] = $setting->value;
        }
        
        // Default values jika setting tidak ada
        $defaults = [
            'church_name' => 'GEREJA KRISTEN JAWA PENARUBAN',
            'church_address' => 'Jl. Mustari No.11 RT 02 RW 08, Desa Penaruban, Kecamatan Kaligondang, Purbalingga - 53391',
            'church_phone' => '(0281)895633',
            'church_email' => 'gkjpenaruban@gmail.com',
            'pastor_name' => 'Pdt. Tri Agus Fajar W, S.Si'
        ];
        
        foreach ($defaults as $key => $value) {
            if (!isset($churchInfo[$key])) {
                $churchInfo[$key] = $value;
            }
        }
        
        return $churchInfo;
    }
    
    /**
     * Get month name in Indonesian
     */
    private function getMonthName($monthNumber)
    {
        $months = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];
        
        return $months[$monthNumber] ?? 'Unknown';
    }
    
    /**
     * API untuk generate PDF dari AJAX request
     */
    public function generateApi()
    {
        if (!$this->checkLogin()) {
            return $this->jsonResponse([], false, 'Unauthorized', 401);
        }
        
        $type = $this->request->getPost('type');
        $data = $this->request->getPost('data');
        $format = $this->request->getPost('format') ?? 'pdf';
        
        if (!$type) {
            return $this->jsonResponse([], false, 'Type is required', 400);
        }
        
        try {
            $filename = '';
            $pdfContent = '';
            
            switch ($type) {
                case 'quick_report':
                    $pdfContent = $this->generateQuickReport($data);
                    $filename = 'Laporan_Cepat_' . date('Ymd_His') . '.pdf';
                    break;
                    
                case 'attendance_list':
                    $pdfContent = $this->generateAttendanceList($data);
                    $filename = 'Daftar_Hadir_' . date('Ymd_His') . '.pdf';
                    break;
                    
                case 'event_certificate':
                    $pdfContent = $this->generateEventCertificate($data);
                    $filename = 'Sertifikat_Acara_' . date('Ymd_His') . '.pdf';
                    break;
            }
            
            if ($format == 'base64') {
                return $this->jsonResponse([
                    'success' => true,
                    'filename' => $filename,
                    'content' => base64_encode($pdfContent),
                    'message' => 'PDF generated successfully'
                ]);
            } else {
                return $this->response
                    ->setContentType('application/pdf')
                    ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                    ->setBody($pdfContent);
            }
            
        } catch (\Exception $e) {
            return $this->jsonResponse([], false, 'Error generating PDF: ' . $e->getMessage(), 500);
        }
    } 
}