<?php

namespace App\Services;

use App\Models\Tournament;
use App\Repositories\SeasonRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
class SeasonService
{
    public function __construct(private SeasonRepository $repository)
    {

    }

    public function list()
    {
        $tournaments = $this->repository->getAll();
        return $tournaments->map(function ($t) {
            return [
                'season_id' => $t->season_id,
                'name' => $t->name,
                'season' => $t->season
            ];
        });
    }
    // api respponse en los request para ser llamadados en los controladores

    public function get($id): array
    {
        if (!ctype_digit($id)) {
            throw new \InvalidArgumentException('El ID debe ser un número entero.');
        }

        $season = $this->repository->findById($id);

        if (!$season) {
            throw new NotFoundHttpException('ID del Torneo no encontrada.');
        }

        return [
            'season_id' => $season->season_id,
            'name' => $season->name,
            // 'season' => $season->season
        ];

    }

    public function store(array $data): array
    {
        $season = $this->repository->create($data);

        return $season->only([
            'season_id',
            'name',
            // 'season'
        ]);
    }

    public function update($seasonId, array $data)
    {
        if (!ctype_digit($seasonId)) {
            throw new \InvalidArgumentException('El ID debe ser un número entero.');
        }

        $season = $this->repository->findById($seasonId);

        if (!$season) {
            throw new NotFoundHttpException('ID del Torneo no encontrada.');
        }
        $result = $this->repository->update($season, $data);

        return $result->only([
            'season_id',
            'name',
            'season'
        ]);
    }

    public function delete($seasonId)
    {

        $season = $this->repository->findById($seasonId);

        if (!$season) {
            throw new NotFoundHttpException('ID del Torneo no encontrada.');
        }


        // $this->repository->delete($tournament);

        //     $producto = Producto::findOrFail($id); 
        return $season->delete();
    }
    /*
        public function deactivate($roleId)
    {
        $role = $this->roleRepository->find($roleId);

        if (!$role) {
            throw new NotFoundHttpException('Rol no encontrado.');
        }

        return $this->roleRepository->deactivate($role);
    }
    */

}
