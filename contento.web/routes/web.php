<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsuarioController;
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

Route::get('/', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('home');
Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard')->middleware(['auth', 'verified']);
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
Route::middleware(['auth', 'role:SuperAdministrador'])->group(function () {
    Route::resource('usuarios', UsuarioController::class);
});
Route::middleware(['auth', 'role:SuperAdministrador'])->group(function () {
    Route::resource('roles', RolesController::class);
});
Route::middleware(['auth', 'role:SuperAdministrador'])->group(function () {
    Route::resource('permisos', PermissionsController::class);
});
require __DIR__ . '/auth.php';
