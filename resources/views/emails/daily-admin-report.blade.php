<!DOCTYPE html>
<html>
<head>
    <title>Reporte Diario de Citas</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <h2 style="color: #6261f2;">Reporte Diario de Citas - Clínica Pedrini</h2>
    <p>Hola Administrador,</p>
    <p>A continuación se presenta el resumen de las citas programadas para hoy ({{ now()->format('d/m/Y') }}):</p>
    
    @if($appointments->isEmpty())
        <p>No hay citas programadas para el día de hoy.</p>
    @else
        <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse; border-color: #ddd;">
            <thead style="background-color: #f8f9fa;">
                <tr>
                    <th style="text-align: left;">Hora</th>
                    <th style="text-align: left;">Paciente</th>
                    <th style="text-align: left;">Doctor</th>
                    <th style="text-align: left;">Especialidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}</td>
                    <td>{{ $appointment->patient->user->name }} {{ $appointment->patient->user->last_name }}</td>
                    <td>Dr. {{ $appointment->doctor->user->name }} {{ $appointment->doctor->user->last_name }}</td>
                    <td>{{ $appointment->doctor->speciality->name ?? 'Medicina General' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    
    <br>
    <p>Saludos cordiales,</p>
    <p><strong>El sistema de Clínica Pedrini</strong></p>
</body>
</html>
