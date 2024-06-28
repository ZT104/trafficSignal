<?php

use App\Http\Controllers\SignalController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [SignalController ::class, 'index']);
Route::post('/start', [SignalController::class, 'start']);
Route::post('/stop', [SignalController::class, 'stop']);