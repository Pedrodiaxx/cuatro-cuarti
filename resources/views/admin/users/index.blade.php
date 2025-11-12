<x-admin-layout title="Usuarios | Pedrini"
:breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Usuarios', 'href' => route('admin.users.index')],
    ['name' => 'Gestión']
]">

<x-wire-card>
    <h2 class="text-2xl font-bold mb-4">Gestión</h2>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm">
        <h3 class="text-xl font-semibold mb-2">Gestión de Usuarios</h3>
        <p class="text-gray-600 dark:text-gray-300">
            Esta es la vista principal del módulo de usuarios.
        </p>
    </div>
</x-wire-card>

</x-admin-layout>
