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


    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::patch('/categories/{id}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggleStatus');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('/categories/export', [CategoryController::class, 'exportToExcel'])->name('categories.export');

});

Route::middleware(['auth', 'verified'])->group(function () {
    
});



// Rutas para la gestión de ventas
Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');                // Lista todas las ventas
Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');        // Muestra el formulario para crear una nueva venta
Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');                // Almacena una nueva venta
Route::get('/sales/{sale}/edit', [SaleController::class, 'edit'])->name('sales.edit');        // Muestra el formulario para editar una venta existente
Route::put('/sales/{sale}', [SaleController::class, 'update'])->name('sales.update');         // Actualiza una venta existente
Route::delete('/sales/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy');    // Elimina una venta existente
            // Muestra los detalles de una venta específica
Route::get('/sales/view', [SaleController::class, 'view'])->name('sales.view');              // Muestra una vista específica (puede ser para filtros, listado, etc.)
Route::get('/sales/export', [SaleController::class, 'exportToExcel'])->name('sales.export'); // Exporta ventas a Excel
Route::get('/sales/{sale}/details', [SaleController::class, 'getDetails'])->name('sales.details');
Route::get('/sales/{id}/details', [SaleController::class, 'showDetails']);
// routes/web.php

Route::get('/sales/{id}', [SaleController::class, 'show'])->name('sales.show');





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
