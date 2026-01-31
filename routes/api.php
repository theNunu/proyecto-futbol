<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('tournaments')->group(function () {
    Route::get('', [\App\Http\Controllers\Api\TournamentController::class, 'index']);
    Route::get('/{tournament_id}', [\App\Http\Controllers\Api\TournamentController::class, 'show']);

    
    Route::post('/', [\App\Http\Controllers\Api\TournamentController::class, 'store']);

    Route::put('/{tournament_id}', [\App\Http\Controllers\Api\TournamentController::class, 'update']);
    Route::delete('/{tournament_id}', [\App\Http\Controllers\Api\TournamentController::class, 'destroy']);
});
