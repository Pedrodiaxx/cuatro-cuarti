<x-admin-layout title="Usuarios | Crear"
:breadcrumbs="[
  ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
  ['name' => 'Usuarios', 'href' => route('users.index')],
  ['name' => 'Crear']
]">

<x-wire-card>
  <h2 class="text-lg font-semibold mb-4">Crear nuevo usuario</h2>

  <form method="POST" action="#">
    @csrf
    <x-wire-input label="Nombre" name="name" placeholder="Nombre completo" />
    <x-wire-input label="Correo" name="email" placeholder="Correo electrónico" />

    <div class="mt-4">
      <x-wire-button type="submit" blue>Guardar Usuario</x-wire-button>
    </div>
  </form>
</x-wire-card>

</x-admin-layout>
