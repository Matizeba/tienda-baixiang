<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\TestEmail;

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::post('dashboard/update-password', [DashboardController::class, 'updatePassword'])
    ->middleware(['auth', 'verified'])
    ->name('password.update');
//Empleados


Route::middleware(['auth', 'verified'])->group(function () {
    // Rutas para UserController
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/export', [UserController::class, 'exportToExcel'])->name('users.export');

    
    // Rutas para ClientController
    Route::get('/clients', [ClientController::class, 'indexClient'])->name('clients.index');
    Route::get('/clients/create', [ClientController::class, 'createClient'])->name('clients.create');
    Route::post('/clients', [ClientController::class, 'storeClient'])->name('clients.storeClient');
    Route::get('/clients/{user}/edit', [ClientController::class, 'editClient'])->name('clients.edit');
    Route::put('/clients/{user}', [ClientController::class, 'updateClient'])->name('clients.update');
    Route::patch('/clients/{id}/toggle-status', [ClientController::class, 'toggleClientStatus'])->name('clients.toggleStatus');
    Route::delete('/clients/{user}', [ClientController::class, 'destroy'])->name('clients.destroy');
    Route::get('/clients/export', [ClientController::class, 'exportToExcel'])->name('clients.export');

    // Rutas para ProductController
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::patch('/products/{id}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggleStatus');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/products/view', [ProductController::class, 'view'])->name('products.view');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/export', [ProductController::class, 'exportToExcel'])->name('products.export');
    
    Route::get('/units', [ProductController::class, 'unitsIndex'])->name('units.index');
    Route::get('/units/create', [ProductController::class, 'unitsCreate'])->name('units.create');
    Route::post('/units', [ProductController::class, 'unitsStore'])->name('units.store');
    Route::get('/units/{unit}/edit', [ProductController::class, 'unitsEdit'])->name('units.edit');
    Route::put('/units/{unit}', [ProductController::class, 'unitsUpdate'])->name('units.update');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::patch('/categories/{id}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggleStatus');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('/categories/export', [CategoryController::class, 'exportToExcel'])->name('categories.export');

    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::delete('/sales/{id}', [SaleController::class, 'destroy'])->name('sales.destroy');
    Route::get('/sales/{id}', [SaleController::class, 'show'])->name('sales.show');
    Route::get('/sales/get-units', [SaleController::class, 'getUnits'])->name('sales.getUnits');
    Route::get('/sales/{id}/edit', [SaleController::class, 'edit'])->name('sales.edit');
    Route::put('/sales/{id}', [SaleController::class, 'update'])->name('sales.update');
    Route::get('sales/{id}/receipt', [SaleController::class, 'printReceipt'])->name('sales.receipt');
    Route::post('/sales/{id}/change-status', [SaleController::class, 'changeStatus'])->name('sales.changeStatus');


});

Route::middleware(['auth', 'verified'])->group(function () {
    
});




// Mostrar los detalles de una venta específica


// Eliminar venta



Route::middleware(['auth'])->group(function () {
   

});



Route::view('/', 'welcome');
/*Route::get('/',function(){
    return redirect()->route('login');
});*/

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
    

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
    
require __DIR__.'/auth.php';
