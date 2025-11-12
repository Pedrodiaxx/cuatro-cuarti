<x-admin-layout title="Usuarios | Editar"
:breadcrumbs="[
  ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
  ['name' => 'Usuarios', 'href' => route('users.index')],
  ['name' => 'Editar']
]">

<x-wire-card>
  <h2 class="text-lg font-semibold mb-4">Editar usuario</h2>

  <form method="POST" action="#">
    @csrf
    @method('PUT')
    <x-wire-input label="Nombre" name="name" value="Juan Pérez" />
    <x-wire-input label="Correo" name="email" value="juan@example.com" />

    <div class="mt-4">
      <x-wire-button type="submit" blue>Actualizar Usuario</x-wire-button>
    </div>
  </form>
</x-wire-card>

</x-admin-layout>
