<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Hoteles') }}
            @if(session('message'))
            <div class="text-green-700 pt-1">
                <div class="font-bold text-xl">
                    {{session('message')}}
                    <a href="/cart">
                        - Ver el carrito &#10154;
                    </a>
                </div>
            </div>
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @forelse($hoteles as $hotel)
                    <div class="pb-2 max-w-md mx-auto bg-white rounded-xl shadow-lg overflow-hidden md:max-w-2xl pb-2">
                        <div class="md:flex">
                            <div class="md:flex-shrink-0">
                                <a href="/hoteles/detail/{{$hotel['_id']}}">
                                    <img class="h-full w-full object-cover md:w-48" src="https://images.unsplash.com/photo-1444201983204-c43cbd584d93?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=750&q=80" alt="Una imagen de un hotel">
                                </a>
                            </div>
                            <div class="p-8">
                                <a href="/hoteles/detail/{{$hotel['_id']}}" class="uppercase tracking-wide text-sm text-indigo-500 font-semibold underline">
                                    Hotel {{$hotel['nombre']}} en {{$hotel['ciudad']}}
                                </a>
                                <div class="block mt-1 text-lg leading-tight font-medium text-black">
                                    Habitacion {{$hotel['tipo_habitacion']}} para {{$hotel['capacidad_personas']}} Personas
                                </div>
                                <div class="block mt-1 text-lg leading-tight font-medium text-black">
                                    {{$hotel['camas']}} camas
                                </div>
                                <p class="mt-2 text-gray-500">
                                    {{$hotel['precio_dia']}}€ por noche
                                </p>
                                <form method="POST" action="{{route('cart.storeHotel')}}">
                                    @csrf
                                    <input type="hidden" name="hotel_id" value="{{$hotel['_id']}}">
                                    <div class="mt-3">
                                        Fecha inicio: <input required="true" type="date" name="fecha_inicio" >
                                    </div>
                                    <div class="mt-3 flex">
                                        <div class="pr-2">Dias:</div> <input type="integer" name="quantity" value="1" class="pl-2 border bg-white w-20 text-xs md:text-base flex items-center justify-center outline-none focus:ring border-blue-300" >
                                    </div>
                                    <div class="mt-3">
                                        <button type="submit" class="bg-white text-gray-800 font-bold rounded border-b-2 border-green-500 hover:border-green-600 hover:bg-green-500 hover:text-white shadow-md py-2 px-6 inline-flex items-center">
                                            <span class="mr-2">Añadir al carrito</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @empty
                        Esta pagina es de hoteles
                        @endif
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>