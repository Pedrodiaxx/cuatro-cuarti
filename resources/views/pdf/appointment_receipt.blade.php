<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprobante de Cita</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; }
        .details { margin-top: 20px; border-collapse: collapse; width: 100%; }
        .details th, .details td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .details th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Comprobante de Cita Médica</h1>
    </div>
    
    <p>Estimado(a) <strong>{{ $appointment->patient->user->name ?? 'Paciente' }}</strong>,</p>
    <p>Este es el comprobante oficial de su cita programada.</p>
    
    <table class="details">
        <tr>
            <th>Paciente</th>
            <td>{{ $appointment->patient->user->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Doctor</th>
            <td>{{ $appointment->doctor->user->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Fecha</th>
            <td>{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <th>Hora de Inicio</th>
            <td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}</td>
        </tr>
        <tr>
            <th>Especialidad</th>
            <td>{{ $appointment->doctor->specialty ?? 'General' }}</td>
        </tr>
    </table>
    
    <br>
    <p>Por favor, preséntese al menos 10 minutos antes de la hora programada.</p>
</body>
</html>
