<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\VuelosController;
use App\Http\Controllers\CochesController;
use App\Http\Controllers\HotelesController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [VuelosController::class, 'index'])
    ->name('home');
Route::get('/vuelos', [VuelosController::class, 'index'])
    ->name('vuelos.index');
Route::get('/vuelos/error', [VuelosController::class, 'error'])
    ->name('vuelos.error');
Route::get('/vuelos/detail/{id}', [VuelosController::class, 'productDetail'])
    ->name('vuelos.detail');

Route::get('/coches', [CochesController::class, 'index'])
    ->name('coches.index');
Route::get('/coches/error', [CochesController::class, 'error'])
    ->name('coches.error');
Route::get('/coches/detail/{id}', [CochesController::class, 'productDetail'])
    ->name('coches.detail');

Route::get('/hoteles', [HotelesController::class, 'index'])
    ->name('hoteles.index');
Route::get('/hoteles/error', [HotelesController::class, 'error'])
    ->name('hoteles.error');
Route::get('/hoteles/detail/{id}', [HotelesController::class, 'productDetail'])
    ->name('hoteles.detail');



Route::group(['middleware' => 'auth'], function () {
    Route::get('/cart', [CartController::class, 'index'])
        ->name('cart.index');
    Route::post('/cart/storeFlight', [VuelosController::class, 'storeInCart'])
        ->name('cart.storeVuelo');
    Route::post('/cart/storeHotel', [HotelesController::class, 'storeInCart'])
        ->name('cart.storeHotel');
    Route::post('/cart/storeCar', [CochesController::class, 'storeInCart'])
        ->name('cart.storeCar');
    Route::get('/cart/decreaseCartQty/{id}', [CartController::class, 'decreaseItemQty'])
        ->name('cart.decreaseQty');
    Route::get('/cart/increaseCartQty/{id}', [CartController::class, 'increaseItemQty'])
        ->name('cart.increaseQty');
    Route::post('/cart/deleteItem/', [CartController::class, 'deleteItem'])
        ->name('cart.deleteItem');
    Route::get('/cart/error', [CartController::class, 'error'])
        ->name('cart.error');
    Route::get('/cart/checkout/', [CartController::class, 'checkout'])
        ->name('cart.checkout');
    Route::post('/cart/finishPayment/', [CartController::class, 'finishPayment'])
        ->name('cart.finishPayment');
});

require __DIR__ . '/auth.php';
