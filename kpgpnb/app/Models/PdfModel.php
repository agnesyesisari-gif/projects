<?php

namespace App\Models;

use CodeIgniter\Model;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PdfModel extends Model
{
    protected $table = 'pdf_exports';
    protected $primaryKey = 'id_pdf';
    protected $allowedFields = [
        'nama_file',
        'jenis_laporan',
        'parameter',
        'path_file',
        'ukuran_file',
        'created_by',
        'created_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    /**
     * Generate PDF untuk Jadwal Ibadah
     */
    public function generatePdfJadwalIbadah($startDate, $endDate, $jenisIbadah = null)
    {
        $db = db_connect();
        
        $query = $db->table('jadwal_ibadah ji')
            ->select('ji.*, p.nama_pendeta, r.nama_ruangan, u.nama as pembuat')
            ->join('pendeta p', 'ji.id_pendeta = p.id_pendeta', 'left')
            ->join('ruangan r', 'ji.id_ruangan = r.id_ruangan', 'left')
            ->join('users u', 'ji.created_by = u.id', 'left')
            ->where('ji.tanggal_ibadah >=', $startDate)
            ->where('ji.tanggal_ibadah <=', $endDate);
            
        if ($jenisIbadah) {
            $query->where('ji.jenis_ibadah', $jenisIbadah);
        }
        
        $query->orderBy('ji.tanggal_ibadah', 'ASC')
              ->orderBy('ji.waktu_mulai', 'ASC');
        
        $data = $query->get()->getResultArray();
        
        // Generate HTML content
        $html = $this->buildHtmlJadwalIbadah($data, $startDate, $endDate);
        
        // Generate PDF
        $filename = 'Jadwal_Ibadah_' . date('Ymd_His') . '.pdf';
        $filepath = WRITEPATH . 'pdfs/' . $filename;
        
        $this->generatePdf($html, $filepath);
        
        // Save to database
        $pdfData = [
            'nama_file' => $filename,
            'jenis_laporan' => 'jadwal_ibadah',
            'parameter' => json_encode([
                'start_date' => $startDate,
                'end_date' => $endDate,
                'jenis_ibadah' => $jenisIbadah
            ]),
            'path_file' => $filepath,
            'ukuran_file' => filesize($filepath),
            'created_by' => session()->get('id') ?? 1
        ];
        
        $this->save($pdfData);
        
        return [
            'filename' => $filename,
            'filepath' => $filepath,
            'data' => $data
        ];
    }
    
    /**
     * Generate PDF untuk Program Kerja
     */
    public function generatePdfProgramKerja($startDate, $endDate, $status = null)
    {
        $db = db_connect();
        
        $query = $db->table('program_kerja pk')
            ->select('pk.*, k.nama_komisi, 
                     COUNT(k.id_kegiatan) as total_kegiatan,
                     SUM(k.anggaran) as total_anggaran_kegiatan,
                     u.nama as penanggungjawab')
            ->join('komisi k', 'pk.id_komisi = k.id_komisi', 'left')
            ->join('kegiatan k', 'pk.id_program = k.id_program', 'left')
            ->join('users u', 'pk.created_by = u.id', 'left')
            ->where('pk.tanggal_mulai >=', $startDate)
            ->where('pk.tanggal_selesai <=', $endDate);
            
        if ($status) {
            $query->where('pk.status', $status);
        }
        
        $query->groupBy('pk.id_program')
              ->orderBy('pk.tanggal_mulai', 'ASC');
        
        $data = $query->get()->getResultArray();
        
        // Generate HTML content
        $html = $this->buildHtmlProgramKerja($data, $startDate, $endDate);
        
        // Generate PDF
        $filename = 'Program_Kerja_' . date('Ymd_His') . '.pdf';
        $filepath = WRITEPATH . 'pdfs/' . $filename;
        
        $this->generatePdf($html, $filepath);
        
        // Save to database
        $pdfData = [
            'nama_file' => $filename,
            'jenis_laporan' => 'program_kerja',
            'parameter' => json_encode([
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => $status
            ]),
            'path_file' => $filepath,
            'ukuran_file' => filesize($filepath),
            'created_by' => session()->get('id') ?? 1
        ];
        
        $this->save($pdfData);
        
        return [
            'filename' => $filename,
            'filepath' => $filepath,
            'data' => $data
        ];
    }
    
    /**
     * Generate PDF untuk Laporan Keuangan
     */
    public function generatePdfLaporanKeuangan($startDate, $endDate, $idDepartemen = null)
    {
        $db = db_connect();
        
        $query = $db->table('transaksi_keuangan tk')
            ->select('tk.*, k.judul_kegiatan, k.nama_komisi, 
                     jk.jenis_kategori, u.nama as pencatat,
                     SUM(CASE WHEN tk.jenis_transaksi = "pemasukan" THEN tk.jumlah ELSE 0 END) as total_pemasukan,
                     SUM(CASE WHEN tk.jenis_transaksi = "pengeluaran" THEN tk.jumlah ELSE 0 END) as total_pengeluaran')
            ->join('kegiatan k', 'tk.id_kegiatan = k.id_kegiatan', 'left')
            ->join('komisi k', 'tk.id_komisi = k.id_komisi', 'left')
            ->join('jenis_kategori jk', 'tk.id_kategori = jk.id_kategori', 'left')
            ->join('users u', 'tk.created_by = u.id', 'left')
            ->where('tk.tanggal_transaksi >=', $startDate)
            ->where('tk.tanggal_transaksi <=', $endDate);
            
        if ($idDepartemen) {
            $query->where('tk.id_departemen', $idDepartemen);
        }
        
        $query->groupBy('tk.id_transaksi')
              ->orderBy('tk.tanggal_transaksi', 'ASC');
        
        $data = $query->get()->getResultArray();
        
        // Calculate totals
        $totalPemasukan = 0;
        $totalPengeluaran = 0;
        foreach ($data as $row) {
            if ($row['jenis_transaksi'] == 'pemasukan') {
                $totalPemasukan += $row['jumlah'];
            } else {
                $totalPengeluaran += $row['jumlah'];
            }
        }
        
        // Generate HTML content
        $html = $this->buildHtmlLaporanKeuangan($data, $startDate, $endDate, $totalPemasukan, $totalPengeluaran);
        
        // Generate PDF
        $filename = 'Laporan_Keuangan_' . date('Ymd_His') . '.pdf';
        $filepath = WRITEPATH . 'pdfs/' . $filename;
        
        $this->generatePdf($html, $filepath);
        
        // Save to database
        $pdfData = [
            'nama_file' => $filename,
            'jenis_laporan' => 'laporan_keuangan',
            'parameter' => json_encode([
                'start_date' => $startDate,
                'end_date' => $endDate,
                'id_departemen' => $idDepartemen
            ]),
            'path_file' => $filepath,
            'ukuran_file' => filesize($filepath),
            'created_by' => session()->get('id') ?? 1
        ];
        
        $this->save($pdfData);
        
        return [
            'filename' => $filename,
            'filepath' => $filepath,
            'data' => $data,
            'total_pemasukan' => $totalPemasukan,
            'total_pengeluaran' => $totalPengeluaran,
            'saldo' => $totalPemasukan - $totalPengeluaran
        ];
    }
    
    /**
     * Generate PDF untuk Laporan Kegiatan
     */
    public function generatePdfLaporanKegiatan($idKegiatan = null, $startDate = null, $endDate = null)
    {
        $db = db_connect();
        
        $query = $db->table('kegiatan k')
            ->select('k.*, pk.nama_program, k.nama_komisi, 
                     u.nama as penanggungjawab, 
                     COUNT(pa.id_peserta) as jumlah_peserta')
            ->join('program_kerja pk', 'k.id_program = pk.id_program', 'left')
            ->join('komisi k', 'pk.id_komisi = k.id_komisi', 'left')
            ->join('users u', 'k.created_by = u.id', 'left')
            ->join('peserta_kegiatan pa', 'k.id_kegiatan = pa.id_kegiatan', 'left');
        
        if ($idKegiatan) {
            $query->where('k.id_kegiatan', $idKegiatan);
        }
        
        if ($startDate && $endDate) {
            $query->where('k.tanggal_kegiatan >=', $startDate)
                  ->where('k.tanggal_kegiatan <=', $endDate);
        }
        
        $query->groupBy('k.id_kegiatan')
              ->orderBy('k.tanggal_kegiatan', 'DESC');
        
        $data = $query->get()->getResultArray();
        
        // Generate HTML content
        $html = $this->buildHtmlLaporanKegiatan($data, $startDate, $endDate);
        
        // Generate PDF
        $filename = 'Laporan_Kegiatan_' . date('Ymd_His') . '.pdf';
        $filepath = WRITEPATH . 'pdfs/' . $filename;
        
        $this->generatePdf($html, $filepath);
        
        // Save to database
        $pdfData = [
            'nama_file' => $filename,
            'jenis_laporan' => 'laporan_kegiatan',
            'parameter' => json_encode([
                'id_kegiatan' => $idKegiatan,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]),
            'path_file' => $filepath,
            'ukuran_file' => filesize($filepath),
            'created_by' => session()->get('id') ?? 1
        ];
        
        $this->save($pdfData);
        
        return [
            'filename' => $filename,
            'filepath' => $filepath,
            'data' => $data
        ];
    }
    
    /**
     * Generate PDF untuk Bukti Pelayanan
     */
    public function generatePdfBuktiPelayanan($idPelayanan)
    {
        $db = db_connect();
        
        $query = $db->table('pelayanan p')
            ->select('p.*, j.nama_lengkap as nama_jemaat, j.alamat, j.no_telepon,
                     jp.nama_pelayanan, k.nama_komisi,
                     u.nama as pelaksana, u2.nama as penanggungjawab')
            ->join('jemaat j', 'p.id_jemaat = j.id_jemaat')
            ->join('jenis_pelayanan jp', 'p.id_jenis_pelayanan = jp.id_jenis_pelayanan')
            ->join('komisi k', 'p.id_komisi = k.id_komisi', 'left')
            ->join('users u', 'p.created_by = u.id', 'left')
            ->join('users u2', 'p.id_penanggungjawab = u2.id', 'left')
            ->where('p.id_pelayanan', $idPelayanan);
        
        $data = $query->get()->getRowArray();
        
        if (!$data) {
            throw new \Exception('Data pelayanan tidak ditemukan');
        }
        
        // Generate HTML content
        $html = $this->buildHtmlBuktiPelayanan($data);
        
        // Generate PDF
        $filename = 'Bukti_Pelayanan_' . $idPelayanan . '_' . date('Ymd_His') . '.pdf';
        $filepath = WRITEPATH . 'pdfs/' . $filename;
        
        $this->generatePdf($html, $filepath, true); // Portrait mode
        
        // Save to database
        $pdfData = [
            'nama_file' => $filename,
            'jenis_laporan' => 'bukti_pelayanan',
            'parameter' => json_encode(['id_pelayanan' => $idPelayanan]),
            'path_file' => $filepath,
            'ukuran_file' => filesize($filepath),
            'created_by' => session()->get('id') ?? 1
        ];
        
        $this->save($pdfData);
        
        return [
            'filename' => $filename,
            'filepath' => $filepath,
            'data' => $data
        ];
    }
    
    /**
     * Build HTML for Jadwal Ibadah
     */
    private function buildHtmlJadwalIbadah($data, $startDate, $endDate)
    {
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Jadwal Ibadah</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; }
                .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
                .header h1 { margin: 0; color: #2c3e50; }
                .header p { margin: 5px 0; color: #7f8c8d; }
                .info-box { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th { background-color: #3498db; color: white; padding: 12px; text-align: left; }
                td { padding: 10px; border-bottom: 1px solid #ddd; }
                tr:nth-child(even) { background-color: #f2f2f2; }
                .footer { margin-top: 40px; text-align: center; color: #95a5a6; font-size: 12px; }
                .logo { text-align: center; margin-bottom: 20px; }
                .logo-text { font-size: 24px; font-weight: bold; color: #2c3e50; }
            </style>
        </head>
        <body>
            <div class="logo">
                <div class="logo-text">GEREJA KRISTEN INDONESIA</div>
                <div>Sistem Informasi Kegiatan Pelayanan</div>
            </div>
            
            <div class="header">
                <h1>JADWAL IBADAH</h1>
                <p>Periode: ' . date('d F Y', strtotime($startDate)) . ' - ' . date('d F Y', strtotime($endDate)) . '</p>
                <p>Tanggal Cetak: ' . date('d F Y H:i:s') . '</p>
            </div>';
        
        if (empty($data)) {
            $html .= '<div class="info-box">
                <p>Tidak ada jadwal ibadah untuk periode yang dipilih.</p>
            </div>';
        } else {
            $html .= '<table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jenis Ibadah</th>
                        <th>Waktu</th>
                        <th>Tempat</th>
                        <th>Pelayan</th>
                        <th>Khotbah</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>';
            
            $no = 1;
            foreach ($data as $row) {
                $html .= '<tr>
                    <td>' . $no++ . '</td>
                    <td>' . date('d/m/Y', strtotime($row['tanggal_ibadah'])) . '</td>
                    <td>' . $row['jenis_ibadah'] . '</td>
                    <td>' . date('H:i', strtotime($row['waktu_mulai'])) . ' - ' . date('H:i', strtotime($row['waktu_selesai'])) . '</td>
                    <td>' . ($row['nama_ruangan'] ?? $row['tempat_ibadah']) . '</td>
                    <td>' . $row['nama_pendeta'] . '</td>
                    <td>' . $row['khotbah'] . '</td>
                    <td>' . ucfirst($row['status']) . '</td>
                </tr>';
            }
            
            $html .= '</tbody>
            </table>
            <div style="margin-top: 20px;">
                <p>Total Jadwal: <strong>' . count($data) . '</strong> ibadah</p>
            </div>';
        }
        
        $html .= '
            <div class="footer">
                <p>Dokumen ini dicetak secara otomatis dari Sistem Informasi Kegiatan Pelayanan Gereja</p>
                <p>© ' . date('Y') . ' Gereja Kristen Indonesia. Hak Cipta Dilindungi.</p>
            </div>
        </body>
        </html>';
        
        return $html;
    }
    
    /**
     * Build HTML for Program Kerja
     */
    private function buildHtmlProgramKerja($data, $startDate, $endDate)
    {
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Program Kerja</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; }
                .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
                .header h1 { margin: 0; color: #2c3e50; }
                .header h2 { margin: 10px 0; color: #34495e; font-size: 18px; }
                .info-box { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th { background-color: #27ae60; color: white; padding: 12px; text-align: left; }
                td { padding: 10px; border-bottom: 1px solid #ddd; vertical-align: top; }
                tr:nth-child(even) { background-color: #f2f2f2; }
                .status { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
                .status-selesai { background-color: #2ecc71; color: white; }
                .status-berjalan { background-color: #3498db; color: white; }
                .status-draft { background-color: #95a5a6; color: white; }
                .footer { margin-top: 40px; text-align: center; color: #95a5a6; font-size: 12px; }
                .summary { display: flex; justify-content: space-between; margin-top: 30px; }
                .summary-box { background: #ecf0f1; padding: 15px; border-radius: 5px; width: 30%; text-align: center; }
                .summary-value { font-size: 24px; font-weight: bold; color: #2c3e50; }
                .summary-label { font-size: 14px; color: #7f8c8d; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>PROGRAM KERJA GEREJA</h1>
                <h2>Periode: ' . date('d F Y', strtotime($startDate)) . ' - ' . date('d F Y', strtotime($endDate)) . '</h2>
                <p>Tanggal Cetak: ' . date('d F Y H:i:s') . '</p>
            </div>';
        
        if (empty($data)) {
            $html .= '<div class="info-box">
                <p>Tidak ada program kerja untuk periode yang dipilih.</p>
            </div>';
        } else {
            // Calculate summary
            $totalProgram = count($data);
            $totalAnggaran = 0;
            $totalKegiatan = 0;
            
            foreach ($data as $row) {
                $totalAnggaran += $row['anggaran'];
                $totalKegiatan += $row['total_kegiatan'];
            }
            
            $html .= '<div class="summary">
                <div class="summary-box">
                    <div class="summary-value">' . $totalProgram . '</div>
                    <div class="summary-label">Total Program</div>
                </div>
                <div class="summary-box">
                    <div class="summary-value">' . number_format($totalAnggaran, 0, ',', '.') . '</div>
                    <div class="summary-label">Total Anggaran</div>
                </div>
                <div class="summary-box">
                    <div class="summary-value">' . $totalKegiatan . '</div>
                    <div class="summary-label">Total Kegiatan</div>
                </div>
            </div>';
            
            $html .= '<table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Program</th>
                        <th>Komisi</th>
                        <th>Periode</th>
                        <th>Anggaran</th>
                        <th>Jml Kegiatan</th>
                        <th>Status</th>
                        <th>Penanggung Jawab</th>
                    </tr>
                </thead>
                <tbody>';
            
            $no = 1;
            foreach ($data as $row) {
                $statusClass = 'status-' . $row['status'];
                $html .= '<tr>
                    <td>' . $no++ . '</td>
                    <td>
                        <strong>' . $row['nama_program'] . '</strong><br>
                        <small>' . substr($row['deskripsi'], 0, 100) . '...</small>
                    </td>
                    <td>' . $row['nama_komisi'] . '</td>
                    <td>
                        ' . date('d/m/Y', strtotime($row['tanggal_mulai'])) . '<br>
                        s/d<br>
                        ' . date('d/m/Y', strtotime($row['tanggal_selesai'])) . '
                    </td>
                    <td style="text-align: right;">Rp ' . number_format($row['anggaran'], 0, ',', '.') . '</td>
                    <td style="text-align: center;">' . $row['total_kegiatan'] . '</td>
                    <td><span class="status ' . $statusClass . '">' . ucfirst($row['status']) . '</span></td>
                    <td>' . $row['penanggungjawab'] . '</td>
                </tr>';
            }
            
            $html .= '</tbody>
            </table>';
        }
        
        $html .= '
            <div class="footer">
                <p>Dokumen ini dicetak secara otomatis dari Sistem Informasi Kegiatan Pelayanan Gereja</p>
                <p>© ' . date('Y') . ' Gereja Kristen Indonesia. Hak Cipta Dilindungi.</p>
            </div>
        </body>
        </html>';
        
        return $html;
    }
    
    /**
     * Build HTML for Laporan Keuangan
     */
    private function buildHtmlLaporanKeuangan($data, $startDate, $endDate, $totalPemasukan, $totalPengeluaran)
    {
        $saldo = $totalPemasukan - $totalPengeluaran;
        
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Laporan Keuangan</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; }
                .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
                .header h1 { margin: 0; color: #2c3e50; }
                .summary { display: flex; justify-content: space-between; margin: 30px 0; }
                .summary-box { padding: 20px; border-radius: 5px; width: 30%; text-align: center; color: white; }
                .pemasukan { background-color: #27ae60; }
                .pengeluaran { background-color: #e74c3c; }
                .saldo { background-color: #3498db; }
                .summary-value { font-size: 24px; font-weight: bold; }
                .summary-label { font-size: 14px; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th { padding: 12px; text-align: left; }
                td { padding: 10px; border-bottom: 1px solid #ddd; }
                .pemasukan-row { background-color: #d5f4e6; }
                .pengeluaran-row { background-color: #fadbd8; }
                .footer { margin-top: 40px; text-align: center; color: #95a5a6; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>LAPORAN KEUANGAN GEREJA</h1>
                <p>Periode: ' . date('d F Y', strtotime($startDate)) . ' - ' . date('d F Y', strtotime($endDate)) . '</p>
                <p>Tanggal Cetak: ' . date('d F Y H:i:s') . '</p>
            </div>
            
            <div class="summary">
                <div class="summary-box pemasukan">
                    <div class="summary-value">Rp ' . number_format($totalPemasukan, 0, ',', '.') . '</div>
                    <div class="summary-label">TOTAL PEMASUKAN</div>
                </div>
                <div class="summary-box pengeluaran">
                    <div class="summary-value">Rp ' . number_format($totalPengeluaran, 0, ',', '.') . '</div>
                    <div class="summary-label">TOTAL PENGELUARAN</div>
                </div>
                <div class="summary-box saldo">
                    <div class="summary-value">Rp ' . number_format($saldo, 0, ',', '.') . '</div>
                    <div class="summary-label">SALDO AKHIR</div>
                </div>
            </div>';
        
        if (!empty($data)) {
            $html .= '<table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Kategori</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Departemen</th>
                    </tr>
                </thead>
                <tbody>';
            
            foreach ($data as $row) {
                $rowClass = $row['jenis_transaksi'] == 'pemasukan' ? 'pemasukan-row' : 'pengeluaran-row';
                $html .= '<tr class="' . $rowClass . '">
                    <td>' . date('d/m/Y', strtotime($row['tanggal_transaksi'])) . '</td>
                    <td>' . $row['keterangan'] . '</td>
                    <td>' . $row['jenis_kategori'] . '</td>
                    <td>' . ucfirst($row['jenis_transaksi']) . '</td>
                    <td style="text-align: right;">Rp ' . number_format($row['jumlah'], 0, ',', '.') . '</td>
                    <td>' . $row['nama_komisi'] . '</td>
                </tr>';
            }
            
            $html .= '</tbody>
            </table>';
        }
        
        $html .= '
            <div class="footer">
                <p>Dokumen ini dicetak secara otomatis dari Sistem Informasi Kegiatan Pelayanan Gereja</p>
                <p>© ' . date('Y') . ' Gereja Kristen Indonesia. Hak Cipta Dilindungi.</p>
            </div>
        </body>
        </html>';
        
        return $html;
    }
    
    /**
     * Generate PDF file
     */
    private function generatePdf($html, $filepath, $portrait = true)
    {
        // Ensure directory exists
        $dir = dirname($filepath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'Arial');
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        
        if ($portrait) {
            $dompdf->setPaper('A4', 'portrait');
        } else {
            $dompdf->setPaper('A4', 'landscape');
        }
        
        $dompdf->render();
        
        // Save the PDF
        $output = $dompdf->output();
        file_put_contents($filepath, $output);
    }
    
    /**
     * Get generated PDF files
     */
    public function getGeneratedPdfs($limit = 10, $offset = 0)
    {
        return $this->orderBy('created_at', 'DESC')
                    ->findAll($limit, $offset);
    }
    
    /**
     * Get PDF by ID
     */
    public function getPdfById($idPdf)
    {
        return $this->find($idPdf);
    }
    
    /**
     * Delete PDF file
     */
    public function deletePdf($idPdf)
    {
        $pdf = $this->find($idPdf);
        if ($pdf && file_exists($pdf['path_file'])) {
            unlink($pdf['path_file']);
        }
        return $this->delete($idPdf);
    }
}