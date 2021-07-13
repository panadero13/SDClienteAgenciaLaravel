<?php

namespace App\Http\Controllers;

use App\Models\Vuelo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Gloudemans\Shoppingcart\Facades\Cart;

class VuelosController extends Controller
{
    private $uri = 'http://192.168.1.9:3000/api/vuelos/';
    private $safe_uri = 'https://localhost:3000/api/vuelos/';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        try {
            Http::withOptions(['verify' => $this->uri]);
            $vuelos_api = Http::get($this->uri . 'getAll');
            $vuelos = $vuelos_api->json();
        }catch (Exception $ex) {
            return redirect()->route('vuelos.error')->with('error', 'El servidor de vuelos ha caido.');
        }

        return view('vuelos/index', compact('vuelos'));
    }

    public function error()
    {
        return view('vuelos.error')->with('error', 'El servidor de vuelos ha caido.');
    }

    public function storeInCart(Request $request)
    {
        try {
            $vuelo_api = Http::get(
                $this->uri . 'getVueloById/' . $request->input('vuelo_id')
            );
            $vuelo = $vuelo_api->json();
        } catch (Exception $ex) {
            return redirect()->route('vuelos.error')->with('error', 'El servidor de vuelos ha caido.');
        }


        $quantity = 1;
        if ($request->input('quantity') != 1) {
            $quantity = $request->input('quantity');
        }

        Cart::add(
            $request->input('vuelo_id'),
            'Vuelo de ' . $vuelo['origen'] . ' a ' . $vuelo['destino'],
            $quantity,
            $vuelo['precio'],
            0,
            [
                'type' => 'vuelo',
                'fecha' => $vuelo['fecha'],
                'hora' => $vuelo['hora'],
                'server_id' => $vuelo['server_id']
            ]
        );

        return redirect()->back()->with('message', 'Vuelo añadido');
    }

    public function productDetail($id)
    {
        try {
            $vuelo_api = Http::get(
                $this->uri . 'getVueloById/' . $id
            );
            $vuelo = $vuelo_api->json();
        } catch (Exception $ex) {
            return redirect()->route('vuelos.error')->with('error', 'El servidor de vuelos ha caido.');
        }

        return view('vuelos.detail', compact('vuelo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Vuelo::insert($request->all());
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vuelo  $vuelo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vuelo $vuelo)
    {
        Vuelo::destroy($vuelo->id);
    }
}
