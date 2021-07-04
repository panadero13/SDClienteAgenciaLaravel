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
                <img alt="ecommerce" class="lg:w-1/2 w-full object-cover object-center rounded border border-gray-200" src="https://images.unsplash.com/photo-1587019158091-1a103c5dd17f?ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8ZmxpZ2h0fGVufDB8fDB8fA%3D%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60">
                <div class="lg:w-1/2 w-full lg:pl-10 lg:py-6 mt-6 lg:mt-0">
                    <h1 class="text-gray-900 text-3xl title-font font-medium mb-1">Vuelo de {{$vuelo['origen']}} a {{$vuelo['destino']}}</h1>
                    <div class="flex mb-4">
                        <h1 class="text-gray-900 text-2xl title-font font-medium mb-1">Fecha de salida {{$vuelo['fecha']}} </h1>
                    </div>
                    <h1 class="text-gray-900 text-xl title-font font-medium mb-1">Hora de salida {{$vuelo['hora']}} </h1>

                    <div class="flex mt-6 items-center pb-5 border-b-2 border-gray-200 mb-5">
                        <span class="title-font font-medium text-2xl text-gray-900">{{$vuelo['precio']}}€ por persona</span>
                    </div>
                    <form method="POST">
                        @csrf
                        <input type="hidden" name="vuelo_id" value="{{$vuelo['_id']}}">
                        <div class="flex">
                            <div class="flex flex-row h-10 w-24 rounded-lg relative">
                                <div class="bg-white w-24 text-xs md:text-base flex items-center justify-center">
                                    <span class="pr-1">Cantidad: </span>
                                    <input type="integer" name="quantity" value=1 class="pl-1 border bg-white w-5 text-xs md:text-base flex items-center justify-center focus:outline-none focus:ring focus:border-blue-300">
                                </div>
                            </div>
                            <button type="submit" formaction="{{route('cart.storeVuelo')}}" class="flex ml-auto text-white bg-red-500 border-0 py-2 px-6 focus:outline-none hover:bg-red-600 rounded">
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