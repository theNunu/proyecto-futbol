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
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json(
            $this->service->list()
        );
    }

    public function store(StoreTournamentRequest $request): JsonResponse
    {
        try {
            $tournament = $this->service->store($request->validated());
            return $this->respondOk($tournament);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function show($tournamentId)
    {
        try {
            $tournament = $this->service->get($tournamentId);
            return $this->respondOk($tournament);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function update(StoreTournamentRequest $request, $tournament_id): JsonResponse
    {
        try {
            $updated = $this->service->update($tournament_id, $request->validated());
            return $this->respondOk($updated);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function destroy( $tournament_id): JsonResponse
    {
         try {
            $deleted = $this->service->delete($tournament_id);
            return $this->respondOk($deleted);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    //     public function deactivate($role_id){
    //     try {
    //         $role = $this->roleService->deactivate($role_id);
    //         return $this->respondOk($role);
    //     } catch (\Exception $e) {
    //         return $this->parseException($e);
    //     }
    // }
}
