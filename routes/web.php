<?php

use App\Http\Controllers\ActividadController;
use App\Http\Controllers\CuotaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PortalSocioController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('socios', SocioController::class)->middleware('auth');
// Route::get('/socios/{id}', [SocioController::class, 'show']);

Route::post('/socios/{socio}/pagar', [SocioController::class, 'pagar'])
    ->name('socios.pagar');

Route::get('/portal/{token}', [PortalSocioController::class, 'show'])->name('portal.socio');
Route::post('/portal/{token}/pagar/cuota/{cuota}', [PortalSocioController::class, 'pagarCuota'])->name('portal.pagar.cuota');

Route::post('/webhook/mercadopago', [PagoController::class, 'webhook'])->name('mercadopago.webhook');

Route::post('/pagos', [PagoController::class, 'store']);

Route::get('/portal/success', function () {
    return "Pago exitoso (esperando confirmación...)";
})->name('portal.success');

Route::get('/portal/failure', function () {
    return "Pago fallido";
})->name('portal.failure');

Route::get('/portal/pending', function () {
    return "Pago pendiente";
})->name('portal.pending');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
});

// Route::resource('actividades', ActividadController::class)
//     ->middleware('auth');

Route::resource('actividades', ActividadController::class)
    ->parameters([
        'actividades' => 'actividad'
    ])
    ->middleware('auth');

Route::get('/actividades/{actividad}/dashboard', [ActividadController::class, 'dashboard'])->middleware('auth');

// Route::middleware('auth')->group(function () {
//     Route::resource('actividades.cuotas', CuotaController::class);
// });

Route::middleware('auth')->group(function () {
    Route::resource('actividades.cuotas', \App\Http\Controllers\CuotaController::class);
});

require __DIR__ . '/auth.php';
