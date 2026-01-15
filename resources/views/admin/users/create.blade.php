<x-admin-layout title="Usuarios | Crear"
:breadcrumbs="[
  ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
  ['name' => 'Usuarios', 'href' => route('admin.users.index')],
  ['name' => 'Crear']
]">

<x-wire-card>
  <h2 class="text-lg font-semibold mb-4">Crear nuevo usuario</h2>

  <form action="{{ route('admin.users.store') }}" method="POST">
    @csrf

    <div class="space-y-4">

      <div class="grid lg:grid-cols-2 gap-4">
      <x-wire-input
        name="name"
        label="Nombre"
        :value="old('name')"
        required
        placeholder="Nombre"
        autocomplete="name"
      />

      <x-wire-input
        name="email"
        label="Email"
        :value="old('email')"
        required
        placeholder="usuario@dominio.com"
        autocomplete="email"
        inputmode="email"
      />

      <x-wire-input
        name="password"
        label="Contraseña"
        required
        placeholder="Mínimo 8 caracteres"
        autocomplete="new-password"
        type="password"
      />

      <x-wire-input
        name="password_confirmation"
        label="Confirmar Contraseña"
        required
        placeholder="Repita la contraseña"
        autocomplete="new-password"
        type="password"
      />

      <x-wire-input
        name="id_number"
        label="Número de ID"
        :value="old('id_number')"
        required
        placeholder="Ej. 123456"
        autocomplete="off"
        inputmode="numeric"
      />

      <x-wire-input
        name="phone"
        label="Teléfono"
        :value="old('phone')"
        required
        placeholder="Ej. 1234567890"
        autocomplete="tel"
        inputmode="tel"
      />
      </div>

      <x-wire-input
        name="address"
        label="Dirección"
        :value="old('address')"
        required
        placeholder="Ej. Calle 123, Ciudad"
        autocomplete="street-address"
      />

      <div>
        <x-wire-native-select name="role_id" label="Rol" required>
          <option value="">Seleccione un rol</option>

          @foreach($roles as $role)
            <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>
              {{ $role->name }}
            </option>
          @endforeach
        </x-wire-native-select>

        <p class="text-sm text-gray-500">
          Define los permisos y accesos del usuario
        </p>
      </div>

      <div class="flex justify-end">
        <x-wire-button
          type="submit" blue>
          <i class="fa-solid fa-floppy-disk"></i> Guardar Usuario
        </x-wire-button>
      </div>

    </div>
  </form>
</x-wire-card>

</x-admin-layout>
