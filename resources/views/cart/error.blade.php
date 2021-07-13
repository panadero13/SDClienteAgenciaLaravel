<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <input type="hidden" id="email" value="{{$email}}">
        <input type="hidden" id="cost" value="{{$cost}}">
            {{ __('Error en Carrito') }}
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
<script type="text/javascript">
    connectSocket = function() {
        var email_input = document.getElementById('email').value;
        var cost_input = document.getElementById('cost').value;
        console.log(email_input,cost_input);
        const ws = new WebSocket("ws://192.168.1.9:3005");

        ws.addEventListener('open', () => {
            console.log('conexion')
            ws.send(JSON.stringify({
                'type': 'cancel',
                'email': email_input,
                'cost': cost_input
            }));
        })

        ws.addEventListener('message', (message) => {
            var message = JSON.parse(message.data);
            console.log(message);
        })
    }

    connectSocket();
</script>