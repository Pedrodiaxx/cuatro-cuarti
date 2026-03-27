<?php
exec('curl.exe -Lks https://github.com/Setasign/FPDF/archive/refs/heads/master.zip -o fpdf.zip');
$zip = new ZipArchive;
if ($zip->open(__DIR__.'/fpdf.zip') === TRUE) {
    if(!is_dir(__DIR__.'/app/Lib')) mkdir(__DIR__.'/app/Lib');
    $zip->extractTo(__DIR__.'/app/Lib/');
    $zip->close();
    echo "FPDF GitHub master extracted!";
} else {
    echo "Failed to extract zip";
}
