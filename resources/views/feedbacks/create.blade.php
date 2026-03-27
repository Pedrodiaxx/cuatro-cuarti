<x-admin-layout title="Nueva Sugerencia"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Sugerencias', 'href' => route('admin.feedbacks.index')],
        ['name' => 'Nueva Sugerencia'],
    ]"
>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Enviar Sugerencia o Reseña</h3>

                    @if ($errors->any())
                        <div class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                            <span class="sr-only">Error</span>
                            <div>
                                <span class="font-medium">Por favor corrige los siguientes errores:</span>
                                <ul class="mt-1.5 ml-4 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.feedbacks.store') }}">
                        @csrf
                        
                        <div class="mb-6">
                            <label for="nombre_usuario" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre del usuario</label>
                            <input type="text" id="nombre_usuario" name="nombre_usuario" value="{{ old('nombre_usuario') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        </div>

                        <div class="mb-6">
                            <label for="tipo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo</label>
                            <select id="tipo" name="tipo" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                <option value="" disabled {{ old('tipo') ? '' : 'selected' }}>Seleccionar...</option>
                                <option value="Queja" {{ old('tipo') == 'Queja' ? 'selected' : '' }}>Queja</option>
                                <option value="Sugerencia" {{ old('tipo') == 'Sugerencia' ? 'selected' : '' }}>Sugerencia</option>
                                <option value="Felicitación" {{ old('tipo') == 'Felicitación' ? 'selected' : '' }}>Felicitación</option>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label for="comentario" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Comentario detallado</label>
                            <textarea id="comentario" name="comentario" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Escribe tu comentario aquí..." required>{{ old('comentario') }}</textarea>
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('admin.feedbacks.index') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                                Cancelar
                            </a>
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                Enviar Sugerencia
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
