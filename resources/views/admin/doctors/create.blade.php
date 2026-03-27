<x-admin-layout title="Doctores | Pedrini"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Doctores', 'href' => route('admin.doctors.index')],
        ['name' => 'Crear'],
    ]">

    <form action="{{ route('admin.doctors.store') }}" method="POST">
        @csrf

        {{-- Usuario --}}
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <label class="block text-sm font-medium text-gray-700">
                Usuario
            </label>
            <select
                name="user_id"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
                <option value="">Selecciona un usuario</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
        </div>

        @include('admin.doctors._form')

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('admin.doctors.index') }}"
               class="px-4 py-2 bg-gray-200 rounded-lg text-sm hover:bg-gray-300">
                Cancelar
            </a>

            <button type="submit"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700">
                Crear doctor
            </button>
        </div>
    </form>
</x-admin-layout>