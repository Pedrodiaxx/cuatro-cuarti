@props(['tap', 'error' => false]) {{--Pestaña a la que pertenece el enlace--}}

<li class="me-2"> 
    <a href="#" x-on:click  ="tab = '{{ $tap }}'" 
        :class="{
    'text-red-600 border-red-600': hasError && tab !== 'informacion-general',
    'text-blue-600 border-blue-600 active': tab === 'informacion-general' && !hasError,
    'text-red-600 border-red-600 active': tab === 'informacion-general' && hasError,
    'border-transparent hover:text-blue-600 hover:border-blue-600': tab !== 'informacion-general' && !hasError
    }"
    class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group transition-colors duration-200 {{ $error ? 'text-red-600' : '' }}"
    :aria-current="tab === '{{ $tap }}' ? 'page' : undefined">
    {{ $slot }}
    
    @if ($error)
    <i class="fa-solid fa-circle-exclamation ms-2 animate-pulse"></i>
     @endif
    
    </a>
    
    
</li>
