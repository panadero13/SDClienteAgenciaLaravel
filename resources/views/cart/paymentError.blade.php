<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Error en Pago') }}
            @if(session('message'))
            <div class="text-red-700 pt-1">
                <div class="font-bold text-xl">
                    <a href="/cart">
                        {{session('message')}}
                    </a>
                </div>
            </div>
            @endif
            <div class="text-red-700 pt-1">
                <div class="font-bold text-xl">
                    <a href="/cart">
                        Volver al carrito
                    </a>
                </div>
            </div>
        </h2>
    </x-slot>

</x-app-layout>
