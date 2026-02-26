@props([
    'tab',
    'error' => false,
])

@php
    $base = "inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group transition-colors duration-200";
@endphp

<li class="me-2">
    <a
        href="#"
        @click.prevent="tab = @js($tab)" {{--Cuando le das click, cambia la variable tab.--}}
        class="{{ $base }}"
        :class="tab === @js($tab)
            ? ({{ $error ? 'true' : 'false' }} ? 'text-red-600 border-red-600' : 'text-blue-600 border-blue-600')
            : ({{ $error ? 'true' : 'false' }} ? 'text-red-600 border-transparent hover:border-red-300' : 'border-transparent hover:text-blue-600 hover:border-blue-600')
        "
        :aria-current="tab === @js($tab) ? 'page' : undefined"
    >
        {{ $slot }}

        @if ($error)
            <i class="fa-solid fa-circle-exclamation ms-2 animate-pulse"></i>
        @endif
    </a>
</li>