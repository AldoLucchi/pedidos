<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Inertia\Inertia;

/* Controllers */
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\DeliveryRateController;
use App\Http\Controllers\IvaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/

Route::get('/', [PedidoController::class, 'create'])->name('pedidos.create');
Route::post('/pedidos', [PedidoController::class, 'store'])->name('pedidos.store');

Route::get('/productos/seleccionar', [ProductoController::class, 'seleccionarProducto'])
    ->name('productos.seleccionar');

Route::post('/procesar-pedido', [PedidoController::class, 'procesarPedido'])
    ->name('pedido.procesar');

/*
|--------------------------------------------------------------------------
| Authenticated routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /* Dashboard */
    Route::get('/dashboard', [PedidoController::class, 'index'])
        ->middleware('verified')
        ->name('dashboard');

    /* Pedidos */
    Route::resource('pedidos', PedidoController::class)
        ->only(['index', 'show', 'destroy']);

    Route::put('/pedidos/{pedido}/estado', [PedidoController::class, 'updateEstado'])
        ->name('pedidos.updateEstado');

    Route::get('/check-new-pedidos', [PedidoController::class, 'checkNewPedidos'])
        ->name('pedidos.check');

    /* Productos */
    Route::resource('productos', ProductoController::class)
        ->except(['show']);

    /* Payment Methods */
    Route::prefix('payment-methods')->group(function () {
        Route::get('/', [PaymentMethodController::class, 'index'])
            ->name('paymentMethods.index');

        Route::get('/edit/{id}', [PaymentMethodController::class, 'edit'])
            ->name('paymentMethods.edit');

        Route::put('/{id}', [PaymentMethodController::class, 'update'])
            ->name('paymentMethods.update');
    });

    /* Delivery Rate */
    Route::get('/delivery-rate', fn () => Inertia::render('DeliveryRate'));
    Route::get('/delivery-rate/get', [DeliveryRateController::class, 'getRate']);
    Route::post('/delivery-rate/update', [DeliveryRateController::class, 'updateRate'])
        ->name('delivery-rate.update');

    /* IVA */
    Route::get('/iva-rate/get', [IvaController::class, 'getRate']);
    Route::post('/iva-rate/update', [IvaController::class, 'updateRate']);

    /* Exchange Rate */
    Route::get('/exchange-rate', fn () => Inertia::render('ExchangeRate'));
    Route::get('/exchange-rate/get', [ExchangeRateController::class, 'getRate']);
    Route::post('/exchange-rate/update', [ExchangeRateController::class, 'updateRate'])
        ->name('exchange-rate.update');

    /* Users */
    Route::resource('users', UserController::class)
        ->only(['index', 'destroy']);

    /* Profile */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Auth routes
|--------------------------------------------------------------------------
*/

Route::get('/welcome', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

require __DIR__.'/auth.php';


