<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Gloudemans\Shoppingcart\Facades\Cart;
use Exception;
use Illuminate\Support\Carbon;

class HotelesController extends Controller
{

    private $uri = 'http://localhost:3002/api/hoteles/';
    private $safe_uri = 'https://localhost:3002/api/hoteles/';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $hoteles_api = Http::get($this->uri . 'getAll');
            $hoteles = $hoteles_api->json();
        } catch (Exception $ex) {
            return redirect()->route('hoteles.error')->with('error', 'El servidor de hoteles ha caido.');
        }

        return view('hoteles.index', compact('hoteles'));
    }

    public function error()
    {
        return view('hoteles.error')->with('error', 'El servidor de hoteles ha caido.');
    }

    public function storeInCart(Request $request)
    {
        try {
            $hotel_api = Http::get(
                $this->uri . 'getHotelById/' . $request->input('hotel_id')
            );
            $hotel = $hotel_api->json();
        } catch (Exception $ex) {
            return redirect()->route('hoteles.error')->with('error', 'El servidor de hoteles ha caido.');
        }


        $quantity = 1;
        if ($request->input('quantity') != 1) {
            $quantity = $request->input('quantity');
        }
        $fecha_inicio = Carbon::now();
        if ($request->input('fecha_inicio') != null) {
            $fecha_inicio = $request->input('fecha_inicio');
        }else{
            $hoy = Carbon::today();
            $mes = $hoy->month < 10 ? '0' . $hoy->month : $hoy->month;
            $dia = $hoy->day < 10 ? '0' . $hoy->day : $hoy->day;
            $fecha_inicio = $hoy->year . '-' . $mes . '-' . $dia;
        }
        Cart::add(
            $request->input('hotel_id'),
            'Hotel ' . $hotel['nombre'] . ' en ' . $hotel['ciudad'],
            $quantity,
            $hotel['precio_dia'],
            0,
            [
                'type' => 'hotel',
                'nombre' => $hotel['nombre'],
                'ciudad' => $hotel['ciudad'],
                'camas' => $hotel['camas'],
                'disponible' => $hotel['disponible'],
                'capacidad_personas' => $hotel['capacidad_personas'],
                'tipo_habitacion' => $hotel['tipo_habitacion'],
                'fecha_inicio' => $fecha_inicio,
            ]
        );

        return redirect()->back()->with('message', 'Habitación añadida');
    }

    public function productDetail($id)
    {
        try {
            $hotel_api = Http::get(
                $this->uri . 'getHotelById/' . $id
            );
            $hotel = $hotel_api->json();
        } catch (Exception $ex) {
            return redirect()->route('hoteles.error')->with('error', 'El servidor de hoteles ha caido.');
        }

        return view('hoteles.detail', compact('hotel'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Hotel  $hotel
     * @return \Illuminate\Http\Response
     */
    public function edit(Hotel $hotel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hotel  $hotel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hotel $hotel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hotel  $hotel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hotel $hotel)
    {
        //
    }
}
