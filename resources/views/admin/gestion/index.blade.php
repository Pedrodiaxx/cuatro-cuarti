<x-admin-layout title="Gestión | Pedrini"
:breadcrumbs="[
  ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
  ['name' => 'Gestión']
]">

<x-wire-card>
  <h2 class="text-xl font-semibold mb-4">Panel de Gestión</h2>
  <p>Desde aquí puedes acceder a los módulos administrativos:</p>

  <ul class="list-disc ml-6 mt-3 text-gray-700 dark:text-gray-300">
    <li><a href="{{ route('admin.roles.index') }}" class="text-blue-600 hover:underline">Roles y permisos</a></li>
    <li><a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline">Usuarios</a></li>
  </ul>
</x-wire-card>

</x-admin-layout>
