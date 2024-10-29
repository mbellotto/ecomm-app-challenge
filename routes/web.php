<?php

use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\checkUserPermissions;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

// Route::resource('productos', ProductoController::class);

Route::get('productos', [ProductoController::class,'index'])->name('productos.index');
Route::get('productos/create', [ProductoController::class,'create'])->middleware(checkUserPermissions::class.':operator')->name('productos.create');
Route::get('productos/{id}/edit', [ProductoController::class,'edit'])->middleware(checkUserPermissions::class.':operator')->name('productos.edit');
Route::get('productos/{id}', [ProductoController::class,'show'])->middleware(checkUserPermissions::class.':operator')->name('productos.show');
Route::post('productos', [ProductoController::class,'store'])->middleware(checkUserPermissions::class.':operator')->name('productos.store');
Route::put('productos/{id}', [ProductoController::class,'update'])->middleware(checkUserPermissions::class.':operator')->name('productos.update');
Route::delete('productos/{id}', [ProductoController::class,'destroy'])->middleware(checkUserPermissions::class.':admin')->name('productos.destroy');

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
