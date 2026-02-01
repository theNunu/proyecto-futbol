<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PhaseController;
use App\Http\Controllers\Api\TournamentController;


Route::prefix('tournaments')->group(function () {
    Route::get('', [TournamentController::class, 'index']);
    Route::get('/{tournament_id}', [TournamentController::class, 'show']);
    Route::post('/', [TournamentController::class, 'store']);
    Route::put('{tournament_id}', [TournamentController::class, 'update']);
    Route::delete('/{tournament_id}', [TournamentController::class, 'destroy']);
});

Route::prefix('phases')->group(function () {
    Route::get('', [PhaseController::class, 'index']);
    Route::post('/', [PhaseController::class, 'store']);
    Route::put('{phase_id}', [PhaseController::class, 'update']);
    Route::delete('/{phase_id}', [PhaseController::class, 'destroy']);
    Route::get('/{phase_id}', [PhaseController::class, 'show']);
    Route::get('/tournament/{tournament_id}', [PhaseController::class, 'getPhasesByTournamentId']);
});