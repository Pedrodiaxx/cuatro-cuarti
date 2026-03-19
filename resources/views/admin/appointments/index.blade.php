<x-admin-layout title="Citas | Meditime"
:breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Citas',
    ]
]">
  
  <div class="mb-6 flex justify-between items-center bg-white shadow-sm p-4 rounded-lg">
    <h2 class="text-xl font-bold text-gray-800">Citas médicas</h2>
    <a href="{{ route('admin.appointments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center shadow-md">
       <i class="fa-solid fa-plus mr-2"></i> Nuevo
    </a>
  </div>

  @if(session('success'))
      <div class="mb-4 p-4 text-green-700 bg-green-100 rounded-lg">
          {{ session('success') }}
      </div>
  @endif

  @if(session('download_pdf'))
      <script>
          document.addEventListener('DOMContentLoaded', function() {
              const link = document.createElement('a');
              link.href = "{{ session('download_pdf') }}";
              link.setAttribute('download', 'comprobante_cita.pdf');
              link.setAttribute('target', '_blank');
              document.body.appendChild(link);
              link.click();
              document.body.removeChild(link);
          });
      </script>
  @endif

  <div class="bg-white shadow rounded-lg overflow-x-auto border border-gray-200">
    <table class="w-full text-sm text-left text-gray-600 min-w-max">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
        <tr>
            <th class="px-6 py-4 font-semibold text-gray-500">ID</th>
            <th class="px-6 py-4 font-semibold text-gray-500">Paciente</th>
            <th class="px-6 py-4 font-semibold text-gray-500">Doctor</th>
            <th class="px-6 py-4 font-semibold text-gray-500">Fecha</th>
            <th class="px-6 py-4 font-semibold text-gray-500">Hora de inicio</th>
            <th class="px-6 py-4 font-semibold text-gray-500">Estatus</th>
            <th class="px-6 py-4 font-semibold text-gray-500 text-center">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        @forelse($appointments as $appointment)
        <tr class="bg-white hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">{{ $appointment->id }}</td>
            <td class="px-6 py-4 font-medium text-gray-900">{{ $appointment->patient->user->name ?? 'N/A' }} {{ $appointment->patient->user->last_name ?? '' }}</td>
            <td class="px-6 py-4">Dr. {{ $appointment->doctor->user->name ?? 'N/A' }} {{ $appointment->doctor->user->last_name ?? '' }}</td>
            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td>
            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}</td>
            <td class="px-6 py-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Programado
                </span>
            </td>
            <td class="px-6 py-4 text-center">
                <a href="{{ route('admin.appointments.consult', $appointment) }}"
                   class="inline-flex items-center justify-center p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition shadow-sm"
                   title="Atender Cita">
                    <i class="fa-solid fa-stethoscope"></i>
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="px-6 py-8 text-center text-gray-500">No hay citas registradas.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</x-admin-layout>
