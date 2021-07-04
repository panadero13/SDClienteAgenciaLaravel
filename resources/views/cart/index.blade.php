<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Carrito') }}
            @if(session('message'))
            <div class="text-green-700 pt-1">
                <div class="font-bold text-xl"> {{session('message')}}</div>
            </div>
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="pb-2 max-w-md mx-auto bg-white rounded-xl shadow-lg overflow-hidden md:max-w-2xl">
                <div class="flex">
                    <div class="p-8">
                        <div class="uppercase px-30 tracking-wide text-xl text-indigo-500 font-semibold">
                            Resumen de tu carrito
                        </div>
                        @forelse($productos as $producto)
                        <div class="block mt-1 text-lg leading-tight font-medium text-black">
                            {{ $producto->name }}: {{$producto->qty}}
                            @if($producto->options->type !== 'vuelo')
                             dias
                            @endif
                             x {{$producto->price}}€
                        </div>
                        @empty
                        <div class="block mt-1 text-lg leading-tight font-medium text-black">
                            No tienes productos en tu carrito
                        </div>
                        @endif
                        @if(!$productos->isEmpty() )
                        <p class="mt-2 text-gray-500">
                            Precio total: {{\Gloudemans\Shoppingcart\Facades\Cart::subtotal()}}€ sin IVA
                        </p>
                        <p class="mt-2 text-gary-900">
                            Precio total: {{\Gloudemans\Shoppingcart\Facades\Cart::total()}}€ con IVA
                        </p>
                        <div class="pt-2 ">
                            <a href="/cart/checkout" >
                                <button type="submit" class="flex text-white bg-red-500 border-0 py-2 px-6 focus:outline-none hover:bg-red-600 rounded">
                                    Pagar ahora
                                </button>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @if(!$productos->isEmpty() )
        <br>
        <hr><br>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @forelse($productos as $producto)
                    <div class="pb-2 max-w-md mx-auto bg-white rounded-xl shadow-lg overflow-hidden md:max-w-2xl pb-2">
                        @if($producto->options->type === 'vuelo')
                        <div class="md:flex">

                            <div class="md:flex-shrink-0">
                                <a href="/vuelos/detail/{{$producto->id}}">
                                    <img class="h-full w-full object-cover md:w-48" src="https://images.unsplash.com/photo-1587019158091-1a103c5dd17f?ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8ZmxpZ2h0fGVufDB8fDB8fA%3D%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Una imagen de un avion">
                                </a>
                            </div>
                            <div class="p-8">
                                <a href="/vuelos/detail/{{$producto->id}}" class="uppercase tracking-wide text-sm text-indigo-500 font-semibold underline">
                                    {{$producto->name }}
                                </a>
                                <div class="block mt-1 text-lg leading-tight font-medium text-black">
                                    {{$producto->options->fecha}} a las {{$producto->options->hora}}H
                                </div>
                                <p class="mt-2 text-gray-500">
                                    {{$producto->price}}€ por persona
                                </p>
                                <div class="pt-2 ">
                                    <form method="POST" action="/cart/deleteItem">
                                        <input type="hidden" name="product_id" value="{{$producto->rowId}}">
                                        @csrf
                                        <button class="flex text-white bg-red-500 border-0 py-2 px-6 focus:outline-none hover:bg-red-600 rounded">
                                            Eliminar del carrito
                                        </button>
                                    </form>

                                    <div class="pt-2">
                                        Cantidad:
                                    </div>
                                    <div class="flex flex-row border h-10 w-24 rounded-lg border-gray-400 relative">
                                        <button class="font-semibold border-r  bg-gray-400 hover:bg-gray-500 text-white border-gray-400 h-full w-20 flex rounded-l focus:outline-none cursor-pointer">
                                            <a class="m-auto" href="cart/decreaseCartQty/{{$producto->rowId}}">
                                                <span class="m-auto">-</span>
                                            </a>
                                        </button>
                                        <div class="bg-white w-24 text-xs md:text-base flex items-center justify-center cursor-default">
                                            <span>{{$producto->qty}}</span>
                                        </div>
                                        <button class="font-semibold border-l  bg-gray-400 hover:bg-gray-500 text-white border-gray-400 h-full w-20 flex rounded-r focus:outline-none cursor-pointer">
                                            <a class="m-auto" href="cart/increaseCartQty/{{$producto->rowId}}">
                                                <span class="m-auto">+</span>
                                            </a>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif($producto->options->type === 'coche')
                        <div class="md:flex">
                            <div class="md:flex-shrink-0">
                                <a href="/coches/detail/{{$producto->id}}">
                                    <img class="h-full w-full object-cover md:w-48" src="https://images.unsplash.com/photo-1493238792000-8113da705763?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=750&q=80" alt="Una imagen de un coche">
                                </a>
                            </div>
                            <div class="p-8">
                                <a href="/coches/detail/{{$producto->id}}" class="uppercase tracking-wide text-sm text-indigo-500 font-semibold underline">
                                    {{$producto->options->marca }}
                                </a>
                                <div class="block mt-1 text-lg leading-tight font-medium text-black">
                                    {{$producto->options->modelo }} - {{$producto->options->plazas }} plazas
                                </div>
                                <p class="mt-2 text-gray-500">
                                    {{$producto->price }}€ por dia
                                </p>
                                <div class="mt-3">
                                    Fecha inicio: <input type="date" name="fecha_inicio" value="{{$producto->options->fecha_inicio}}">
                                </div>
                                <div class="py-2 ">
                                    <div class="pt-2">
                                        Dias:
                                    </div>
                                    <div class="flex flex-row border h-10 w-24 rounded-lg border-gray-400 relative">
                                        <button class="font-semibold border-r  bg-gray-400 hover:bg-gray-500 text-white border-gray-400 h-full w-20 flex rounded-l focus:outline-none cursor-pointer">
                                            <a class="m-auto" href="cart/decreaseCartQty/{{$producto->rowId}}">
                                                <span class="m-auto">-</span>
                                            </a>
                                        </button>
                                        <input type="hidden" class="md:p-2 p-1 text-xs md:text-base border-gray-400 focus:outline-none text-center" name="custom-input-number" />
                                        <div class="bg-white w-24 text-xs md:text-base flex items-center justify-center cursor-default">
                                            <span>{{$producto->qty}}</span>
                                        </div>
                                        <button class="font-semibold border-l  bg-gray-400 hover:bg-gray-500 text-white border-gray-400 h-full w-20 flex rounded-r focus:outline-none cursor-pointer">
                                            <a class="m-auto" href="cart/increaseCartQty/{{$producto->rowId}}">
                                                <span class="m-auto">+</span>
                                            </a>
                                        </button>
                                    </div>
                                </div>
                                <form method="POST" action="/cart/deleteItem">
                                    <input type="hidden" name="product_id" value="{{$producto->rowId}}">
                                    @csrf
                                    <button class="flex text-white bg-red-500 border-0 py-2 px-6 focus:outline-none hover:bg-red-600 rounded">
                                        Eliminar del carrito
                                    </button>
                                </form>
                            </div>
                        </div>
                        @else
                        <div class="md:flex">
                            <div class="md:flex-shrink-0">
                                <a href="/hoteles/detail/{{$producto->id}}">
                                    <img class="h-full w-full object-cover md:w-48" src="https://images.unsplash.com/photo-1444201983204-c43cbd584d93?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=750&q=80" alt="Una imagen de un hotel">
                                </a>
                            </div>
                            <div class="p-8">
                                <a href="/hoteles/detail/{{$producto->id}}" class="uppercase tracking-wide text-sm text-indigo-500 font-semibold underline">
                                    {{$producto->name}}
                                </a>
                                <div class="block mt-1 text-lg leading-tight font-medium text-black">
                                    Habitacion {{$producto->options->tipo_habitacion}} para {{$producto->options->capacidad_personas}} personas
                                </div>
                                <p class="mt-2 text-gray-500">
                                    {{$producto->price}}€ por noche
                                </p>
                                <div class="mt-3">
                                    Fecha inicio: <input type="date" name="fecha_inicio" value="{{$producto->options->fecha_inicio}}">
                                </div>
                                <div class="py-2 ">
                                    <div class="py-2 ">
                                        <div class="pt-2">
                                            Dias:
                                        </div>
                                        <div class="flex flex-row border h-10 w-24 rounded-lg border-gray-400 relative">
                                            <button class="font-semibold border-r  bg-gray-400 hover:bg-gray-500 text-white border-gray-400 h-full w-20 flex rounded-l focus:outline-none cursor-pointer">
                                                <a class="m-auto" href="cart/decreaseCartQty/{{$producto->rowId}}">
                                                    <span class="m-auto">-</span>
                                                </a>
                                            </button>
                                            <input type="hidden" class="md:p-2 p-1 text-xs md:text-base border-gray-400 focus:outline-none text-center" name="custom-input-number" />
                                            <div class="bg-white w-24 text-xs md:text-base flex items-center justify-center cursor-default">
                                                <span>{{$producto->qty}}</span>
                                            </div>
                                            <button class="font-semibold border-l  bg-gray-400 hover:bg-gray-500 text-white border-gray-400 h-full w-20 flex rounded-r focus:outline-none cursor-pointer">
                                                <a class="m-auto" href="cart/increaseCartQty/{{$producto->rowId}}">
                                                    <span class="m-auto">+</span>
                                                </a>
                                            </button>
                                        </div>
                                    </div>
                                    <form method="POST" action="/cart/deleteItem">
                                        <input type="hidden" name="product_id" value="{{$producto->rowId}}">
                                        @csrf
                                        <button class="flex text-white bg-red-500 border-0 py-2 px-6 focus:outline-none hover:bg-red-600 rounded">
                                            Eliminar del carrito
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @empty
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>