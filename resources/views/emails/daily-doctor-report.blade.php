<!DOCTYPE html>
<html>
<head>
    <title>Tus Citas de Hoy</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Hola Dr. {{ $doctor->user->name ?? '' }} {{ $doctor->user->last_name ?? '' }}</h2>
    <p>Este es el resumen de tus citas programadas para el día de hoy, <strong>{{ \Carbon\Carbon::today()->format('d/m/Y') }}</strong>:</p>

    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%; margin-top: 20px;">
        <thead style="background-color: #f4f4f4;">
            <tr>
                <th>Hora</th>
                <th>Paciente</th>
                <th>Especialidad</th>
                <th>Motivo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($appointments as $apt)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($apt->start_time)->format('h:i A') }}</td>
                    <td>{{ $apt->patient->user->name ?? 'N/A' }} {{ $apt->patient->user->last_name ?? '' }}</td>
                    <td>{{ $apt->doctor->speciality->name ?? 'N/A' }}</td>
                    <td>{{ $apt->reason ?? 'No especificado' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">No tienes citas programadas para hoy.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p style="margin-top: 30px;">¡Que tengas un excelente día de consulta!</p>
    <p><strong>El equipo de {{ config('app.name') }}</strong></p>
</body>
</html>
