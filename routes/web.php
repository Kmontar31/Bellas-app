<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ServiciosController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\ProfesionalesController;
use App\Http\Controllers\HorariosController;
use App\Http\Controllers\AgendaController;

use App\Http\Controllers\Controller;

Route::get('/', function () {
    return view('home');
});

// Rutas de debug temporales para comprobar vistas sin pasar por Auth/BD
Route::get('/_debug/horarios', function () {
    return view('admin.horarios.index');
});

Route::get('/_debug/agenda', function () {
    return view('admin.agenda.index');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Grupo de rutas para el admin
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'index'])->name('index');

    // Calendario admin y endpoint de eventos
    Route::get('agenda/calendar', [AgendaController::class, 'calendar'])->name('agenda.calendar');
    Route::get('agenda/events', [AgendaController::class, 'events'])->name('agenda.events');
    
    // Vista de calendario de horarios
    Route::get('horarios/calendar', [HorariosController::class, 'calendar'])->name('horarios.calendar');

    // Reprogramar cita (drag/drop)
    Route::put('agenda/{agenda}/reschedule', [AgendaController::class, 'updateEvent'])->name('agenda.reschedule');

    // Resources
    Route::resources([
        'servicios' => ServiciosController::class,
        'clientes' => ClientesController::class,
        'profesionales' => ProfesionalesController::class,
        'horarios' => HorariosController::class,
        'agenda' => AgendaController::class,
    ]);

    // Modales y reprogramación manual
    Route::get('agenda/{agenda}/modal', [AgendaController::class, 'showModal'])->name('agenda.modal');
    Route::get('agenda/{agenda}/modal-reprogramar', [AgendaController::class, 'showReprogramarModal'])->name('agenda.modal.reprogramar');
    Route::put('agenda/{agenda}/update-from-modal', [AgendaController::class, 'updateFromModal'])->name('agenda.update.modal');

    // Bloqueos: crear/eliminar días no disponibles
    Route::post('agenda/blocks', [AgendaController::class, 'blocksStore'])->name('agenda.blocks.store');
    Route::delete('agenda/blocks/{block}', [AgendaController::class, 'blocksDestroy'])->name('agenda.blocks.destroy');
});

// Rutas públicas para booking externo
Route::get('reservar', [AgendaController::class, 'publicCreate'])->name('booking.create');
Route::post('reservar', [AgendaController::class, 'publicStore'])->name('booking.store');
Route::get('reservar/gracias', [AgendaController::class, 'publicThanks'])->name('booking.thanks');
