<?php

namespace App\Services;

use App\Models\News;
use App\Repositories\NewsRepository;
use Carbon\Carbon;
use InvalidArgumentException;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\IsEmpty;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// use App\Repositories\TournamentRepository;
class NewsService
{
    public function __construct(private NewsRepository $repository)
    {

    }

    public function getAll(Request $request)
    {
        // dd($request);
        return $this->repository->getAll($request);
    }

    public function store(array $data): News
    {
        // 1. Convertir las strings a instancias de Carbon para compararlas
        $inicio = Carbon::parse($data['begin_date']);
        $fin = Carbon::parse($data['end_date']);

        // 2. Aplicar la condicional de negocio
        if ($fin->lessThanOrEqualTo($inicio)) {
            throw new InvalidArgumentException('La fecha de fin debe ser mayor a la fecha de inicio.');
        }

        if (empty(($data['is_active']))) {
            $data['is_active'] = true;
            //  dd("mi activo", $data['is_active']);
        }
        // dd("la noticia: ",$data);
        return $this->repository->create($data);

    }


    public function update(array $data, int $newsId): News
    {

        if (!ctype_digit($newsId)) {
            throw new \InvalidArgumentException('El ID debe ser un número entero.');
        }

        $news = $this->repository->findById($newsId);

        if (!$news) {
            throw new NotFoundHttpException('ID de la noticia no encontrada.');
        }

        // 1. Convertir las strings a instancias de Carbon para compararlas
        $inicio = Carbon::parse($data['begin_date']);
        $fin = Carbon::parse($data['end_date']);

        // 2. Aplicar la condicional de negocio
        if ($fin->lessThanOrEqualTo($inicio)) {
            throw new InvalidArgumentException('La fecha de fin debe ser mayor a la fecha de inicio.');
        }

        // if (empty(($data['is_active']))) {
        //     $data['is_active'] = true;
        //     //  dd("mi activo", $data['is_active']);
        // }
        // dd("la noticia: ",$data);
        // return $this->repository->create($data);
        return $this->repository->update($news, $data);

    }

    public function getById(int $newsId)
    {

        if (ctype_digit($newsId)) {
            throw new \InvalidArgumentException('El ID debe ser un número entero.');
        }

        $news = $this->repository->findById($newsId);

        if (!$news) {
            throw new NotFoundHttpException('ID de la noticia no encontrada.');
        }

        return $news;

    }

}
