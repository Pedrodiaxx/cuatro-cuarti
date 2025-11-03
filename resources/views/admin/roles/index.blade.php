<x-admin-layout title="Roles | Pedrini"
:breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Roles',
    ]
]">
   <x-slot name="action">
    <x-wire-button blue href="{{ route('admin.roles.create') }}">
        <i class="fa-solid fa-plus-circle"></i> Nuevo Rol
    </x-wire-button>
</x-slot>


@livewire('admin.datatables.role-table')
</x-admin-layout>