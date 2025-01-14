<?php

use Illuminate\Support\Facades\Route;
use app\http\Controllers\DealsController;
//use app\http\Controllers\DashboardController;
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
use App\Http\Controllers\DashboardController;


Route::get('/dashboard', [DashboardController::class, 'index']);

Route::get('/', function () {
    return view('welcome');
});
Route::get('/product', function () {
    return view('product');
});
Route::get('admin/product', function () {
    return view('admin.products.index');
});
Route::get('/product_details', function () {
    return view('product_details');
});
//Route::get('/admin', [DashboardController::class, 'index'])->name('dashboard');

//Route::get('/admin', function () {

    //return view('admin.dashboard');
//});
Route::get('/shop', function () {
    return view('shop');
});
Route::get('/test', function () {
    return "Test route working!";
});



Route::get('/cart', function () {
    return view('cart');
});

Route::get('/about', function () {
    return view('about');
});
Route::get('/deals', [App\Http\Controllers\DealsController::class, 'index'])->name('deals');
