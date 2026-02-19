<x-admin-layout title="Doctores | Pedrini"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Doctores'],
    ]"
>

    <x-wire-card>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500">
                        <th class="py-2">Doctor</th>
                        <th class="py-2">Especialidad</th>
                        <th class="py-2">Licencia</th>
                        <th class="py-2 w-1">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($doctors as $doctor)
                        <tr class="border-t">
                            <td class="py-2">{{ $doctor->user->name }}</td>
                            <td class="py-2">{{ $doctor->speciality?->name ?? 'N/A' }}</td>
                            <td class="py-2">{{ $doctor->medical_license_number ?? 'N/A' }}</td>
                            <td class="py-2 whitespace-nowrap">
                                @include('admin.doctors.actions', ['doctor' => $doctor])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $doctors->links() }}
            </div>
        </div>
    </x-wire-card>
</x-admin-layout>