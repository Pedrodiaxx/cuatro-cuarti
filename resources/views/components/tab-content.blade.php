{{--muestra/oculta el contenido de cada tab--}}
@props(['tab'])
<div x-show="tab === @js($tab)" x-cloak> 
    {{ $slot }}
</div>
{{--El Componente es el marco (la estructura repetitiva).

El Slot es la foto que pones dentro (el contenido variable).--}}

{{--Solo se muestra si el estado de Alpine (tab) coincide con el nombre del tab.

x-cloak evita el “parpadeo” inicial antes de que Alpine se active.--}}