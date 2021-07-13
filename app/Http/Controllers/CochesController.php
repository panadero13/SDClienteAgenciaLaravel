<?php

namespace App\Http\Controllers;

use App\Models\Coche;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Gloudemans\Shoppingcart\Facades\Cart;

class CochesController extends Controller
{
    private $uri = 'http://192.168.1.9:3001/api/coches/';
    private $safe_uri = 'https://localhost:3001/api/coches/';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $coches_api = Http::get($this->uri . 'getAll');
            $coches = $coches_api->json();
        } catch (Exception $ex) {
            return redirect()->route('coches.error')->with('message', 'El servidor de coches ha caido.');
        }
        return view('coches.index', compact('coches'));
    }

    public function error()
    {
        return view('coches.error')->with('message', 'El servidor de coches ha caido.');
    }

    public function storeInCart(Request $request)
    {
        try {
            $coche_api = Http::get(
                $this->uri . 'getCocheById/' . $request->input('coche_id')
            );
            $coche = $coche_api->json();
        } catch (Exception $ex) {
            return redirect()->route('coches.error')->with('message', 'El servidor de coches ha caido.');
        }

        $quantity = 1;
        if ($request->input('quantity') != 1) {
            $quantity = $request->input('quantity');
        }
        $fecha_inicio = Carbon::now();
        if ($request->input('fecha_inicio') != null) {
            $fecha_inicio = $request->input('fecha_inicio');
        } else {
            $hoy = Carbon::today();
            $mes = $hoy->month < 10 ? '0' . $hoy->month : $hoy->month;
            $dia = $hoy->day < 10 ? '0' . $hoy->day : $hoy->day;
            $fecha_inicio = $hoy->year . '-' . $mes . '-' . $dia;
        }

        Cart::add(
            $request->input('coche_id'),
            $coche['marca'] . '-' . $coche['modelo'],
            $quantity,
            $coche['precio'],
            0,
            [
                'marca' => $coche['marca'],
                'modelo' => $coche['modelo'],
                'type' => 'coche',
                'plazas' => $coche['plazas'],
                'disponible' => $coche['disponible'],
                'fecha_inicio' => $fecha_inicio,
                'server_id' => $coche['server_id']
            ]
        );

        return redirect()->back()->with('message', 'Coche añadido');
    }

    public function productDetail($id)
    {
        try {
            $coche_api = Http::get(
                $this->uri . 'getCocheById/' . $id
            );
            $coche = $coche_api->json();
        } catch (Exception $ex) {
            return redirect()->route('coches.error')->with('message', 'El servidor de coches ha caido.');
        }

        return view('coches.detail', compact('coche'));
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
     * Display the specified resource.
     *
     * @param  \App\Models\Coche  $coche
     * @return \Illuminate\Http\Response
     */
    public function show(Coche $coche)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Coche  $coche
     * @return \Illuminate\Http\Response
     */
    public function edit(Coche $coche)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Coche  $coche
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coche $coche)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Coche  $coche
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coche $coche)
    {
        //
    }
}
