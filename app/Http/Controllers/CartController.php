<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Exception;

class CartController extends Controller
{
    private $vuelo_uri = 'http://192.168.1.9:3000/api/vuelos/';
    private $vuelo_order_uri = 'http://192.168.1.9:3000/api/vuelos/orders/';
    private $coche_uri = 'http://192.168.1.9:3001/api/coches/';
    private $coche_order_uri = 'http://192.168.1.9:3001/api/coches/orders/';
    private $hotel_uri = 'http://192.168.1.9:3002/api/hoteles/';
    private $hotel_order_uri = 'http://192.168.1.9:3002/api/hoteles/orders/';

    private $safe_vuelo_uri = 'https://localhost:3000/api/vuelos/';
    private $safe_vuelo_order_uri = 'https://localhost:3000/api/vuelos/orders/';
    private $safe_coche_uri = 'https://localhost:3001/api/coches/';
    private $safe_coche_order_uri = 'https://localhost:3001/api/coches/orders/';
    private $safe_hotel_uri = 'https://localhost:3002/api/hoteles/';
    private $safe_hotel_order_uri = 'https://localhost:3002/api/hoteles/orders/';

    public function index()
    {
        $productos = Cart::content();
        return view('cart.index', compact('productos'));
    }

    public function decreaseItemQty($id)
    {
        $item = Cart::get($id);
        if ($item->qty == 1) {
            Cart::remove($id);
            return redirect()->back()
                ->with('message', 'Producto eliminado del carrito');
        }
        $new_qty = $item->qty - 1;
        Cart::update($id, ['qty' => $new_qty]);
        return redirect()->back();
    }
    public function increaseItemQty($id)
    {
        $item = Cart::get($id);
        $new_qty = $item->qty + 1;
        Cart::update($id, ['qty' => $new_qty]);
        return redirect()->back();
    }

    public function deleteItem(Request $request)
    {
        Cart::remove($request->input('product_id'));
        return redirect()->back()
            ->with('message', 'Producto eliminado del carrito');
    }

    public function checkout()
    {
        return view('cart.payment');
    }

    public function finishPayment(Request $request)
    {
        $request->validate([
            'card_date' => 'regex:/(^([0-9]{2})\/([0-9]{2})$)/u',
            'card_cvc' => 'digits:3',
        ]);
        DB::beginTransaction();
        $order_id = DB::table('orders')->insertGetId([
            'user_id' => auth()->user()->id,
            'total_price' => Cart::total() * 100,
            'fecha_compra' => Carbon::now(),
        ]);
        $pedidos_realizados = [];
        $num_productos = 0;
        foreach (Cart::content() as $row) {
            $post_id = '' . $order_id . '-' . $num_productos;
            try {
                if ($row->options->type === 'vuelo') {
                    $antiguo_stock = $this->postVuelo($row, $order_id, $post_id);
                    $pedidos_realizados[] = [
                        'id' => $post_id,
                        'tipo' => 'vuelo',
                        'cantidad' => $row->qty,
                        'producto_id' => $row->id,
                        'antiguo_stock' => $antiguo_stock
                    ];
                } elseif ($row->options->type === 'coche') {
                    $antiguo_stock = $this->postCoche($row,$order_id,$post_id);
                    $pedidos_realizados[] = [
                        'id' => $post_id,
                        'tipo' => 'coche',
                        'cantidad' => $row->qty,
                        'producto_id' => $row->id,
                        'antiguo_stock' => $antiguo_stock
                    ];
                } else {
                    $antiguo_stock = $this->postHotel($row,$order_id,$post_id);
                    $pedidos_realizados[] = [
                        'id' => $post_id,
                        'tipo' => 'hotel',
                        'cantidad' => $row->qty,
                        'producto_id' => $row->id,
                        'antiguo_stock' => $antiguo_stock
                    ];
                }
            } catch (Exception $ex) {
                DB::rollBack();
                $this->deleteInvalidOrders($pedidos_realizados);
                return redirect()->route('cart.error',['email' => $request->email, 'cost' => Cart::total()])
                    ->with('message', 'Lo sentimos, el producto ' . $row->name . ' no se encuentra disponible.');
            }
            $num_productos++;
        }
        DB::commit();
        return redirect()->route('home')->with('success', 'Pedido realizado con exito');
    }

    public function error($email,$cost)
    {
        return view('cart.error')->with('error', 'error')->with('email',$email)->with('cost',$cost);
    }

    public function postVuelo($row, $order_id, $post_id)
    {
        $vuelo_api = Http::get(
            $this->vuelo_uri . 'getVueloById/' . $row->id
        );
        $vuelo = $vuelo_api->json();
        if ($vuelo['stock'] < $row->qty) {
            throw new Exception;
        }
        $vuelo_id = DB::table('vuelos')->insertGetId([
            'ciudad_origen' => $vuelo['origen'],
            'ciudad_destino' => $vuelo['destino'],
            'fecha_salida' => Carbon::createFromFormat('d/m/Y', $vuelo['fecha']),
            'hora_salida' => $vuelo['hora'],
            'precio' => $vuelo['precio'] * 100,
        ]);
        DB::table('order_products')->insert([
            'order_id' => $order_id,
            'vuelo_id' => $vuelo_id,
            'cantidad' => $row->qty,
            'precio' => $row->price * 100,
            'fecha_inicio_contratada' => Carbon::createFromFormat('d/m/Y', $vuelo['fecha']),
            'server_id' => $vuelo['server_id']
        ]);
        $new_qty = $vuelo['stock'] - $row->qty;
        Http::put($this->vuelo_uri . 'modificaStock/'.$row->id.'/'.$new_qty,[]);
        $this->postVueloServer($vuelo, $row, $post_id);
    }

    public function postVueloServer($vuelo, $row, $post_id)
    {
        Http::post($this->vuelo_order_uri.'postVueloOrder', [
            '_id' => $post_id,
            'agencia_id' => '1111',
            'vuelo_id' => $vuelo['_id'],
            'usuario_email' => auth()->user()->email,
            'precio' => $row->price,
            'fecha_orden' => Carbon::now()->day.'/'.Carbon::now()->month.'/'.Carbon::now()->year,
            'plazas_compradas' => $row->qty
        ]);
    }

    public function postCoche($row, $order_id, $post_id)
    {
        $coche_api = Http::get(
            $this->coche_uri . 'getCocheById/' . $row->id
        );
        $coche = $coche_api->json();
        if ($coche['stock'] < $row->qty) {
            throw new Exception;
        }
        $coche_id = DB::table('coches')->insertGetId([
            'marca' => $coche['marca'],
            'modelo' => $coche['modelo'],
            'plazas' => $coche['plazas'],
            'precio_alquiler' => $coche['precio'] * 100,
        ]);
        DB::table('order_products')->insert([
            'order_id' => $order_id,
            'coche_id' => $coche_id,
            'cantidad' => $row->qty,
            'precio' => $row->price * 100,
            'fecha_inicio_contratada' => $row->options->fecha_inicio,
            'server_id' => $coche['server_id']
        ]);
        $new_qty = $coche['stock'] - $row->qty;
        Http::put($this->coche_uri . 'modificaStock/'.$row->id.'/'.$new_qty,[]);
        $this->postcocheServer($coche, $row, $post_id);
    }

    public function postCocheServer($coche, $row, $post_id)
    {
        Http::post($this->coche_order_uri.'postCocheOrder', [
            '_id' => $post_id,
            'agencia_id' => '1111',
            'coche_id' => $coche['_id'],
            'usuario_email' => auth()->user()->email,
            'precio' => $row->price,
            'fecha_orden' => Carbon::now()->day.'/'.Carbon::now()->month.'/'.Carbon::now()->year,
            'dias_contratados' => $row->qty
        ]);
    }

    public function postHotel($row, $order_id, $post_id)
    {
        $hotel_api = Http::get(
            $this->hotel_uri . 'getHotelById/' . $row->id
        );
        $hotel = $hotel_api->json();
        if ($hotel['stock'] < $row->qty) {
            throw new Exception;
        }
        $hotel_id = DB::table('hoteles')->insertGetId([
            'nombre' => $hotel['nombre'],
            'ciudad' => $hotel['ciudad'],
            'tipo_habitacion' => $hotel['tipo_habitacion'],
            'personas' => $hotel['capacidad_personas'],
            'camas' => $hotel['camas'],
            'precio_dia' => $hotel['precio_dia'] * 100,
        ]);
        DB::table('order_products')->insert([
            'order_id' => $order_id,
            'hotel_id' => $hotel_id,
            'cantidad' => $row->qty,
            'precio' => $row->price * 100,
            'fecha_inicio_contratada' => $row->options->fecha_inicio,
            'server_id' => $hotel['server_id']
        ]);
        $new_qty = $hotel['stock'] - $row->qty;
        Http::put($this->hotel_uri . 'modificaStock/'.$row->id.'/'.$new_qty,[]);
        $this->postHotelServer($hotel, $row, $post_id);
    }

    public function postHotelServer($hotel, $row, $post_id)
    {
        Http::post($this->hotel_order_uri.'postHotelOrder', [
            '_id' => $post_id,
            'agencia_id' => '1111',
            'hotel_id' => $hotel['_id'],
            'usuario_email' => auth()->user()->email,
            'precio' => $row->price,
            'fecha_orden' => Carbon::now()->day.'/'.Carbon::now()->month.'/'.Carbon::now()->year,
            'dias_contratados' => $row->qty
        ]);
    }

    public function deleteInvalidOrders($pedidos_realizados)
    {
        foreach ($pedidos_realizados as $pedido) {
            $new_qty = $pedido['antiguo_stock'] - $pedido['cantidad'];
            if($pedido['tipo'] === 'vuelo'){
                Http::put($this->vuelo_uri . 'modificaStock/'.$pedido['producto_id'].'/'.$new_qty,[]);
                Http::delete($this->vuelo_order_uri.'deleteById/'.$pedido['id']);
            }elseif($pedido['tipo'] === 'coche'){
                Http::put($this->coche_uri . 'modificaStock/'.$pedido['producto_id'].'/'.$new_qty,[]);
                Http::delete($this->coche_order_uri.'deleteById/'.$pedido['id']);
            }else{
                Http::put($this->hotel_uri . 'modificaStock/'.$pedido['producto_id'].'/'.$new_qty,[]);
                Http::delete($this->hotel_order_uri.'deleteById/'.$pedido['id']);
            }
        }
    }

    public function paymentError()
    {
        return redirect()->route('cart.showPaymentError')->with('message','Sus credenciales fueron erroneas ');
    }

    public function showPaymentError()
    {
        return view('cart.paymentError');
    }

    public function noCredit()
    {
        return redirect()->route('cart.showPaymentError')->with('message','No dispone de credito suficiente ');
    }

}
