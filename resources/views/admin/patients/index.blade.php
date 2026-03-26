<x-admin-layout title="Pacientes | Pedrini"
:breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Pacientes',
    ]
]">

  <!-- Formulario de Importación Masiva -->
  <div class="mb-6 bg-white rounded-lg shadow-md p-6">
      <h3 class="text-lg font-semibold text-gray-800 mb-2">Importar Pacientes (CSV)</h3>
      <p class="text-sm text-gray-600 mb-4">Sube un archivo .csv con múltiples pacientes. El procesamiento se hará en segundo plano para no bloquear tu pantalla.</p>
      
      <form action="{{ route('admin.patients.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-4">
          @csrf
          <div class="flex-1">
              <input type="file" name="import_file" id="import_file" accept=".csv" class="block w-full text-sm text-gray-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-full file:border-0
                file:text-sm file:font-semibold
                file:bg-blue-50 file:text-blue-700
                hover:file:bg-blue-100
                border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" required>
          </div>
          <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
              Importar
          </button>
      </form>
      @error('import_file')
          <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
      @enderror
  </div>

  @livewire('admin.datatables.patient-table')
  
</x-admin-layout>