<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$apiKey = 're_7VgZBiuY_EtnJKSmtRv46V3XesBLCTRih';
$response = \Illuminate\Support\Facades\Http::withToken($apiKey)
    ->withoutVerifying()
    ->post('https://api.resend.com/emails', [
        'from' => 'Pedrini Sistema <onboarding@resend.dev>',
        'to' => ['joel.diaz.lopez7@gmail.com'],
        'subject' => 'Prueba de HTTP API desde Laravel',
        'html' => '<strong>Funciona! Este correo saltó la barrera del antivirus local.</strong>'
    ]);

echo $response->status() . " | " . $response->body();
