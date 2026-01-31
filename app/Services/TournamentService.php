<?php

namespace App\Services;

use App\Models\Tournament;
use App\Repositories\TournamentRepository;
use Illuminate\Database\Eloquent\Collection;

class TournamentService
{
    public function __construct(
        private TournamentRepository $repository
    ) {}

    public function list(): Collection
    {
        return $this->repository->getAll();
    }

    public function get( $id)
    {


        return $this->repository->findById($id);
    }

    public function store(array $data): Tournament
    {
        return $this->repository->create($data);
    }

    public function update(Tournament $tournament, array $data): Tournament
    {
        return $this->repository->update($tournament, $data);
    }

    public function delete(Tournament $tournament): void
    {
        $this->repository->delete($tournament);
    }
}
