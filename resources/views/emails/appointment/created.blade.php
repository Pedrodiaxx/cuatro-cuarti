<!DOCTYPE html>
<html>
<head>
    <title>Comprobante de Cita</title>
</head>
<body>
    <h2>Hola, {{ $appointment->patient->user->name }}</h2>
    <p>Se ha registrado tu cita médica con éxito.</p>
    <p>Adjunto a este correo encontrarás el comprobante en formato PDF con los detalles de tu cita.</p>
    <br>
    <p>Atentamente,</p>
    <p>Tu clínica de confianza</p>
</body>
</html>
