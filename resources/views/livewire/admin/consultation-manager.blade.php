<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Breadcrumbs -->
    <div class="mb-4 flex text-sm text-gray-500 space-x-2">
        <span>Dashboard</span>
        <span>/</span>
        <span>Citas</span>
        <span>/</span>
        <span class="text-gray-900 font-medium">Consulta</span>
    </div>

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 leading-none mb-1">
                {{ $appointment->patient->user->name }} {{ $appointment->patient->user->last_name }}
            </h2>
            <p class="text-gray-500 font-medium tracking-tight">DNI: {{ $appointment->patient->user->id_number ?? 'No registrado' }}</p>
        </div>
        <div class="flex space-x-3">
            <button wire:click="toggleMedicalHistoryModal" class="flex items-center px-4 py-2 border border-gray-300 rounded-md bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                <i class="fa-solid fa-folder-open mr-2 text-gray-400"></i> Ver Historia
            </button>
            <button wire:click="toggleHistoryModal" class="flex items-center px-4 py-2 border border-gray-300 rounded-md bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                <i class="fa-solid fa-clock-rotate-left mr-2 text-gray-400"></i> Consultas Anteriores
            </button>
        </div>
    </div>

    <!-- Main Card Content -->
    <div class="bg-white shadow-[0_4px_20px_rgb(0,0,0,0.03)] rounded-xl border border-gray-100 overflow-hidden">
        <!-- Tabs -->
        <div class="border-b border-gray-100">
            <nav class="flex px-8" aria-label="Tabs">
                <button 
                    wire:click="switchTab('consulta')" 
                    class="py-5 px-6 border-b-2 font-bold text-sm transition-all focus:outline-none flex items-center {{ $activeTab === 'consulta' ? 'border-[#6261f2] text-[#6261f2]' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                    <i class="fa-solid fa-comment-medical mr-2 font-medium"></i> Consulta
                </button>
                <button 
                    wire:click="switchTab('receta')" 
                    class="py-5 px-6 border-b-2 font-bold text-sm transition-all focus:outline-none flex items-center {{ $activeTab === 'receta' ? 'border-[#6261f2] text-[#6261f2]' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                    <i class="fa-solid fa-prescription-bottle-medical mr-2 font-medium"></i> Receta
                </button>
            </nav>
        </div>

        <div class="p-8">
            @if($activeTab === 'consulta')
                <!-- Consulta Tab Content -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Diagnóstico</label>
                        <textarea 
                            wire:model="diagnosis"
                            rows="4" 
                            class="w-full rounded-xl border-gray-200 focus:border-[#6261f2] focus:ring focus:ring-[#6261f2]/10 transition-all placeholder-gray-300 text-sm" 
                            placeholder="Describa el diagnóstico del paciente aquí..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tratamiento</label>
                        <textarea 
                            wire:model="treatment"
                            rows="4" 
                            class="w-full rounded-xl border-gray-200 focus:border-[#6261f2] focus:ring focus:ring-[#6261f2]/10 transition-all placeholder-gray-300 text-sm" 
                            placeholder="Describa el tratamiento recomendado aquí..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Notas</label>
                        <textarea 
                            wire:model="notes"
                            rows="4" 
                            class="w-full rounded-xl border-gray-200 focus:border-[#6261f2] focus:ring focus:ring-[#6261f2]/10 transition-all placeholder-gray-300 text-sm" 
                            placeholder="Agregue notas adicionales sobre la consulta..."></textarea>
                    </div>
                </div>
            @else
                <!-- Receta Tab Content -->
                <div class="bg-gray-50/50 rounded-xl p-6 border border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="w-full border-separate border-spacing-y-2">
                            <thead>
                                <tr class="text-xs font-bold text-gray-400 uppercase tracking-widest text-left">
                                    <th class="px-4 py-3">Medicamento</th>
                                    <th class="px-4 py-3">Dosis</th>
                                    <th class="px-4 py-3">Frecuencia / Duración</th>
                                    <th class="w-16"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($medications as $index => $med)
                                    <tr wire:key="med-{{ $index }}" class="bg-white shadow-[0_2px_10px_rgb(0,0,0,0.02)] rounded-lg">
                                        <td class="px-4 py-3 first:rounded-l-lg last:rounded-r-lg">
                                            <input type="text" wire:model="medications.{{ $index }}.name" 
                                                class="w-full rounded-lg border-gray-100 focus:border-[#6261f2] focus:ring-0 text-sm placeholder-gray-300"
                                                placeholder="Amoxicilina 500mg">
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="text" wire:model="medications.{{ $index }}.dose"
                                                class="w-full rounded-lg border-gray-100 focus:border-[#6261f2] focus:ring-0 text-sm placeholder-gray-300 text-center"
                                                placeholder="1 cada 8 horas">
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="text" wire:model="medications.{{ $index }}.frequency"
                                                class="w-full rounded-lg border-gray-100 focus:border-[#6261f2] focus:ring-0 text-sm placeholder-gray-300"
                                                placeholder="Ej: cada 8 horas por 7 días">
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <button wire:click="removeMedication({{ $index }})" class="p-2 text-red-100 hover:text-red-500 transition-colors">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <button wire:click="addMedication" 
                        class="mt-6 flex items-center px-6 py-2 border border-gray-200 rounded-lg text-sm font-bold text-gray-700 hover:bg-white hover:shadow-sm transition-all focus:outline-none">
                        <i class="fa-solid fa-plus mr-2 text-indigo-400"></i> Añadir Medicamento
                    </button>
                </div>
            @endif

            <!-- Save Action -->
            <div class="mt-12 flex justify-end">
                <button 
                    wire:click="saveConsultation" 
                    class="flex items-center bg-[#6261f2] hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl transition-all shadow-lg hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-[#6261f2]/20">
                    <i class="fa-solid fa-floppy-disk mr-3"></i> Guardar Consulta
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Consultas Anteriores -->
    @if($showHistoryModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm animate-in fade-in duration-200">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden animate-in zoom-in-95 duration-200">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-extrabold text-gray-800">
                    Historial de Consultas - {{ $appointment->patient->user->name }} {{ $appointment->patient->user->last_name }}
                </h3>
                <button wire:click="toggleHistoryModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <div class="p-6 max-h-[60vh] overflow-y-auto">
                @forelse($pastConsultations as $past)
                    <div class="mb-6 p-5 rounded-xl border border-gray-100 bg-gray-50/50 hover:bg-white hover:shadow-md transition-all">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs font-black text-[#6261f2] tracking-widest uppercase">
                                {{ \Carbon\Carbon::parse($past->date)->format('d M Y') }} ({{ \Carbon\Carbon::parse($past->start_time)->format('H:i') }})
                            </span>
                            <span class="text-xs font-bold text-gray-400">
                                Dr. {{ $past->doctor->user->name ?? 'Médico' }} {{ $past->doctor->user->last_name ?? '' }}
                            </span>
                        </div>
                        <h4 class="font-bold text-gray-800 mb-1">Diagnóstico:</h4>
                        <p class="text-sm text-gray-600 mb-4">{{ $past->diagnosis ?? 'No registrado' }}</p>
                        
                        @if($past->treatment)
                            <h4 class="font-bold text-gray-800 mb-1">Tratamiento:</h4>
                            <p class="text-sm text-gray-600">{{ $past->treatment }}</p>
                        @endif

                        @if($past->reason && !$past->diagnosis)
                            <p class="text-xs text-gray-400 mt-2 italic">Motivo inicial: {{ $past->reason }}</p>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="text-gray-200 mb-4 text-5xl text-center flex justify-center">
                            <i class="fa-solid fa-box-open"></i>
                        </div>
                        <p class="text-gray-400 font-medium tracking-tight">No hay registros de consultas previas.</p>
                    </div>
                @endforelse
            </div>

            <div class="p-6 bg-gray-50 text-right">
                <button wire:click="toggleHistoryModal" class="px-6 py-2 font-bold text-sm text-gray-600 hover:text-gray-800 transition-colors">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Historia Médica -->
    @if($showMedicalHistoryModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm animate-in fade-in duration-200">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden animate-in zoom-in-95 duration-200">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/30">
                <h3 class="text-xl font-extrabold text-gray-800">
                    Historia médica del paciente
                </h3>
                <button wire:click="toggleMedicalHistoryModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Tipo de sangre:</p>
                        <p class="text-lg font-extrabold text-gray-800">{{ $appointment->patient->bloodType->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Alergias:</p>
                        <p class="text-lg font-extrabold text-gray-800">{{ $appointment->patient->allergies ?: 'No registradas' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Enfermedades crónicas:</p>
                        <p class="text-lg font-extrabold text-gray-800">{{ $appointment->patient->chronic_conditions ?: 'No registradas' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Antecedentes quirúrgicos:</p>
                        <p class="text-lg font-extrabold text-gray-800">{{ $appointment->patient->surgical_history ?: 'No registrados' }}</p>
                    </div>
                </div>

                <div class="mt-12 flex justify-end">
                    <a href="#" class="text-[#6261f2] font-bold text-sm hover:underline">
                        Ver / Editar Historia Médica
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
