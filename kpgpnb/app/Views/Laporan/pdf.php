<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf {

    public function createPDF($html, $filename='', $download=TRUE, $paper='A4', $orientation='portrait') {
        require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';
        
        $pdf = new TCPDF($orientation, PDF_UNIT, $paper, true, 'UTF-8', false);
        
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Sistem Informasi Gereja');
        $pdf->SetTitle('Laporan Kegiatan Gereja');
        $pdf->SetSubject('Laporan');
        
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        
        if($download)
            $pdf->Output($filename.'.pdf', 'D');
        else
            $pdf->Output($filename.'.pdf', 'I');
    }
}
?>