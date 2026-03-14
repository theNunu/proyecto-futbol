<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MatchGameRequest;
use App\Models\GameMatch;
use App\Services\MatchService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
class MatchGameController extends Controller
{

    public function __construct(private MatchService $service)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $tournamentId = $request->query('tournament_id');

        return response()->json(
            $this->service->listByTournament($tournamentId)
        );
    }

    public function store(
        MatchGameRequest $request
    ): JsonResponse {
        $match = $this->service->create(
            $request->validated()
        );

        return response()->json($match, 201);
    }

    public function update(
        MatchGameRequest $request,
        GameMatch $match
    ): JsonResponse {
        $updated = $this->service->update(
            $match,
            $request->validated()
        );

        return response()->json($updated);
    }

    public function destroy(
        GameMatch $match
    ): JsonResponse {
        $this->service->delete($match);

        return response()->json(null, 204);
    }
}
