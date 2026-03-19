<!DOCTYPE html>
<html>
<head>
    <title>Confirmación de Cita Médica</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <h2 style="color: #6261f2;">Confirmación de su Cita en Clínica Pedrini</h2>
    <p>Hola {{ $appointment->patient->user->name }},</p>
    <p>Su cita médica ha sido confirmada con éxito.</p>
    <ul>
        <li><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</li>
        <li><strong>Hora:</strong> {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</li>
        <li><strong>Doctor:</strong> Dr. {{ $appointment->doctor->user->name }} {{ $appointment->doctor->user->last_name }}</li>
        <li><strong>Especialidad:</strong> {{ $appointment->doctor->speciality->name ?? 'Medicina General' }}</li>
    </ul>
    <p>Adjunto a este correo encontrará su comprobante en PDF.</p>
    <p>Por favor asista 10 minutos antes de la hora programada.</p>
    <br>
    <p>Saludos cordiales,</p>
    <p><strong>El equipo de Clínica Pedrini</strong></p>
</body>
</html>
