<?php

use Illuminate\Support\Facades\Route;

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
    return view('index');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// rutas para el admin
Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name( name:'admin.index')
->middleware (middleware: 'auth' );

// rutas para el admin - profesionales
Route::get('/admin/profesionales', [App\Http\Controllers\ProfesionalController::class, 'index'])->name( name:'admin.profesionales.index')
->middleware (middleware: 'auth' );

// rutas para el admin - Clientes
Route::get('/admin/clientes', [App\Http\Controllers\ClienteController::class, 'index'])->name( name:'admin.clientes.index')
->middleware (middleware: 'auth' );

// rutas para el admin - Servicios
Route::get('/admin/servicios', [App\Http\Controllers\ServiciosController::class, 'index'])->name( name:'admin.servicios.index')
->middleware (middleware: 'auth' );

// rutas para el admin - Horario
Route::get('/admin/horario', [App\Http\Controllers\HorarioController::class, 'index'])->name( name:'admin.horario.index')
->middleware (middleware: 'auth' );

// rutas para el admin - Agenda
Route::get('/admin/agenda', [App\Http\Controllers\AgendaController::class, 'index'])->name( name:'admin.agenda.index')
->middleware (middleware: 'auth' );