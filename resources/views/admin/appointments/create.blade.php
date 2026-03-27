<x-admin-layout title="Nueva Cita | Pedrini"
:breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Citas',
        'href' => route('admin.appointments.index'),
    ],
    [
        'name' => 'Nuevo',
    ]
]">

    <div class="bg-white rounded-lg shadow p-6">
        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.appointments.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Paciente -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Paciente</label>
                    <select name="patient_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Seleccione un paciente...</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->user->name }} {{ $patient->user->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Doctor -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Doctor</label>
                    <select name="doctor_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Seleccione un doctor...</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}">Dr. {{ $doctor->user->name }} {{ $doctor->user->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Fecha -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha (date)</label>
                    <input type="date" name="date" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <!-- Horas -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hora de inicio</label>
                        <input type="time" name="start_time" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hora de fin</label>
                        <input type="time" name="end_time" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                </div>

            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Motivo</label>
                <textarea name="reason" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Motivo de la cita..." required></textarea>
            </div>

            <div class="pt-4 border-t flex justify-end gap-x-4">
                <a href="{{ route('admin.appointments.index') }}" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Guardar cita
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
