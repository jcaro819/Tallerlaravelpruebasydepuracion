<?php

use App\Http\Controllers\Api\CitaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/citas', [CitaController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/citas', [CitaController::class, 'store']);
});
