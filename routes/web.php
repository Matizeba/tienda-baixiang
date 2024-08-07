<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

//Empleados
Route::get('/empleados', [UserController::class, 'index'])->name('users.index');
Route::get('/empleados/create', [UserController::class, 'create'])->name('users.create');
Route::post('/empleados', [UserController::class, 'store'])->name('users.store');
Route::get('/empleados/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/empleados/{user}', [UserController::class, 'update'])->name('users.update');
Route::delete('/empleados/{user}', [UserController::class, 'destroy'])->name('users.destroy');
//clientes
Route::get('/clientes', [UserController::class, 'indexClients'])->name('users2.index');
Route::get('/clientes/create', [UserController::class, 'createClient'])->name('users.createClient');
Route::post('/clientes', [UserController::class, 'storeClient'])->name('users.storeClient');
Route::get('/clientes/{user}/edit', [UserController::class, 'editClient'])->name('users.editClient');
Route::put('/clientes/{user}', [UserController::class, 'updateClient'])->name('users.updateClient');
Route::delete('/clientes/{user}', [UserController::class, 'destroyClient'])->name('users.destroyClient');


//Productos
Route::get('/products', [ProductController::class, 'Index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'Create'])->name('products.create');
Route::post('/products', [ProductController::class, 'Store'])->name('products.store');
Route::get('/products/{product}/edit', [ProductController::class, 'Edit'])->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'Update'])->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'Destroy'])->name('products.destroy');


Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
    

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
    
require __DIR__.'/auth.php';
