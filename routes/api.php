<?php

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function(){ return "Hello world..."; });
// Route::post('/add-cart-item', 'App\Http\Controllers\CartController@addToCart');
Route::post('/add-cart-item', [CartController::class, 'addToCart']);
Route::post('/delete-cart-item', [CartController::class, 'deleteFromCart']);
Route::post('/checkout', [CartController::class, 'checkout']);