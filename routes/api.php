<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Route::get('tickets', function(){
//     return Ticket::all();
// });

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
