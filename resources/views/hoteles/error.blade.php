<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Error en Hoteles') }}
            @if(session('error'))
            <div class="text-red-700 pt-1">
                <div class="font-bold text-xl">
                    {{session('error')}}
                </div>
            </div>
            @endif
        </h2>
    </x-slot>

</x-app-layout>