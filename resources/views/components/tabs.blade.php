{{--Contenedor principal--}}
@props(['active' => 'default'])

<div x-data="{ tab: @js($active) }">{{--evita problemas de comillas y hace seguro el valor en JS--}}
    @isset($header)
        <div class="border-b border-gray-200">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500">
                {{ $header }}
            </ul>
        </div>
    @endisset

    <div class="p-4 mt-4">
        {{ $slot }}
    </div>
</div>