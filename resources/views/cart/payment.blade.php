<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pago') }}
            @if(session('message'))
            <div class="text-green-700 pt-1">
                <div class="font-bold text-xl"> {{session('message')}}</div>
            </div>
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="flex items-center pb-2 max-w-md mx-auto bg-white rounded-xl shadow-lg overflow-hidden md:max-w-2xl">
            <div class="flex items-center">
                <div class="leading-loose">
                    <form id="form" action="/cart/finishPayment/" method="POST" class="max-w-xl m-4 p-10 bg-white rounded shadow-xl">
                        @csrf
                        <p class="text-gray-800 font-bold">Informacion personal</p>
                        <div class="">
                            <label class="block text-sm text-gray-600" for="name">Nombre</label>
                            <input class=" w-full px-5 py-1 text-gray-700 bg-gray-200 rounded" id="name" name="name" type="text" required="true" placeholder="Tu Nombre" aria-label="Name">
                        </div>
                        <div class="mt-2">
                            <label class="block text-sm text-gray-600" for="email">Email</label>
                            <input class="w-full px-5  py-1 text-gray-700 bg-gray-200 rounded" id="email" name="email" type="email" required="true" placeholder="Tu Email" aria-label="Email">
                        </div>
                        <p class="mt-4 text-gray-800 font-medium">Informacion de Pago</p>
                        <div class="">
                            <input class="w-full px-2 py-2 text-gray-700 bg-gray-200 rounded" id="card_num" name="card_num" type="tel" inputmode="numeric" pattern="[0-9\s]{13,19}" maxlength="19" required="true" placeholder="Numero de tarjeta" aria-label="Num_tarjeta">
                            <input class="w-30 px-2 pt-2 text-gray-700 bg-gray-200 rounded" id="card_date" name="card_date" type="text" maxlength="5" required="true" placeholder="MM/YY" aria-label="Fecha_tarjeta">
                            <input class="w-20 px-2 pt-2 text-gray-700 bg-gray-200 rounded" id="card_cvc" name="card_cvc" type="text" maxlength="3" required="true" placeholder="CVC" aria-label="CVC_tarjeta">
                        </div>
                    </form>
                    <div class="mt-4">
                        <button id="pay_button" onclick="connectSocket()" class="px-4 py-1 text-white font-light tracking-wider bg-gray-900 rounded" value="{{\Gloudemans\Shoppingcart\Facades\Cart::total()}}">{{\Gloudemans\Shoppingcart\Facades\Cart::total()}}€</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script type="text/javascript">
    var user_name = document.getElementById('name');
    var email_input = document.getElementById('email');
    var card_number = document.getElementById('card_num');
    var card_date = document.getElementById('card_date');
    var card_cvc = document.getElementById('card_cvc');
    var pay_button = document.getElementById('pay_button');
    var form = document.getElementById('form');

    connectSocket = function() {
        const ws = new WebSocket("ws://192.168.1.9:3005");

        ws.addEventListener('open', () => {
            console.log('conexion')
            ws.send(JSON.stringify({
                'type':'pay',
                'name': user_name.value,
                'email': email_input.value,
                'card_number': card_number.value,
                'card_date': card_date.value,
                'card_cvc': card_cvc.value,
                'cost': pay_button.value
            }));
        })

        ws.addEventListener('message', (message) => {
            var result = JSON.parse(message.data)['result'];
            var credit = JSON.parse(message.data)['credit'];
            if (result && credit) {
                form.submit();
            }
            else if(!result){
                window.location.replace(`http://localhost:8000/cart/paymentError/`);
            }
            else if(!credit){
                window.location.replace(`http://localhost:8000/cart/noCredit/`);
            }
        })

    }
</script>