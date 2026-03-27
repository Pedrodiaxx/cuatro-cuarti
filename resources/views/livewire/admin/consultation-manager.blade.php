<div>
    <!-- Encabezado y botones -->
    <div class="mb-6 bg-white shadow-sm p-5 rounded-lg border border-gray-200 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ $appointment->patient->user->name }} {{ $appointment->patient->user->last_name }}</h2>
            <p class="text-sm text-gray-500">
                Fecha de consulta: {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }} 
                | Hora: {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}
            </p>
        </div>
        <div class="flex gap-3">
            <!-- Botón Ver Historia -->
            <a href="{{ route('admin.patients.show', $appointment->patient_id) }}" 
               class="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 flex items-center">
                <i class="fa-solid fa-file-medical mr-2"></i> Ver Historia
            </a>
            
            <!-- Botón Modal Consultas Anteriores -->
            <button wire:click="openHistoryModal" 
                    class="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 flex items-center">
                <i class="fa-solid fa-clock-rotate-left mr-2"></i> Consultas Anteriores
            </button>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
        <!-- Tabs Nav -->
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px" aria-label="Tabs">
                <button wire:click="setTab('consulta')" 
                        class="w-1/2 py-4 px-1 text-center font-medium text-sm {{ $activeTab === 'consulta' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                    <i class="fa-solid fa-stethoscope mr-2"></i> Consulta
                </button>
                <button wire:click="setTab('receta')" 
                        class="w-1/2 py-4 px-1 text-center font-medium text-sm {{ $activeTab === 'receta' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                    <i class="fa-solid fa-pills mr-2"></i> Receta
                </button>
            </nav>
        </div>

        <form wire:submit.prevent="save">
            <div class="p-6">
                <!-- Tab: Consulta -->
                <div class="{{ $activeTab === 'consulta' ? 'block' : 'hidden' }}">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Diagnóstico <span class="text-red-500">*</span></label>
                            <textarea wire:model="diagnosis" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Describa el diagnóstico..." required></textarea>
                            @error('diagnosis') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tratamiento <span class="text-red-500">*</span></label>
                            <textarea wire:model="treatment" rows="4" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Indique el tratamiento a seguir..." required></textarea>
                            @error('treatment') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notas (Opcional)</label>
                            <textarea wire:model="notes" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Observaciones adicionales..."></textarea>
                            @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Tab: Receta -->
                <div class="{{ $activeTab === 'receta' ? 'block' : 'hidden' }}">
                    <div class="mb-4">
                        <button type="button" wire:click="addMedication" class="px-4 py-2 border border-blue-500 text-blue-500 bg-white hover:bg-blue-50 rounded-lg text-sm font-medium flex items-center shadow-sm">
                            <i class="fa-solid fa-plus mr-2"></i> Añadir Medicamento
                        </button>
                    </div>

                    @if(empty($medications))
                        <div class="text-center py-6 text-gray-500 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                            No hay medicamentos en la receta. Haz clic en "Añadir Medicamento".
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($medications as $index => $medication)
                                <div class="flex items-start gap-4 p-4 border border-gray-200 rounded-lg bg-gray-50 relative">
                                    <div class="flex-1 space-y-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-700 mb-1">Medicamento <span class="text-red-500">*</span></label>
                                                <input type="text" wire:model="medications.{{ $index }}.name" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Ej. Paracetamol 500mg">
                                                @error("medications.{$index}.name") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-700 mb-1">Dosis <span class="text-red-500">*</span></label>
                                                <input type="text" wire:model="medications.{{ $index }}.dosage" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Ej. 1 tableta">
                                                @error("medications.{$index}.dosage") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-700 mb-1">Frecuencia / Duración <span class="text-red-500">*</span></label>
                                            <input type="text" wire:model="medications.{{ $index }}.frequency" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Ej. Cada 8 horas por 5 días">
                                            @error("medications.{$index}.frequency") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <button type="button" wire:click="removeMedication({{ $index }})" class="p-2 text-red-500 hover:bg-red-50 rounded bg-white shadow-sm border border-red-200">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Footer con Botón Guardar -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow flex items-center">
                    <i class="fa-solid fa-save mr-2"></i> Guardar Consulta
                </button>
            </div>
        </form>
    </div>

    <!-- Modal de Consultas Anteriores -->
    @if($showHistoryModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeHistoryModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                    Historial de Consultas Anteriores
                                </h3>
                                
                                <div class="mt-2 text-left space-y-4 max-h-96 overflow-y-auto w-full">
                                    @forelse($previousConsultations as $consultation)
                                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                            <div class="flex justify-between items-start mb-2">
                                                <div class="font-semibold text-gray-800">
                                                    Fecha: {{ \Carbon\Carbon::parse($consultation->date)->format('d/m/Y') }}
                                                </div>
                                                <div class="text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                                    Dr. {{ $consultation->doctor->user->name ?? 'Desconocido' }}
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <span class="text-xs font-bold text-gray-600 uppercase">Diagnóstico:</span>
                                                <p class="text-sm text-gray-800">{{ $consultation->diagnosis }}</p>
                                            </div>
                                            <div>
                                                <span class="text-xs font-bold text-gray-600 uppercase">Tratamiento:</span>
                                                <p class="text-sm text-gray-800">{{ $consultation->treatment }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center text-gray-500 py-6 bg-gray-50 rounded-lg">
                                            No hay consultas anteriores para este paciente.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse relative flex justify-end">
                        <button type="button" wire:click="closeHistoryModal" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
