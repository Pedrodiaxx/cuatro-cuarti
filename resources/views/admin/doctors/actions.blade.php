<div class="flex gap-2">
    <x-wire-button xs outline gray href="{{ route('admin.doctors.show', $doctor) }}">
        <i class="fa-solid fa-eye"></i>
    </x-wire-button>

    <x-wire-button href="{{ route('admin.doctors.edit', $doctor) }}" blue xs>
        <i class="fa-solid fa-pen-to-square"></i>
    </x-wire-button>

    <x-wire-button href="{{ route('admin.doctors.schedule', $doctor) }}" purple xs title="Gestionar Horarios">
        <i class="fa-solid fa-clock"></i>
    </x-wire-button>
</div>