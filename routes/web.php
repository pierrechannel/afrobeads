<?php

use Illuminate\Support\Facades\Route;
use app\http\Controllers\DealsController;
use app\http\Controllers\DashboardController;
//use app\http\Controllers\admin\UsersController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/admin', [DashboardController::class, 'index'])->name('dashboard');


Route::get('/shop', function () {
    return view('shop');
});


Route::get('/cart', function () {
    return view('cart');
});

Route::get('/about', function () {
    return view('about');
});
Route::get('/deals', [App\Http\Controllers\DealsController::class, 'index'])->name('deals');
