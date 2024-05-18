<?php

use App\Http\Controllers\Api\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('ticket/all', [TicketController::class, 'index']);
Route::middleware("auth:sanctum")->post('ticket', [TicketController::class, 'store']);
Route::middleware("auth:sanctum")->post('sendticket', [TicketController::class, 'send']);
Route::middleware("auth:sanctum")->post('modifyticket/{codigo}', [TicketController::class, 'update']);
Route::middleware("auth:sanctum")->post('readCsf', [TicketController::class, 'readCsf']);
Route::post('login', [TicketController::class, 'login']);
