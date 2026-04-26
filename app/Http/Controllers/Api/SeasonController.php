<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSeasonRequest;
use App\Http\Requests\StoreTournamentRequest;
use App\Models\Season;
use App\Models\Tournament;
use App\Services\SeasonService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class SeasonController extends Controller
{
    // use ApiResponse;
    public function __construct(
        private SeasonService $service
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json(
            $this->service->list()
        );
    }

    public function store(StoreSeasonRequest $request): JsonResponse
    {
        try {
            $season = $this->service->store($request->validated());
            return $this->respondOk($season);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function show($seasonId)
    {
        try {
            $season = $this->service->get($seasonId);
            return $this->respondOk($season);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function update(StoreSeasonRequest $request, $seasonId): JsonResponse
    {
        try {
            $updated = $this->service->update($seasonId, $request->validated());
            return $this->respondOk($updated);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function destroy( $seasonId): JsonResponse
    {
         try {
            $deleted = $this->service->delete($seasonId);
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
