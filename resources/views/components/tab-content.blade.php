@props(['tap', 'error' => false]) {{--Pestaña a la que pertenece el enlace--}}
<div x-show="tab === '{{ $tap }}'" style="display:none;">
    {{ $slot }}
</div>
