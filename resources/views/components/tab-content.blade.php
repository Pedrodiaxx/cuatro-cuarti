@props(['tab'])

<div x-show="tab === @js($tab)" x-cloak>
    {{ $slot }}
</div>