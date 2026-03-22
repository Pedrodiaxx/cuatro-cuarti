<?php

namespace App\Services;

class PdfGenerator {
    /**
     * Creates a fully compliant Raw PDF sequence bypassing all external libraries
     * Due to deep local SSL blocking, standard Composer installations (DomPDF/FPDF) map fail.
     */
    public static function generateReceipt($appointment) {
        $lines = [
            'COMPROBANTE DE CITA MEDICA',
            '========================================',
            utf8_decode('Clínica Pedrini'),
            '',
            'Paciente: ' . utf8_decode($appointment->patient->user->name ?? '') . ' ' . utf8_decode($appointment->patient->user->last_name ?? ''),
            'Doctor: Dr. ' . utf8_decode($appointment->doctor->user->name ?? '') . ' ' . utf8_decode($appointment->doctor->user->last_name ?? ''),
            'Especialidad: ' . utf8_decode($appointment->doctor->speciality->name ?? 'General'),
            'Fecha: ' . $appointment->date,
            'Hora: ' . \Carbon\Carbon::parse($appointment->start_time)->format('h:i A'),
            utf8_decode('Duración: 15 minutos'),
            '',
            'Motivo de consulta: ' . utf8_decode($appointment->reason ?? 'Ninguno especifico'),
            '',
            '========================================',
            utf8_decode('¡Gracias por su preferencia!')
        ];
        
        $objects = [];
        $objects[] = "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj";
        $objects[] = "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj";
        $objects[] = "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> >> >> /Contents 4 0 R >>\nendobj";
        
        $stream = "BT /F1 14 Tf\n";
        $y = 800; // start near top
        foreach ($lines as $line) {
            $clean = str_replace(['(', ')', '\\'], ['\\(', '\\)', '\\\\'], $line);
            $stream .= "50 {$y} Td ($clean) Tj\n-50 -{$y} Td\n";
            $y -= 25;
        }
        $stream .= "ET";
        
        $objects[] = "4 0 obj\n<< /Length " . strlen($stream) . " >>\nstream\n" . $stream . "\nendstream\nendobj";
        
        $pdf = "%PDF-1.4\n";
        $offsets = [];
        foreach ($objects as $obj) {
            $offsets[] = strlen($pdf);
            $pdf .= $obj . "\n";
        }
        
        $xrefPos = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";
        foreach ($offsets as $off) {
            $pdf .= sprintf("%010d 00000 n \n", $off);
        }
        $pdf .= "trailer\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\nstartxref\n$xrefPos\n%%EOF\n";
        
        return $pdf;
    }
}
