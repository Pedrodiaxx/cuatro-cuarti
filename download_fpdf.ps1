[Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12
[System.Net.ServicePointManager]::ServerCertificateValidationCallback = {$true}
Invoke-WebRequest -Uri "https://github.com/Setasign/FPDF/archive/refs/heads/master.zip" -OutFile "fpdf.zip"
