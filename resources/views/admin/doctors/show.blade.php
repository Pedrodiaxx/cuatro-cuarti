<x-admin-layout title="Doctores | Pedrini"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Doctores', 'href' => route('admin.doctors.index')],
        ['name' => 'Detalle'],
    ]"
>
    <x-wire-card class="mt-10">
        <div class="space-y-2">
            <p><span class="text-gray-500 font-semibold">Nombre:</span> {{ $doctor->user->name }}</p>
            <p><span class="text-gray-500 font-semibold">Email:</span> {{ $doctor->user->email }}</p>
            <p><span class="text-gray-500 font-semibold">Especialidad:</span> {{ $doctor->speciality?->name ?? 'N/A' }}</p>
            <p><span class="text-gray-500 font-semibold">Licencia:</span> {{ $doctor->medical_license_number ?? 'N/A' }}</p>
            <p><span class="text-gray-500 font-semibold">Biografía:</span> {{ $doctor->biography ?? 'N/A' }}</p>
        </div>
    </x-wire-card>
</x-admin-layout>