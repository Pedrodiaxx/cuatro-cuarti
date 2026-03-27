<x-admin-layout title="Doctores | Pedrini"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Doctores', 'href' => route('admin.doctors.index')],
        ['name' => 'Editar'],
    ]">

    <form action="{{ route('admin.doctors.update', $doctor) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- 🔥 IMPORTANTE: enviar user_id para que pase la validación --}}
        <input type="hidden" name="user_id" value="{{ $doctor->user_id }}">

        {{-- Header del doctor --}}
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <div class="lg:flex lg:justify-between lg:items-center">
                
                {{-- Info del usuario --}}
                <div class="flex items-center">
                    <img 
                        src="{{ $doctor->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.$doctor->user->name }}" 
                        alt="{{ $doctor->user->name }}"
                        class="h-20 w-20 rounded-full object-cover object-center">

                    <div class="ml-4">
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ $doctor->user->name }}
                        </p>
                        <p class="text-sm text-gray-500">
                            Licencia: {{ $doctor->medical_license_number ?? 'N/A' }}
                        </p>
                        <p class="text-sm text-gray-400">
                            {{ $doctor->user->email }}
                        </p>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="flex space-x-3 mt-6 lg:mt-0">
                    <a href="{{ route('admin.doctors.index') }}"
                       class="px-4 py-2 bg-gray-200 rounded-lg text-sm hover:bg-gray-300">
                        Volver
                    </a>

                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700">
                        Guardar cambios
                    </button>
                </div>

            </div>
        </div>

        {{-- Formulario reutilizable --}}
        @include('admin.doctors._form')

    </form>
</x-admin-layout>