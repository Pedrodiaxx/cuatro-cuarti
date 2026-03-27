<x-admin-layout title="Doctores | Pedrini"
:breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Doctores',
    ]
]">

    {{-- Barra superior --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <input type="text"
                   placeholder="Buscar doctor..."
                   class="px-4 py-2 border rounded-lg shadow-sm focus:ring focus:ring-indigo-200 w-64">
        </div>

        
    </div>

    {{-- Tabla --}}
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">ID</th>
                        <th class="px-6 py-3 text-left">Doctor</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Especialidad</th>
                        <th class="px-6 py-3 text-left">Licencia</th>
                        <th class="px-6 py-3 text-right">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse ($doctors as $doctor)
                        <tr class="hover:bg-gray-50 transition">
                            
                            <td class="px-6 py-4 font-medium">
                                #{{ $doctor->id }}
                            </td>

                            {{-- SOLO NOMBRE (SIN FOTO) --}}
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-900">
                                    {{ $doctor->user->name }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $doctor->user->email }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-semibold bg-indigo-100 text-indigo-700 rounded-full">
                                    {{ $doctor->speciality?->name ?? 'Sin especialidad' }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $doctor->medical_license_number ?? 'N/A' }}
                            </td>

                            {{-- BOTONES CON ICONOS (SIN TEXTO LARGO) --}}
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">

                                    {{-- Ver (Ojito) --}}
                                    <a href="{{ route('admin.doctors.show', $doctor) }}"
                                       class="p-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition"
                                       title="Ver doctor">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7
                                                     -1.274 4.057-5.065 7-9.542 7
                                                     -4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    {{-- Editar (Lápiz) --}}
                                    <a href="{{ route('admin.doctors.edit', $doctor) }}"
                                       class="p-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition"
                                       title="Editar doctor">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </a>

                                    {{-- Horarios (Reloj) --}}
                                    <a href="{{ route('admin.doctors.schedules', $doctor) }}"
                                       class="p-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition"
                                       title="Ver Horarios">
                                        <i class="fa-solid fa-clock"></i>
                                    </a>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                No hay doctores registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="p-4 border-t">
            {{ $doctors->links() }}
        </div>
    </div>

</x-admin-layout>