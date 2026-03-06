<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LinkController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('links', [LinkController::class, 'index']);
    Route::post('links', [LinkController::class, 'store']);
    Route::get('links/{link}', [LinkController::class, 'show']);
    Route::delete('links/{link}', [LinkController::class, 'destroy']);
});
