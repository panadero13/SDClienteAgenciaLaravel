<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Error en Coches') }}
            @if(session('message'))
            <div class="text-red-700 pt-1">
                <div class="font-bold text-xl">
                    {{session('message')}}
                </div>
            </div>
            @endif
        </h2>
    </x-slot>

</x-app-layout>