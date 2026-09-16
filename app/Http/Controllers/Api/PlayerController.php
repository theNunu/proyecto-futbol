<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlayerRequest;
use App\Http\Requests\UpdatePlayerRequest;
use App\Services\PlayerService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class PlayerController extends Controller
{
    public function __construct(private PlayerService $service) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $players = $this->service->getAll($request);

            return $this->respondOk($players, 'Jugadores obtenidos exitosamente');
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function getById(string $player_id): JsonResponse
    {
        try {
            $player = $this->service->getById($player_id);

            return $this->respondOk($player, 'Jugador encontrado exitosamente');
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function store(StorePlayerRequest $request): JsonResponse
    {
        try {
            $player = $this->service->store($request->validated());

            return $this->respondCreated($player, 'Jugador creado exitosamente');
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function update(UpdatePlayerRequest $request, string $player_id): JsonResponse
    {
        try {
            $player = $this->service->update($request->validated(), $player_id);

            return $this->respondOk($player, 'Jugador actualizado exitosamente');
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function destroy(string $player_id): JsonResponse
    {
        try {
            $this->service->delete($player_id);

            return $this->respondOk(null, 'Jugador eliminado exitosamente');
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }
}
