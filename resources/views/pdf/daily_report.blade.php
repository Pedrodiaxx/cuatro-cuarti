<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Diario de Citas</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #aaa; padding: 6px; text-align: left; }
        .table th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Reporte de Citas Agendadas</h2>
        <p>Fecha: {{ now()->format('d/m/Y') }}</p>
    </div>

    @if($appointments->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Hora</th>
                    <th>Paciente</th>
                    <th>Doctor</th>
                    <th>Especialidad</th>
                    <th>Motivo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</td>
                        <td>{{ $appointment->patient->user->name ?? 'N/A' }}</td>
                        <td>{{ $appointment->doctor->user->name ?? 'N/A' }}</td>
                        <td>{{ $appointment->doctor->specialty ?? 'General' }}</td>
                        <td>{{ $appointment->reason ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No hay citas programadas para el día de hoy.</p>
    @endif
</body>
</html>
