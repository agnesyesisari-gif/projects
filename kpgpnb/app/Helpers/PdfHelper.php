<?php

use TCPDF;

if (!function_exists('generate_pdf')) {

    function generate_pdf($data, $type = 'service', $filename = 'document.pdf')
    {
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator('Church Information System');
        $pdf->SetAuthor('Gereja');
        $pdf->SetTitle($data['title'] ?? 'Church Document');
        $pdf->SetSubject('Church Services and Programs');
        
        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Set default monospaced fonts
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        
        // Set margins
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(0);
        
        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        
        // Set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        
        // Add a page
        $pdf->AddPage();
        
        // Set font
        $pdf->SetFont('helvetica', '', 11);
        
        // Generate content based on type
        $html = generate_pdf_content($data, $type);
        
        // Write HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
        
        // Output PDF
        $pdf->Output($filename, 'I');
    }
}

if (!function_exists('generate_pdf_content')) {

    function generate_pdf_content($data, $type)
    {
        $content = '';
        
        switch ($type) {
            case 'service':
                $content = generate_service_pdf($data);
                break;
            case 'program':
                $content = generate_program_pdf($data);
                break;
            case 'schedule':
                $content = generate_schedule_pdf($data);
                break;
            default:
                $content = generate_default_pdf($data);
        }
        
        return $content;
    }
}

if (!function_exists('generate_service_pdf')) {

    function generate_service_pdf($data)
    {
        $html = '
        <style>
            .header { 
                text-align: center; 
                margin-bottom: 20px;
                border-bottom: 2px solid #333;
                padding-bottom: 10px;
            }
            .title { 
                font-size: 24px; 
                font-weight: bold; 
                color: #2c3e50;
            }
            .subtitle { 
                font-size: 16px; 
                color: #7f8c8d;
                margin-bottom: 10px;
            }
            .section { 
                margin: 15px 0; 
            }
            .section-title { 
                font-size: 18px; 
                font-weight: bold; 
                background-color: #f8f9fa;
                padding: 8px;
                border-left: 4px solid #3498db;
                margin-bottom: 10px;
            }
            .info-row { 
                margin: 5px 0; 
                padding: 3px 0;
            }
            .label { 
                font-weight: bold; 
                display: inline-block;
                width: 150px;
                color: #2c3e50;
            }
            .value { 
                display: inline-block;
                color: #34495e;
            }
            .table {
                width: 100%;
                border-collapse: collapse;
                margin: 15px 0;
            }
            .table th {
                background-color: #3498db;
                color: white;
                padding: 10px;
                text-align: left;
                border: 1px solid #ddd;
            }
            .table td {
                padding: 8px 10px;
                border: 1px solid #ddd;
            }
            .table tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            .note {
                font-style: italic;
                color: #7f8c8d;
                margin-top: 20px;
                padding: 10px;
                background-color: #f8f9fa;
                border-left: 4px solid #e74c3c;
            }
        </style>
        
        <div class="header">
            <div class="title">JADWAL IBADAH GEREJA</div>
            <div class="subtitle">' . ($data['church_name'] ?? 'Gereja') . '</div>
            <div class="subtitle">Periode: ' . ($data['period'] ?? date('F Y')) . '</div>
        </div>';
        
        if (isset($data['services']) && is_array($data['services'])) {
            $html .= '<div class="section">
                <div class="section-title">Detail Ibadah</div>';
            
            foreach ($data['services'] as $service) {
                $html .= '<div class="info-row">
                    <span class="label">Tanggal:</span>
                    <span class="value">' . ($service['date'] ?? '-') . ' ' . ($service['time'] ?? '') . '</span>
                </div>
                <div class="info-row">
                    <span class="label">Tema:</span>
                    <span class="value">' . ($service['theme'] ?? '-') . '</span>
                </div>
                <div class="info-row">
                    <span class="label">Pemimpin Ibadah:</span>
                    <span class="value">' . ($service['speaker'] ?? '-') . '</span>
                </div>
                <div class="info-row">
                    <span class="label">Tempat:</span>
                    <span class="value">' . ($service['location'] ?? '-') . '</span>
                </div>
                <div class="info-row">
                    <span class="label">Keterangan:</span>
                    <span class="value">' . ($service['description'] ?? '-') . '</span>
                </div>
                <hr style="margin: 15px 0; border: 0; border-top: 1px dashed #ddd;">';
            }
            
            $html .= '</div>';
        }
        
        if (isset($data['officers']) && is_array($data['officers'])) {
            $html .= '<div class="section">
                <div class="section-title">Petugas Ibadah</div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Peran</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            $no = 1;
            foreach ($data['officers'] as $officer) {
                $html .= '<tr>
                    <td>' . $no++ . '</td>
                    <td>' . ($officer['name'] ?? '-') . '</td>
                    <td>' . ($officer['role'] ?? '-') . '</td>
                    <td>' . ($officer['notes'] ?? '-') . '</td>
                </tr>';
            }
            
            $html .= '</tbody></table></div>';
        }
        
        $html .= '<div class="note">
            * Dokumen ini dibuat secara otomatis oleh Sistem Informasi Kegiatan Pelayanan Gereja
        </div>';
        
        return $html;
    }
}

if (!function_exists('generate_program_pdf')) {
    
    function generate_program_pdf($data)
    {
        $html = '
        <style>
            .header { 
                text-align: center; 
                margin-bottom: 30px;
                border-bottom: 2px solid #2c3e50;
                padding-bottom: 15px;
            }
            .title { 
                font-size: 26px; 
                font-weight: bold; 
                color: #2c3e50;
                margin-bottom: 5px;
            }
            .subtitle { 
                font-size: 18px; 
                color: #3498db;
                margin-bottom: 10px;
            }
            .period { 
                font-size: 14px; 
                color: #7f8c8d;
            }
            .section { 
                margin: 20px 0; 
            }
            .section-title { 
                font-size: 20px; 
                font-weight: bold; 
                color: #fff;
                background-color: #3498db;
                padding: 10px;
                border-radius: 5px;
                margin-bottom: 15px;
            }
            .program-card {
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 15px;
                margin: 10px 0;
                background-color: #f8f9fa;
            }
            .program-title {
                font-size: 16px;
                font-weight: bold;
                color: #2c3e50;
                margin-bottom: 8px;
            }
            .program-detail {
                margin: 5px 0;
                padding: 2px 0;
            }
            .label { 
                font-weight: bold; 
                display: inline-block;
                width: 120px;
                color: #2c3e50;
            }
            .value { 
                display: inline-block;
                color: #34495e;
            }
            .status {
                display: inline-block;
                padding: 3px 10px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: bold;
            }
            .status-sedang_berjalan { background-color: #f39c12; color: white; }
            .status-berlangsung { background-color: #3498db; color: white; }
            .status-selesai { background-color: #27ae60; color: white; }
            .status-dibatalkan { background-color: #e74c3c; color: white; }
            .table {
                width: 100%;
                border-collapse: collapse;
                margin: 15px 0;
            }
            .table th {
                background-color: #2c3e50;
                color: white;
                padding: 12px;
                text-align: left;
                border: 1px solid #ddd;
            }
            .table td {
                padding: 10px 12px;
                border: 1px solid #ddd;
            }
            .table tr:hover {
                background-color: #f5f5f5;
            }
            .footer-note {
                margin-top: 30px;
                padding: 15px;
                background-color: #f8f9fa;
                border-top: 2px solid #3498db;
                font-size: 12px;
                color: #7f8c8d;
                text-align: center;
            }
        </style>
        
        <div class="header">
            <div class="title">PROGRAM KERJA PELAYANAN GEREJA</div>
            <div class="subtitle">' . ($data['department'] ?? 'Departemen Pelayanan') . '</div>
            <div class="period">Tahun ' . ($data['year'] ?? date('Y')) . '</div>
        </div>';
        
        if (isset($data['programs']) && is_array($data['programs'])) {
            $html .= '<div class="section">
                <div class="section-title">Daftar Program Kerja</div>';
            
            foreach ($data['programs'] as $program) {
                $statusClass = 'status-' . ($program['status'] ?? 'sedang_berjalan');
                $statusText = ucfirst($program['status'] ?? 'Sedang Berjalan');
                
                $html .= '<div class="program-card">
                    <div class="program-title">' . ($program['name'] ?? '-') . ' 
                        <span class="status ' . $statusClass . '">' . $statusText . '</span>
                    </div>
                    <div class="program-detail">
                        <span class="label">Pelaksana:</span>
                        <span class="value">' . ($program['executor'] ?? '-') . '</span>
                    </div>
                    <div class="program-detail">
                        <span class="label">Waktu:</span>
                        <span class="value">' . ($program['start_date'] ?? '-') . ' s/d ' . ($program['end_date'] ?? '-') . '</span>
                    </div>
                    <div class="program-detail">
                        <span class="label">Anggaran:</span>
                        <span class="value">Rp ' . number_format($program['budget'] ?? 0, 0, ',', '.') . '</span>
                    </div>
                    <div class="program-detail">
                        <span class="label">Tujuan:</span>
                        <span class="value">' . ($program['objective'] ?? '-') . '</span>
                    </div>
                    <div class="program-detail">
                        <span class="label">Keterangan:</span>
                        <span class="value">' . ($program['description'] ?? '-') . '</span>
                    </div>
                </div>';
            }
            
            $html .= '</div>';
        }
        
        if (isset($data['timeline']) && is_array($data['timeline'])) {
            $html .= '<div class="section">
                <div class="section-title">Timeline Kegiatan</div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kegiatan</th>
                            <th>Tanggal</th>
                            <th>Tempat</th>
                            <th>Penanggung Jawab</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            $no = 1;
            foreach ($data['timeline'] as $activity) {
                $html .= '<tr>
                    <td>' . $no++ . '</td>
                    <td>' . ($activity['activity'] ?? '-') . '</td>
                    <td>' . ($activity['date'] ?? '-') . '</td>
                    <td>' . ($activity['place'] ?? '-') . '</td>
                    <td>' . ($activity['person_in_charge'] ?? '-') . '</td>
                </tr>';
            }
            
            $html .= '</tbody></table></div>';
        }
        
        if (isset($data['budget_summary'])) {
            $html .= '<div class="section">
                <div class="section-title">Rangkuman Anggaran</div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Jumlah Program</th>
                            <th>Total Anggaran</th>
                            <th>Realisasi</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            foreach ($data['budget_summary'] as $summary) {
                $html .= '<tr>
                    <td>' . ($summary['category'] ?? '-') . '</td>
                    <td>' . ($summary['program_count'] ?? 0) . '</td>
                    <td>Rp ' . number_format($summary['total_budget'] ?? 0, 0, ',', '.') . '</td>
                    <td>Rp ' . number_format($summary['realization'] ?? 0, 0, ',', '.') . '</td>
                </tr>';
            }
            
            $html .= '</tbody></table></div>';
        }
        
        $html .= '<div class="footer-note">
            Dokumen Program Kerja - ' . ($data['church_name'] ?? 'Gereja') . ' - Dicetak pada ' . date('d F Y H:i:s') . '
        </div>';
        
        return $html;
    }
}

if (!function_exists('generate_schedule_pdf')) {
    
    function generate_schedule_pdf($data)
    {
        $html = '
        <style>
            .header { 
                text-align: center; 
                margin-bottom: 25px;
                padding-bottom: 15px;
                border-bottom: 3px double #3498db;
            }
            .title { 
                font-size: 28px; 
                font-weight: bold; 
                color: #2c3e50;
                margin-bottom: 5px;
            }
            .subtitle { 
                font-size: 20px; 
                color: #e74c3c;
                margin-bottom: 10px;
            }
            .month-year { 
                font-size: 16px; 
                color: #7f8c8d;
                font-style: italic;
            }
            .calendar-table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }
            .calendar-table th {
                background-color: #2c3e50;
                color: white;
                padding: 12px;
                text-align: center;
                border: 1px solid #ddd;
                width: 14.28%;
            }
            .calendar-table td {
                padding: 10px;
                border: 1px solid #ddd;
                vertical-align: top;
                height: 100px;
                position: relative;
            }
            .calendar-day {
                font-weight: bold;
                color: #2c3e50;
                position: absolute;
                top: 5px;
                right: 5px;
            }
            .today {
                background-color: #e8f4fc;
                border: 2px solid #3498db;
            }
            .event {
                background-color: #e8f6f3;
                border-left: 3px solid #27ae60;
                padding: 5px;
                margin: 2px 0;
                font-size: 11px;
                border-radius: 3px;
            }
            .event-service {
                background-color: #fef9e7;
                border-left-color: #f39c12;
            }
            .event-program {
                background-color: #e8f4fc;
                border-left-color: #3498db;
            }
            .event-meeting {
                background-color: #f4ecf7;
                border-left-color: #8e44ad;
            }
            .legend {
                margin: 20px 0;
                padding: 15px;
                background-color: #f8f9fa;
                border-radius: 8px;
            }
            .legend-item {
                display: inline-block;
                margin-right: 20px;
            }
            .legend-color {
                display: inline-block;
                width: 15px;
                height: 15px;
                margin-right: 5px;
                vertical-align: middle;
            }
            .notes-section {
                margin-top: 30px;
                padding: 20px;
                background-color: #f8f9fa;
                border-radius: 8px;
                border-left: 4px solid #e74c3c;
            }
            .notes-title {
                font-weight: bold;
                color: #2c3e50;
                margin-bottom: 10px;
            }
            .footer {
                margin-top: 30px;
                text-align: center;
                font-size: 12px;
                color: #7f8c8d;
                padding-top: 15px;
                border-top: 1px dashed #ddd;
            }
        </style>
        
        <div class="header">
            <div class="title">KALENDER KEGIATAN GEREJA</div>
            <div class="subtitle">' . ($data['church_name'] ?? 'Gereja') . '</div>
            <div class="month-year">' . ($data['month_year'] ?? date('F Y')) . '</div>
        </div>';
        
        // Add legend
        $html .= '<div class="legend">
            <div class="legend-item">
                <span class="legend-color" style="background-color: #fef9e7; border-left: 3px solid #f39c12;"></span>
                <span>Ibadah</span>
            </div>
            <div class="legend-item">
                <span class="legend-color" style="background-color: #e8f4fc; border-left: 3px solid #3498db;"></span>
                <span>Program</span>
            </div>
            <div class="legend-item">
                <span class="legend-color" style="background-color: #f4ecf7; border-left: 3px solid #8e44ad;"></span>
                <span>Rapat</span>
            </div>
            <div class="legend-item">
                <span class="legend-color" style="background-color: #e8f6f3; border-left: 3px solid #27ae60;"></span>
                <span>Kegiatan Lain</span>
            </div>
        </div>';
        
        if (isset($data['calendar']) && is_array($data['calendar'])) {
            $html .= '<table class="calendar-table">';
            
            // Table headers (days of week)
            $daysOfWeek = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            $html .= '<thead><tr>';
            foreach ($daysOfWeek as $day) {
                $html .= '<th>' . $day . '</th>';
            }
            $html .= '</tr></thead><tbody>';
            
            // Calendar rows
            foreach ($data['calendar'] as $week) {
                $html .= '<tr>';
                foreach ($week as $day) {
                    $isToday = isset($day['is_today']) && $day['is_today'];
                    $tdClass = $isToday ? 'today' : '';
                    
                    $html .= '<td class="' . $tdClass . '">';
                    $html .= '<div class="calendar-day">' . ($day['day'] ?? '') . '</div>';
                    
                    if (isset($day['events']) && is_array($day['events'])) {
                        foreach ($day['events'] as $event) {
                            $eventClass = 'event';
                            if (isset($event['type'])) {
                                $eventClass .= ' event-' . $event['type'];
                            }
                            
                            $html .= '<div class="' . $eventClass . '">
                                <strong>' . ($event['time'] ?? '') . '</strong><br>
                                ' . ($event['title'] ?? '') . '
                            </div>';
                        }
                    }
                    
                    $html .= '</td>';
                }
                $html .= '</tr>';
            }
            
            $html .= '</tbody></table>';
        }
        
        if (isset($data['important_notes']) && is_array($data['important_notes'])) {
            $html .= '<div class="notes-section">
                <div class="notes-title">Catatan Penting Bulan Ini:</div>
                <ul>';
            
            foreach ($data['important_notes'] as $note) {
                $html .= '<li>' . ($note['text'] ?? '') . '</li>';
            }
            
            $html .= '</ul></div>';
        }
        
        $html .= '<div class="footer">
            Kalender Kegiatan Gereja - Dicetak pada ' . date('d F Y H:i:s') . '
        </div>';
        
        return $html;
    }
}

if (!function_exists('save_pdf_to_file')) {
    
    function save_pdf_to_file($data, $type = 'service', $filename = null)
    {
        if (!$filename) {
            $filename = 'church_document_' . date('Ymd_His') . '.pdf';
        }
        
        $path = WRITEPATH . 'uploads/pdfs/' . $filename;
        $directory = dirname($path);
        
        // Create directory if not exists
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator('Church Information System');
        $pdf->SetAuthor('Gereja');
        $pdf->SetTitle($data['title'] ?? 'Church Document');
        
        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Add a page
        $pdf->AddPage();
        
        // Set font
        $pdf->SetFont('helvetica', '', 11);
        
        // Generate content
        $html = generate_pdf_content($data, $type);
        
        // Write HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
        
        // Save to file
        $pdf->Output($path, 'F');
        
        return $path;
    }
}