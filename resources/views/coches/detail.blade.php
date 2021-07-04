<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
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

    <section class="text-gray-700 body-font overflow-hidden bg-white">
        <div class="container px-5 py-24 mx-auto">
            <div class="lg:w-4/5 mx-auto flex flex-wrap">
                <img class="lg:w-1/2 w-full object-cover object-center rounded border border-gray-200" src="https://images.unsplash.com/photo-1493238792000-8113da705763?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=750&q=80">
                <div class="lg:w-1/2 w-full lg:pl-10 lg:py-6 mt-6 lg:mt-0">
                    <h1 class="text-gray-900 text-3xl title-font font-medium mb-1"> {{$coche['marca']}} {{$coche['modelo']}}</h1>
                    <div class="flex mb-4">
                        <h1 class="text-gray-900 text-2xl title-font font-medium mb-1">{{$coche['plazas']}} Plazas</h1>
                    </div>

                    <div class="flex mt-6 items-center pb-5 border-b-2 border-gray-200 mb-5">
                        <span class="title-font font-medium text-2xl text-gray-900">{{$coche['precio']}}€ por dia</span>
                    </div>
                    <form method="POST">
                        @csrf
                        <input type="hidden" name="coche_id" value="{{$coche['_id']}}">
                        <div class="mt-3">
                            Fecha inicio: <input required="true" type="date" name="fecha_inicio">
                        </div>
                        <div class="flex pt-2">
                            <div class="flex flex-row h-10 w-24 rounded-lg relative">
                                <div class="bg-white w-24 text-xs md:text-base flex items-center justify-center">
                                    <span class="pr-1">Dias: </span>
                                    <input type="integer" name="quantity" value=1 class="pl-1 border bg-white w-5 text-xs md:text-base flex items-center justify-center focus:outline-none focus:ring focus:border-blue-300">
                                </div>
                            </div>

                            <button type="submit" formaction="{{route('cart.storeCar')}}" class="flex ml-auto text-white bg-red-500 border-0 py-2 px-6 focus:outline-none hover:bg-red-600 rounded">
                                Añadir al carrito
                            </button>
                            <button class="flex ml-auto text-white bg-green-500 border-0 py-2 px-6 focus:outline-none hover:bg-green-600 rounded">
                                Comprar ahora
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

</x-app-layout>