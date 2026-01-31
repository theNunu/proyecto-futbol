<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTournamentRequest;
use App\Models\Tournament;
use App\Services\TournamentService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class TournamentController extends Controller
{
    // use ApiResponse;
    public function __construct(
        private TournamentService $service
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(
            $this->service->list()
        );
    }

    public function store(StoreTournamentRequest $request): JsonResponse
    {
        $tournament = $this->service->store($request->validated());

        return response()->json($tournament, 201);
    }

    public function show($tournamentId)
    {
        try {
            $tournament = $this->service->get($tournamentId);
            return $this->respondOk($tournament);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
        // $tournament = $this->service->get($tournamentId);

        //  return response()->json($tournament, 200);
        // // return response()->json($tournament);
    }

    

    

    public function update(StoreTournamentRequest $request,Tournament $tournament): JsonResponse {
        $updated = $this->service->update($tournament, $request->validated());

        return response()->json($updated);
    }

    public function destroy(Tournament $tournament): JsonResponse
    {
        $this->service->delete($tournament);

        return response()->json(null, 204);
    }
}
