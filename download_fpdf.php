<?php
$context = stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]);
$code = file_get_contents('https://raw.githubusercontent.com/Setasign/FPDF/master/fpdf.php', false, $context);
if (!is_dir(__DIR__.'/app/Lib')) mkdir(__DIR__.'/app/Lib');
file_put_contents(__DIR__.'/app/Lib/fpdf.php', $code);
echo "FPDF Downloaded! ";
