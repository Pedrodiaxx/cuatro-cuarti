<?php
$context = stream_context_create([
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ],
    'http' => [
        'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n"
    ]
]);
$zipData = file_get_contents('https://github.com/Setasign/FPDF/archive/refs/heads/master.zip', false, $context);
file_put_contents(__DIR__.'/fpdf.zip', $zipData);
$zip = new ZipArchive;
if ($zip->open(__DIR__.'/fpdf.zip') === TRUE) {
    if(!is_dir(__DIR__.'/app/Lib')) mkdir(__DIR__.'/app/Lib');
    $zip->extractTo(__DIR__.'/app/Lib/');
    $zip->close();
    echo "FPDF GitHub master extracted! ";
} else {
    echo "Failed to extract zip";
}
