<?php

use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\checkAuth;
use App\Http\Middleware\checkUserPermissions;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('productos', [ProductoController::class,'index'])
    ->middleware([checkAuth::class])
    ->name('productos.index');
Route::get('productos/create', [ProductoController::class,'create'])
    ->middleware([checkAuth::class, checkUserPermissions::class.':11'])
    ->name('productos.create');
Route::get('productos/{id}/edit', [ProductoController::class,'edit'])
    ->middleware([checkAuth::class, checkUserPermissions::class.':11'])
    ->name('productos.edit');
Route::get('productos/{id}', [ProductoController::class,'show'])
    ->middleware([checkAuth::class])
    ->name('productos.show');
Route::post('productos', [ProductoController::class,'store'])
    ->middleware([checkAuth::class, checkUserPermissions::class.':11'])
    ->name('productos.store');
Route::put('productos/{id}', [ProductoController::class,'update'])
    ->middleware([checkAuth::class, checkUserPermissions::class.':11'])
    ->name('productos.update');
Route::delete('productos/{id}', [ProductoController::class,'destroy'])
    ->middleware([checkAuth::class, checkUserPermissions::class.':111'])
    ->name('productos.destroy');

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
