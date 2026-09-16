<?php

namespace App\Services;

use App\Repositories\PlayerRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PlayerService
{
    public function __construct(private PlayerRepository $repository) {}

    public function getAll(Request $request)
    {
        return $this->repository->getAll($request);
    }

    public function getById(string $playerId)
    {
        $player = $this->repository->findById($playerId);
        if (! $player) {
            throw new NotFoundHttpException('Jugador no encontrado.');
        }

        return $player;
    }

    public function store(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(array $data, string $playerId)
    {
        $player = $this->repository->findById($playerId);
        if (! $player) {
            throw new NotFoundHttpException('Jugador no encontrado.');
        }

        return $this->repository->update($player, $data);
    }

    public function delete(string $playerId)
    {
        $player = $this->repository->findById($playerId);
        if (! $player) {
            throw new NotFoundHttpException('Jugador no encontrado.');
        }

        $this->repository->delete($player);
    }
}
