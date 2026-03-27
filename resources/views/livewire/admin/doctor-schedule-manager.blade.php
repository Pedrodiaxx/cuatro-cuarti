<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-4 flex justify-between items-end">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Horarios</h2>
            <div class="text-sm text-gray-500">Dashboard / Horarios / {{ $doctor->user->name }}</div>
        </div>
    </div>

    <div class="bg-white shadow-[0_2px_10px_rgb(0,0,0,0.05)] rounded-lg overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
            <h3 class="text-lg font-bold text-gray-700">Gestor de horarios</h3>
            <div class="flex items-center space-x-3">
                <div wire:loading wire:target="saveSchedule" class="text-sm text-gray-400">Guardando...</div>
                <button wire:click="saveSchedule" class="bg-[#6261f2] hover:bg-indigo-600 text-white px-6 py-2 rounded text-sm font-medium transition-colors">
                    Guardar horario
                </button>
            </div>
        </div>

        <div class="p-6 overflow-x-auto">
            <table class="w-full text-sm border-separate border-spacing-y-4">
                <thead>
                    <tr class="text-gray-400 text-[11px] uppercase tracking-wider text-left">
                        <th class="px-4 py-2 font-semibold">DÍA/HORA</th>
                        @foreach($days as $day)
                            <th class="px-4 py-2 font-semibold">{{ $day }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($timeBlocks as $block)
                        <!-- Main Hour Row -->
                        <tr class="group" wire:key="row-{{ $block['main'] }}">
                            <td class="px-4 py-6 align-top">
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" wire:click="toggleAllTime('{{ $block['main'] }}')" class="rounded border-gray-300 text-[#6261f2] focus:ring-[#6261f2]">
                                    <span class="font-bold text-gray-700 text-base">{{ $block['main'] }}</span>
                                </div>
                            </td>
                            
                            @foreach($days as $index => $day)
                                <td class="px-4 py-6 align-top" wire:key="cell-{{ $index }}-{{ $block['main'] }}">
                                    <div class="flex flex-col space-y-3">
                                        <!-- "Todos" checkbox for current day/block -->
                                        <div class="flex items-center space-x-2 mb-1">
                                            <input type="checkbox" class="rounded border-gray-200 text-[#6261f2] focus:ring-0 h-4 w-4" wire:click="toggleAllDay({{ $index }})">
                                            <span class="text-gray-400 text-xs">Todos</span>
                                        </div>

                                        @foreach($block['slots'] as $slot)
                                            @php 
                                                $key = $index . '-' . $slot;
                                                $isSelected = $schedule[$key] ?? false;
                                                // Format 08:00:00 -> 08:00
                                                $startTimeFormat = \Carbon\Carbon::createFromFormat('H:i:s', $slot)->format('H:i');
                                                $endTimeFormat = \Carbon\Carbon::createFromFormat('H:i:s', $slot)->addMinutes(15)->format('H:i');
                                            @endphp
                                            <label class="flex items-center space-x-2 cursor-pointer group" wire:key="slot-{{ $key }}">
                                                <input type="checkbox" 
                                                    wire:click="toggleSlot({{ $index }}, '{{ $slot }}')"
                                                    @if($isSelected) checked @endif
                                                    class="rounded border-gray-200 text-[#6261f2] focus:ring-0 h-4 w-4 transition-all">
                                                <span class="text-[13px] {{ $isSelected ? 'text-gray-900 font-medium' : 'text-gray-500' }}">
                                                    {{ $startTimeFormat }} - {{ $endTimeFormat }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
