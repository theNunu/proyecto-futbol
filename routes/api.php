<?php

use App\Admin\Controllers\AuthController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\MenuController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PhaseController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\TournamentController;
use App\Http\Controllers\Api\TournamentTeamController;

Route::get('/test', function () {
    return response()->json([
        'message' => 'API funcionando'
    ]);
});

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


// Route::apiResource('teams', TeamController::class)
//     ->except(['show']);
Route::prefix('teams')->group(function () {
    Route::get('', [TeamController::class, 'index']);
    Route::post('/', [TeamController::class, 'store']);
    Route::put('{team_id}', [TeamController::class, 'update']);
    Route::delete('/{team_id}', [TeamController::class, 'destroy']);
    Route::get('/{team_id}', [TeamController::class, 'show']);
    // Route::get('/tournament/{tournament_id}', [TeamController::class, 'getPhasesByTournamentId']);
});

Route::prefix('tournament-teams')->group(function () {
    Route::get('', [TournamentTeamController::class, 'getAll']);
    Route::get('get-teams/{tournament_id}', [TournamentTeamController::class, 'getTeams']);
    Route::get('{tournament_id}', [TournamentTeamController::class, 'getTeamsbyTournamentId']);
    Route::post('/', [TournamentTeamController::class, 'store']);
    // Route::put('{team_id}', [TeamController::class, 'update']);
    // Route::delete('/{team_id}', [TeamController::class, 'destroy']);
    // Route::get('/{team_id}', [TeamController::class, 'show']);
});

Route::prefix('catalogs')->group(function () {
    Route::get('', [CatalogController::class, 'index']);
    Route::get('catalog-detail', [CatalogController::class, 'getAllDetailsWithCatalog']);
    Route::post('/', [CatalogController::class, 'store']);
    Route::post('catalog-detail', [CatalogController::class, 'createDetail']);

    // Route::put('{team_id}', [TeamController::class, 'update']);
    // Route::delete('/{team_id}', [TeamController::class, 'destroy']);
    // Route::get('/{team_id}', [TeamController::class, 'show']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/menus', [MenuController::class, 'index']);
Route::post('/menus', [MenuController::class, 'store']);

Route::prefix('menus')->group(function () {

    Route::get('', [MenuController::class, 'index']);
    Route::post('', [MenuController::class, 'store']);

});
