<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vuelos') }}
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
            @if(session('success'))
            <div class="text-green-700 pt-1">
                <div class="font-bold text-xl">
                    {{session('success')}}
                </div>
            </div>
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @forelse($vuelos as $vuelo)
                    <div class="pb-2 max-w-md mx-auto bg-white rounded-xl shadow-lg overflow-hidden md:max-w-2xl pb-2">
                        <div class="md:flex">
                            <div class="md:flex-shrink-0">
                                <a href="/vuelos/detail/{{$vuelo['_id']}}">
                                    <img class="h-full w-full object-cover md:w-48" src="https://images.unsplash.com/photo-1587019158091-1a103c5dd17f?ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8ZmxpZ2h0fGVufDB8fDB8fA%3D%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Una imagen de un avion">
                                </a>
                            </div>
                            <div class="p-8">
                                <a href="/vuelos/detail/{{$vuelo['_id']}}" class="uppercase tracking-wide text-sm text-indigo-500 font-semibold underline">
                                    {{$vuelo['origen'] }} a {{$vuelo['destino'] }}
                                </a>
                                <div class="block mt-1 text-lg leading-tight font-medium text-black">
                                    {{$vuelo['fecha']}} a las {{$vuelo['hora']}} 
                                </div>
                                <p class="mt-2 text-gray-500">
                                    {{$vuelo['precio']}}€
                                </p>
                                <form method="POST" action="{{route('cart.storeVuelo')}}">
                                    @csrf
                                    <input type="hidden" name="vuelo_id" value="{{$vuelo['_id']}}">
                                    <input type="hidden" name="quantity" value=1>
                                    <div class="mt-3">
                                        <button type="submit" class="bg-white text-gray-800 font-bold rounded border-b-2 border-green-500 hover:border-green-600 hover:bg-green-500 hover:text-white shadow-md py-2 px-6 inline-flex items-center">
                                            <span class="mr-2">Añadir al carrito</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    Esta pagina es de vuelos
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>