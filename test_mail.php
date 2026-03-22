<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    \Illuminate\Support\Facades\Mail::raw('Simulación de conexión SMTP Resend desde Laravel local.', function ($message) {
        $message->to('joel.diaz.lopez7@gmail.com')
                ->subject('Conexión Resend Exitosa');
    });
    echo "EXITO";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
