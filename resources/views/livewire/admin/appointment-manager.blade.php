<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-4 text-left">
        <h2 class="text-2xl font-bold text-gray-800">Nueva Cita Médica</h2>
        <div class="text-sm text-gray-500">Dashboard / Citas / Nuevo</div>
    </div>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            <strong class="font-bold">¡Atención!</strong>
            <ul class="list-disc list-inside mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Top Search Block -->
    <div class="bg-white shadow-[0_2px_10px_rgb(0,0,0,0.05)] rounded-lg mb-6 p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-1">Buscar disponibilidad</h3>
        <p class="text-sm text-gray-400 mb-6">Encuentra el horario perfecto filtrando por fecha y especialidad.</p>

        <form wire:submit.prevent="searchAvailability" class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-4 items-end w-full">
            <div class="col-span-1">
                <label class="block text-[11px] font-medium text-gray-500 mb-1">Fecha</label>
                <input type="date" wire:model.live="searchDate" class="block w-full rounded border-gray-200 focus:border-[#6261f2] focus:ring-0 sm:text-sm text-gray-600 px-3 py-2" required>
            </div>
            
            <div class="col-span-1 md:col-span-2 lg:col-span-1">
                <label class="block text-[11px] font-medium text-gray-500 mb-1">Hora (Rango)</label>
                <div class="flex items-center space-x-2">
                    <input type="time" wire:model="searchStartTime" class="block w-full rounded border-gray-200 focus:border-[#6261f2] focus:ring-0 sm:text-sm text-gray-600 px-3 py-2">
                    <span class="text-gray-400 text-sm">-</span>
                    <input type="time" wire:model="searchEndTime" class="block w-full rounded border-gray-200 focus:border-[#6261f2] focus:ring-0 sm:text-sm text-gray-600 px-3 py-2">
                </div>
            </div>

            <div class="col-span-1 lg:col-span-2">
                <label class="block text-[11px] font-medium text-gray-500 mb-1">Especialidad (opcional)</label>
                <input type="text" wire:model="searchSpecialty" placeholder="Ej. Endocrinología" class="block w-full rounded border-gray-200 focus:border-[#6261f2] focus:ring-0 sm:text-sm text-gray-600 px-3 py-2">
            </div>

             <div class="col-span-1">
                <button type="submit" class="w-full bg-[#6261f2] text-white py-2 px-6 rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#6261f2] transition-all text-sm font-semibold shadow-md">
                    Actualizar búsqueda
                </button>
            </div>
        </form>
    </div>

    <div class="flex flex-col md:flex-row gap-6">
        <!-- Left Column: Doctors and Availabilities -->
        <div class="md:w-2/3 flex flex-col space-y-4">
            @if(count($availableDoctors) == 0)
                <div class="bg-white shadow-[0_2px_10px_rgb(0,0,0,0.05)] rounded-lg p-6 text-center py-20 border-2 border-dashed border-gray-100">
                    <div class="text-[#6261f2] mb-3">
                        <i class="fa-solid fa-calendar-xmark text-5xl"></i>
                    </div>
                    <p class="text-gray-800 font-semibold mb-1">No se encontraron horarios disponibles</p>
                    <p class="text-gray-400 text-sm">Asegúrate de que los doctores tengan su **Gestor de Horarios** configurado para este día.</p>
                </div>
            @else
                @foreach($availableDoctors as $doc)
                <div class="bg-white shadow-[0_2px_10px_rgb(0,0,0,0.05)] rounded-lg p-6 border-2 transition-all {{ $selectedDoctorId == $doc['id'] ? 'border-[#6261f2]' : 'border-transparent' }}" wire:key="doc-{{ $doc['id'] }}">
                    <div class="flex items-center mb-6 pb-6 border-b border-gray-100">
                        @php
                            $initials = collect(explode(' ', $doc['name']))->map(function($segment) {
                                return substr($segment, 0, 1);
                            })->take(2)->implode('');
                        @endphp
                        <div class="h-16 w-16 rounded-full bg-[#eef0ff] flex items-center justify-center text-[#6261f2] font-bold text-2xl mr-4 border border-[#d6daff]">
                            {{ strtoupper($initials) }}
                        </div>
                        <div>
                            <h4 class="text-lg font-extrabold text-gray-800">Dr. {{ $doc['name'] }}</h4>
                            <p class="text-sm font-medium text-[#6261f2] uppercase tracking-wide">{{ $doc['specialty'] }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-[11px] font-bold text-gray-400 mb-4 uppercase tracking-widest">Horarios disponibles hoy:</p>
                        <div class="flex flex-wrap gap-3">
                            @foreach($doc['slots'] as $slot)
                            <button 
                                wire:click="selectTimeSlot({{ $doc['id'] }}, 'Dr. {{ $doc['name'] }}', '{{ $slot }}')" 
                                wire:key="slot-{{ $doc['id'] }}-{{ $slot }}"
                                class="px-6 py-2.5 rounded-lg text-sm font-bold transition-all shadow-sm border
                                {{ ($selectedDoctorId == $doc['id'] && $selectedTime == $slot) 
                                    ? 'bg-[#6261f2] text-white border-[#6261f2] scale-105' 
                                    : 'bg-white text-gray-700 border-gray-200 hover:border-[#6261f2] hover:text-[#6261f2] hover:bg-indigo-50' }}">
                                {{ $slot }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>

        <!-- Right Column: Appointment Summary & Details -->
        <div class="md:w-1/3">
            <div class="bg-white shadow-[0_2px_10px_rgb(0,0,0,0.05)] rounded-lg p-6 border border-gray-50">
                <h3 class="text-xl font-bold text-gray-800 mb-6 pb-4 border-b border-gray-100">Resumen de la cita</h3>
                
                <div class="space-y-4 mb-8">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-400">Doctor:</span>
                        <span class="font-bold text-gray-800">{{ $selectedDoctorName ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-400">Fecha:</span>
                        <span class="font-bold text-gray-800">{{ $searchDate ? \Carbon\Carbon::parse($searchDate)->translatedFormat('d F, Y') : '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-400">Horario:</span>
                        <span class="font-bold text-[#6261f2]">
                            @if($selectedTime)
                                {{ $selectedTime }} - {{ \Carbon\Carbon::parse($selectedTime)->addMinutes(15)->format('H:i') }}
                            @else
                                Pendiente
                            @endif
                        </span>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-[11px] font-bold text-gray-500 mb-1 uppercase tracking-wider">Paciente</label>
                    <select wire:model="patientId" class="block w-full rounded-lg border-gray-200 focus:border-[#6261f2] focus:ring-0 text-sm py-3 px-4 text-gray-700 bg-gray-50 hover:bg-white transition-colors">
                        <option value="">Seleccione un paciente</option>
                        @foreach($patients as $p)
                            <option value="{{ $p->id }}">{{ $p->user->name ?? 'Usuario' }} {{ $p->user->last_name ?? 'Desconocido' }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-8">
                    <label class="block text-[11px] font-bold text-gray-500 mb-1 uppercase tracking-wider">Motivo de la cita</label>
                    <textarea wire:model="reason" rows="3" class="block w-full rounded-lg border-gray-200 focus:border-[#6261f2] focus:ring-0 text-sm py-3 px-4 text-gray-700 bg-gray-50 hover:bg-white transition-colors" placeholder="Escribe el motivo de la consulta..."></textarea>
                </div>

                <button wire:click="saveAppointment" 
                    @if(!$selectedDoctorId || !$selectedTime || !$patientId) disabled @endif
                    class="w-full py-4 px-6 rounded-xl text-sm font-extrabold transition-all shadow-lg 
                    {{ ($selectedDoctorId && $selectedTime && $patientId) 
                        ? 'bg-[#6261f2] text-white hover:bg-indigo-700 hover:-translate-y-0.5' 
                        : 'bg-gray-200 text-gray-400 cursor-not-allowed' }}">
                    CONFIRMAR CITA
                </button>
                
                @if(!$selectedDoctorId || !$selectedTime || !$patientId)
                    <p class="mt-3 text-[10px] text-center text-gray-400">Selecciona doctor, horario y paciente para continuar.</p>
                @endif
            </div>
        </div>
    </div>
</div>
