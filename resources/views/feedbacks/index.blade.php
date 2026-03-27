<x-admin-layout title="Sugerencias"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Sugerencias'],
    ]"
>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Buzón de Sugerencias</h3>
                <a href="{{ route('admin.feedbacks.create') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    Nueva Sugerencia
                </a>
            </div>

            @if(session('success'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                    <span class="font-medium">¡Éxito!</span> {{ session('success') }}
                </div>
            @endif

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID</th>
                            <th scope="col" class="px-6 py-3">USUARIO</th>
                            <th scope="col" class="px-6 py-3">TIPO</th>
                            <th scope="col" class="px-6 py-3">ESTADO</th>
                            <th scope="col" class="px-6 py-3">FECHA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($feedbacks as $feedback)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $feedback->id }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $feedback->nombre_usuario }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $color = match($feedback->tipo) {
                                            'Queja' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                            'Sugerencia' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                            'Felicitación' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                                        };
                                    @endphp
                                    <span class="{{ $color }} text-xs font-medium mr-2 px-2.5 py-0.5 rounded">
                                        {{ $feedback->tipo }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($feedback->estado === 'Pendiente')
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">
                                            Pendiente
                                        </span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
                                            {{ $feedback->estado }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    {{ $feedback->created_at->format('d/m/Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td colspan="5" class="px-6 py-4 text-center">
                                    No hay sugerencias registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-admin-layout>
