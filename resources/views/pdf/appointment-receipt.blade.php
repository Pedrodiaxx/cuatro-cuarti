<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprobante de Cita Médica</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; font-size: 14px; }
        .header { text-align: center; border-bottom: 2px solid #6261f2; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { font-size: 24px; font-weight: bold; color: #6261f2; margin-bottom: 5px; }
        .subtitle { color: #777; font-size: 12px; }
        .content-box { border: 1px solid #eee; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
        .row { margin-bottom: 15px; }
        .label { font-weight: bold; color: #555; display: inline-block; width: 120px; }
        .value { color: #000; }
        .footer { text-align: center; margin-top: 50px; font-size: 11px; color: #999; border-top: 1px solid #eee; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">CLÍNICA PEDRINI</div>
        <div class="subtitle">Comprobante de Cita Médica Generado Automáticamente</div>
    </div>

    <div class="content-box">
        <div class="row">
            <span class="label">ID de Cita:</span>
            <span class="value">#{{ str_pad($appointment->id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="row">
            <span class="label">Paciente:</span>
            <span class="value">{{ $appointment->patient->user->name }} {{ $appointment->patient->user->last_name }}</span>
        </div>
        @if(isset($appointment->patient->user->id_number))
        <div class="row">
            <span class="label">DNI:</span>
            <span class="value">{{ $appointment->patient->user->id_number }}</span>
        </div>
        @endif
        <div class="row">
            <span class="label">Fecha:</span>
            <span class="value">{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</span>
        </div>
        <div class="row">
            <span class="label">Horario:</span>
            <span class="value">{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</span>
        </div>
        <div class="row">
            <span class="label">Atiende:</span>
            <span class="value">Dr. {{ $appointment->doctor->user->name }} {{ $appointment->doctor->user->last_name }}</span>
        </div>
    </div>

    <p style="font-size: 12px; color: #555; text-align: justify;">
        <strong>Nota Importante:</strong> Este comprobante confirma su reserva. Por favor, preséntelo en la recepción el día de su consulta. En caso de no poder asistir, comuníquese con nosotros con al menos 24 horas de anticipación.
    </p>

    <div class="footer">
        Documento generado el {{ now()->format('d/m/Y H:i:s') }}
        <br>
        Clínica Pedrini
    </div>
</body>
</html>
