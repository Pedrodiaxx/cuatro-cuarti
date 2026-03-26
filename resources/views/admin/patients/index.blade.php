<x-admin-layout title="Pacientes | Meditime"
:breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Pacientes',
    ]
]">

  <div class="mb-6 bg-white p-4 rounded-lg shadow sm:flex sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <h3 class="text-lg font-medium text-gray-900">Importación Masiva de Pacientes</h3>
        <p class="text-sm text-gray-500">Sube un archivo Excel o CSV para importar miles de pacientes en segundo plano.</p>
    </div>
    <form action="{{ route('admin.patients.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center gap-2">
        @csrf
        <input type="file" name="file" accept=".csv, .xlsx, .xls" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" required>
        <button type="submit" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow">
            Importar
        </button>
    </form>
  </div>

  @if(session('current_import_id'))
  <div x-data="importProgress()" x-init="start()" class="mb-6 bg-white p-4 rounded-lg shadow border border-indigo-100">
      <div class="flex justify-between mb-1">
          <span class="text-sm font-medium text-indigo-700">Progreso de Importación</span>
          <span class="text-sm font-medium text-indigo-700" x-text="`${percentage}%`"></span>
      </div>
      <div class="w-full bg-gray-200 rounded-full h-2.5">
          <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500 ease-in-out" :style="`width: ${percentage}%`"></div>
      </div>
      <p class="text-xs text-gray-500 mt-2" x-text="`Procesando ${current} de ${total} registros aproximados en segundo plano...`" x-show="status !== 'finished' && status !== 'error'"></p>
      
      <p class="mt-2 text-sm text-green-600 font-bold" x-show="status === 'finished'" style="display: none;">
          ✅ ¡Importación Completada! <button @click="window.location.reload()" class="underline text-indigo-600 ml-2 hover:text-indigo-800">Recargar tabla</button>
      </p>

      <p class="mt-2 text-sm text-red-600 font-bold" x-show="status === 'error'" style="display: none;">
          ❌ <span x-text="errorMessage || 'La importación fue interrumpida por un paciente repetido o un error.'"></span> 
          <button @click="window.location.reload()" class="underline text-red-600 ml-2 hover:text-red-800">Recargar tabla</button>
      </p>
  </div>
  
  <script>
      function importProgress() {
          return {
              current: 0,
              total: 100,
              percentage: 0,
              status: 'processing',
              errorMessage: '',
              interval: null,
              start() {
                  this.interval = setInterval(() => {
                      fetch('{{ route('admin.patients.import.progress') }}')
                          .then(res => res.json())
                          .then(data => {
                              if (data.status === 'none') {
                                  clearInterval(this.interval);
                                  return;
                              }
                              this.current = data.current;
                              this.total = data.total;
                              this.status = data.status;
                              if (data.message) this.errorMessage = data.message;
                              this.percentage = Math.round((this.current / this.total) * 100);
                              
                              if (this.status === 'finished' || this.status === 'error') {
                                  clearInterval(this.interval);
                                  if(this.status === 'finished') this.percentage = 100;
                              }
                          });
                  }, 1500); // Check every 1.5 seconds
              }
          }
      }
  </script>
  @endif

  @if($errors->any())
    <div class="mb-4 text-red-600">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
  @endif

  @livewire('admin.datatables.patient-table')
  
</x-admin-layout>