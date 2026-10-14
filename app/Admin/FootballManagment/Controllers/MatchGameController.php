<?php

namespace App\Admin\FootballManagment\Controllers;

use App\Admin\FootballManagment\Requests\MatchGameRequest;
use App\Admin\FootballManagment\Requests\UpdateMatchGameRequest;
use App\Admin\FootballManagment\Services\MatchService;
use App\Http\Controllers\Controller;
use App\Models\GameMatch;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
class MatchGameController extends Controller
{

    public function __construct(private MatchService $matchService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $tournamentId = $request->query('tournament_id');

        return response()->json(
            $this->matchService->listByTournament($tournamentId)
        );
    }

    // public function store(
    //     MatchGameRequest $request
    // ): JsonResponse {
    //     $match = $this->service->create(
    //         $request->validated()
    //     );

    //     return response()->json($match, 201);
    // }

    public function show(int $matchGameId): JsonResponse
    {
        try {
            $matchGame = $this->matchService->getById($matchGameId);
            return $this->respondOk($matchGame, "Partido encontrado exitosamente");
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function store(MatchGameRequest $request): JsonResponse
    {
        try {
            // dd("tilin");
            $match = $this->matchService->create($request->validated());
            return $this->respondOk($match, "Partido creado correctamente");
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function update(UpdateMatchGameRequest $request, int $matchId): JsonResponse
    {
        try {
            // dd("tilin");
            $match = $this->matchService->update($request->validated(), $matchId);
            return $this->respondOk($match, "Partido actualizado correctamente");
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function destroy(
        GameMatch $match
    ): JsonResponse {
        $this->matchService->delete($match);

        return response()->json(null, 204);
    }
}
