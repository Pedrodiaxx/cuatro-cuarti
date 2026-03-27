<div class="bg-white shadow rounded-lg p-6">
    <div class="space-y-6">

        @php
            // Esto evita errores cuando estamos en CREATE (no existe $doctor)
            $doctor = $doctor ?? null;
        @endphp

        {{-- Especialidad --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">
                Especialidad
            </label>
            <select
                name="speciality_id"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
                <option value="">Selecciona una especialidad</option>
                @foreach ($specialities as $speciality)
                    <option value="{{ $speciality->id }}"
                        @selected(old('speciality_id', optional($doctor)->speciality_id) == $speciality->id)>
                        {{ $speciality->name }}
                    </option>
                @endforeach
            </select>
            @error('speciality_id')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Licencia médica --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">
                Número de licencia médica
            </label>
            <input
                type="text"
                name="medical_license_number"
                value="{{ old('medical_license_number', optional($doctor)->medical_license_number) }}"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
            @error('medical_license_number')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Biografía --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">
                Biografía
            </label>
            <textarea
                name="biography"
                rows="4"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >{{ old('biography', optional($doctor)->biography) }}</textarea>
            @error('biography')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

    </div>
</div>