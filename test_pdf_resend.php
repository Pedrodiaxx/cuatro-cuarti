<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

require_once app_path('Lib/FPDF-master/fpdf.php');
$pdf = new \FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'Test PDF attachment');
$pdfString = $pdf->Output('S');

$apiKey = 're_7VgZBiuY_EtnJKSmtRv46V3XesBLCTRih';
$response = \Illuminate\Support\Facades\Http::withToken($apiKey)
    ->withoutVerifying()
    ->post('https://api.resend.com/emails', [
        'from' => 'Pedrini Admin <onboarding@resend.dev>',
        'to' => ['joel.diaz.lopez7@gmail.com'],
        'subject' => 'Prueba de PDF Attached',
        'html' => '<p>Ahi va el PDF</p>',
        'attachments' => [
            [
                'filename' => 'prueba.pdf',
                'content' => base64_encode($pdfString)
            ]
        ]
    ]);

echo $response->status() . " | " . $response->body();
