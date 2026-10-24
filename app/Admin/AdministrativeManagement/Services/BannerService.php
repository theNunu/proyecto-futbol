<?php

namespace App\Admin\AdministrativeManagement\Services;

use App\Admin\AdministrativeManagement\Repository\BannerRepository;
use App\Models\Banner;
use App\Models\Catalog;
use App\Models\CatalogDetail;
use App\Models\News;
// use App\Repositories\BannerRepository;
use App\Repositories\NewsRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use InvalidArgumentException;
use PHPUnit\Framework\Constraint\IsEmpty;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// use App\Repositories\TournamentRepository;
class BannerService
{
    public function __construct(private BannerRepository $repository)
    {

    }

    public function getAll(Request $request)
    {
        // dd($request);
        return $this->repository->getAll($request);
    }

    public function store(array $data): Banner
    {
        return $this->repository->create($data);

    }


    public function update(array $data, int $bannerId): Banner
    {

        // if (!ctype_digit($bannerId)) {
        //     throw new \InvalidArgumentException('El ID debe ser un número entero.');
        // }

        $banner = $this->repository->findById($bannerId);

        if (!$banner) {
            throw new NotFoundHttpException('ID de la noticia no encontrada.');
        }

        return $this->repository->update($banner, $data);

    }

    public function getById(int $bannerId)
    {

        if (ctype_digit($bannerId)) {
            throw new \InvalidArgumentException('El ID debe ser un número entero.');
        }

        $banner = $this->repository->findById($bannerId);

        if (!$banner) {
            throw new NotFoundHttpException('ID de la noticia no encontrada.');
        }

        return $banner;

    }

}
