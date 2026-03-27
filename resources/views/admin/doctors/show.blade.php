<x-admin-layout title="Doctores | Pedrini"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Doctores', 'href' => route('admin.doctors.index')],
        ['name' => 'Detalle'],
    ]">

    <x-wire-card>
        <div class="space-y-2">
            <p><strong>Nombre:</strong> {{ $doctor->user->name }}</p>
            <p><strong>Email:</strong> {{ $doctor->user->email }}</p>
            <p><strong>Especialidad:</strong> {{ $doctor->speciality?->name ?? 'N/A' }}</p>
            <p><strong>Licencia:</strong> {{ $doctor->medical_license_number ?? 'N/A' }}</p>
            <p><strong>Biografía:</strong> {{ $doctor->biography ?? 'N/A' }}</p>
        </div>
    </x-wire-card>
</x-admin-layout>