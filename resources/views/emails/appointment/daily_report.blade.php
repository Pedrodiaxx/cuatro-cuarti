<!DOCTYPE html>
<html>
<head>
    <title>Reporte Diario</title>
</head>
<body>
    <h2>Hola, {{ $recipientName }}</h2>
    <p>Adjunto a este correo encontrarás el reporte diario de citas agendadas para hoy, <strong>{{ now()->format('d/m/Y') }}</strong>.</p>
    <br>
    <p>Atentamente,</p>
    <p>Sistema Médico</p>
</body>
</html>
