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
      <h3 class="text-lg font-semibold text-gray-800 mb-2">Importar Pacientes (Excel/CSV)</h3>
      <p class="text-sm text-gray-600 mb-4">Sube un archivo .xlsx o .csv con múltiples pacientes. El procesamiento se hará en segundo plano para no bloquear tu pantalla.</p>
      
      @if(session('import_id'))
          <div id="import-progress-container" class="my-4">
              <h4 class="text-md font-semibold text-gray-700 mb-2">Progreso de Importación</h4>
              <div class="w-full bg-gray-200 rounded-full h-4 mb-2">
                <div id="import-progress-bar" class="bg-blue-600 h-4 rounded-full transition-all duration-300" style="width: 0%"></div>
              </div>
              <p id="import-progress-text" class="text-sm text-gray-600 font-medium">0%</p>
          </div>

          <script>
              document.addEventListener('DOMContentLoaded', function() {
                  const importId = "{{ session('import_id') }}";
                  const progressBar = document.getElementById('import-progress-bar');
                  const progressText = document.getElementById('import-progress-text');
                  
                  const interval = setInterval(() => {
                      fetch(`{{ route('admin.patients.import.status') }}?id=${importId}`)
                          .then(res => res.json())
                          .then(data => {
                              progressBar.style.width = data.progress + '%';
                              progressText.innerText = data.progress + '%';
                              
                              if (data.status === 'completed' || data.progress >= 100) {
                                  clearInterval(interval);
                                  if (typeof Swal !== 'undefined') {
                                      Swal.fire({
                                          icon: 'success',
                                          title: '¡Importación finalizada!',
                                          text: 'Todos los pacientes han sido importados exitosamente.',
                                      }).then(() => {
                                          window.location.href = "{{ route('admin.patients.index') }}";
                                      });
                                  } else {
                                      alert('Importación finalizada con éxito.');
                                      window.location.href = "{{ route('admin.patients.index') }}";
                                  }
                              }
                          })
                          .catch(err => console.error(err));
                  }, 1000);
              });
          </script>
      @else
          <form action="{{ route('admin.patients.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-4">
              @csrf
              <div class="flex-1">
                  <input type="file" name="import_file" id="import_file" accept=".csv, .xlsx, .xls" class="block w-full text-sm text-gray-500
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
      @endif
  </div>

  @livewire('admin.datatables.patient-table')
  
</x-admin-layout>