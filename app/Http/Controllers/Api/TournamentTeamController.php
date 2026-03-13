<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTournamentTeamRequest;
// use App\Http\Requests\StoreTournamentTeamRequest;
use App\Models\TournamentTeam;
use App\Services\TournamentTeamService;
use Symfony\Component\HttpFoundation\JsonResponse;

class TournamentTeamController extends Controller
{
    public function __construct(private TournamentTeamService $service) {
        // dd('xvxvx');
    }

    public function index($tournamentId): JsonResponse{
        try {
            $tournaments = $this->service->listTeams($tournamentId);
            return $this->respondOk($tournaments);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function store(StoreTournamentTeamRequest $request): JsonResponse {
        // dd('cxv');
        try {
            $tournamentTeam = $this->service->addTeam($request->validated());
            return $this->respondOk($tournamentTeam);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function destroy(
        TournamentTeam $tournamentTeam
    ): JsonResponse {
        $this->service->removeTeam($tournamentTeam);

        return response()->json(null, 204);
    }
}
