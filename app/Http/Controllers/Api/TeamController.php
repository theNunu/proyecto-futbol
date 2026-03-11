<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTeamRequest;
use App\Models\Team;
use App\Services\TeamService;
use Illuminate\Http\JsonResponse;
class TeamController extends Controller
{
    public function __construct(private TeamService $service)
    {
    }

    public function index(): JsonResponse
    {
        try {
            $teams = $this->service->list();
            return $this->respondOk($teams, 'Se han traido todos los equipos del sistema. ');
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function store(StoreTeamRequest $request): JsonResponse
    {
        try {
            $team = $this->service->store($request->validated());
            return $this->respondOk($team);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }

    }

    public function update(StoreTeamRequest $request, $team_id): JsonResponse {
        // $updated = $this->service->update($team, $request->validated());

        // return response()->json($updated);

        try {
            $updated = $this->service->update($team_id, $request->validated());
            return $this->respondOk($updated);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function destroy(Team $team): JsonResponse
    {
        $this->service->delete($team);

        return response()->json(null, 204);
    }
}
