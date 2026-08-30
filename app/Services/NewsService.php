<?php

namespace App\Services;

use App\Models\Catalog;
use App\Models\CatalogDetail;
use App\Models\File;
use App\Models\News;
use App\Models\NewsMedia;
use App\Repositories\NewsRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use InvalidArgumentException;
use PHPUnit\Framework\Constraint\IsEmpty;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
// use App\Repositories\TournamentRepository;
class NewsService
{
    const CATEGORY_NEWS = 'CATEGORY_NEWS';
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
        // dd('tilina', $data['catalog_details']);
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

        // $this->categoryExist($data['catalog_details']);

        // $this->categoryValidated(self::CATEGORY_NEWS, $data['catalog_details']);
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

    public function categoryValidated(string $CATEGORY_NAME, array $categories)
    {
        $exist = Catalog::where('key', $CATEGORY_NAME)->first();

        // $details = CatalogDetail::get();

        $catalogIds = CatalogDetail::where('catalog_id', $exist->catalog_id)->get();
        // dd('diddy',$catalogIds);
        $carro = $catalogIds->pluck('catalog_detail_id')->toArray();
        // Calcula la diferencia entre ambos arreglos
        $idsFaltantes = array_diff($categories, $carro);

        if (empty($idsFaltantes)) {
            // Todos existen
        } else {
            throw new NotFoundHttpException('Una o varias de las categorias seleccionadas no pertenecen a la Categoria de noticias. ');
            // El arreglo $idsFaltantes contiene los IDs que no están en la tabla
        }

    }

    public function categoryExist(array $categories)
    {
        $details = CatalogDetail::get();

        $catalogIds = $details->pluck('catalog_detail_id')->toArray();
        // dd('diddy',$catalogIds);

        // Calcula la diferencia entre ambos arreglos
        $idsFaltantes = array_diff($categories, $catalogIds);

        if (empty($idsFaltantes)) {
            // Todos existen
        } else {
            throw new NotFoundHttpException('Uno o varios de los id selccionados no existen. ');
            // El arreglo $idsFaltantes contiene los IDs que no están en la tabla
        }


    }

    public function infoNews(array $filtros)
    {


        // if (ctype_digit($newsId)) {
        //     throw new \InvalidArgumentException('El ID debe ser un número entero.');
        // }

        return $this->repository->infoNews($filtros);

        // if (!$news) {
        //     throw new NotFoundHttpException('ID de la noticia no encontrada.');
        // }

        // return $news;

    }

    public function addMedia(array $request, string $id)
    {

        $news = News::findOrFail($id);

        $insertRows = [];

        // IMAGEN 
        if (!empty($request['images'])) {

            foreach ($request['images'] as $imageId) {

                $insertRows[] = [
                    'new_id' => $news->news_id,
                    'file_id' => $imageId,
                    'type' => 'image',
                    'url_externo' => null,
                ];
            }
            //  dd('pdiddy', $request, $insertRows);

        }

        // VIDEOS 
        if (!empty($request['videos'])) {

            $videosNotExist = [];

            $urlNotValid = [];

            foreach ($request['videos'] as $video) {
                if (is_numeric($video)) { // CASO 1: Es un ID entero (debe existir en la tabla File) 

                    $exist = File::where('file_id', $video)->first();

                    if ($exist) { // si el id existe entonces guardar en array
                        // ✅ SI EXISTE: Lo agregamos limpio a las filas por insertar
                        $insertRows[] = [
                            'new_id' => $news->news_id,
                            'file_id' => $video,
                            'type' => 'videos', // Nota: Asegúrate si tu BD espera 'video' o 'videos'
                            'url_externo' => null,
                        ];
                    } else {
                        // ❌ NO EXISTE: Lo acumulamos en la lista de errores
                        $videosNotExist[] = $video;
                    }

                } else {    // CASO 2: Es una URL externa (no se busca en la BD, se guarda directo)

                    $regexYouTube = '/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+$/i';

                    // 2. Validamos con PHP puro dentro del Service
                    if (!$video || !filter_var($video, FILTER_VALIDATE_URL) || !preg_match($regexYouTube, $video)) {
                        // Si no es válida, lanzamos un error de validación
                        // throw ValidationException::withMessages(['video_url' => ['El enlace proporcionado debe ser una URL válida de YouTube.']]);
                        $urlNotValid[] = $video;

                    } else {
                        $insertRows[] = [
                            'new_id' => $news->news_id,
                            'file_id' => null, // No tiene ID de la tabla File
                            'type' => 'videos',
                            'url_externo' => $video, // Guardamos la URL de YouTube/Vimeo aquí
                        ];

                    }

                }

            }

        }

        // dd('pdiddy', $request, 'videos ingresados del usaurio; ', $request['videos'], 'file id (videos) que  no existen', $videosNotExist, "lo que se guarada", $insertRows);

        // INSERTAR DIRECTAMENTE EN NewsMedia

        foreach ($insertRows as $item) {
            NewsMedia::create([
                'new_id' => $item['new_id'],
                'file_id' => $item['file_id'],
                'type' => $item['type'],
                'url_externo' => $item['url_externo'] ?? null,
            ]);

        }
        // foreach ($insertRows as $row) {
        //     $news->newsWithMedia->($row)->save();
        // }
        //     $insertRows[] = [
        //         'new_id' => $news->new_id,
        //         'file_id' => $request['images'],
        //         'type' => 'image',
        //         'url_externo' => null,
        //     ];
        // }

        // return $news->load(['images', 'videos']);
    }

}
