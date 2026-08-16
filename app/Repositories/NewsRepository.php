<?php

namespace App\Repositories;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class NewsRepository
{

    public function getAll(Request $request)
    {
        // 1. Capturamos el parámetro 'nombre' (si no existe, será null)
        $searchNew = $request->query('title');
        $filtrarEstado = $request->query('is_active');
        // 2. Construimos la consulta dinámica
        $news = News::query()
            ->when($searchNew, function ($query, $nombre) {
                // Este bloque solo corre si $searchNew NO está vacío
                return $query->where('title', 'LIKE', '%' . $nombre . '%');
            })

            // 2. Filtro por estado (solo si no está vacío)
            ->when($request->filled('is_active'), function ($query) use ($filtrarEstado) {
                // Convertimos el string "true"/"false" a booleano real de PHP
                $valorBooleano = filter_var($filtrarEstado, FILTER_VALIDATE_BOOLEAN);

                return $query->where('is_active', $valorBooleano);
            })->with('files')
            ->get(); // Al final se ejecuta la consulta
        return $news;
        // return News::get();
    }

    public function create(array $data): News
    {
        //  dd($data);
        // return News::create([
        //     'title' => $data['title'],
        //     'description' => $data['description'],
        //     'summary' => $data['summary'],
        //     'begin_date' => $data['begin_date'],
        //     'end_date' => $data['end_date'],
        //     'file_id' => $data['file_id'],
        //     'is_active' => $data['is_active'],
        // ]);
        // 1. Creamos la noticia
        $news = News::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'summary' => $data['summary'],
            'begin_date' => $data['begin_date'],
            'end_date' => $data['end_date'],
            'file_id' => $data['file_id'],
            'is_active' => $data['is_active'],
        ]);

        // 2. Guardamos en la tabla pivote 'category_news' usando sync()
        if (isset($data['catalog_details'])) {
            $news->catalog_details()->sync($data['catalog_details']);
        }

        return $news;
    }

    public function findById(int $newsId): ?News
    {
        return News::where('news_id', $newsId)->first();
    }

    public function update(News $news, array $data): News
    {
        $news->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'summary' => $data['summary'],
            'begin_date' => $data['begin_date'],
            'end_date' => $data['end_date'],
            // 'is_active' => $data['is_active'],
        ]);
        return $news;
    }

    public function infoNews(array $filtros)
    {

        // 1. Extraemos los valores asegurando su existencia
        $name = $filtros['name'] ?? null;
        $description = $filtros['description'] ?? null;
        $is_active = $filtros['is_active'] ?? null;
        $category_id = $filtros['p_category_id'] ?? null;

        // 2. Manejo preciso del booleano para el SP
     

        // // Pasamos ambos filtros al procedimiento almacenado de Postgres
        // return DB::select('SELECT * FROM sp_obtener_rubro(?, ?)', [$nombre, $tipo]);

        // 2. IMPORTANTE: El orden en este array debe ser EXACTAMENTE: ID, Nombre, Tipo
        return DB::select('SELECT * FROM sp_search_noticias(?, ?, ?, ?)', [
            $name,       // Va a la posición 1 (integer)
            $description,   // Va a la posición 2 (varchar)
            $is_active,      // Va a la posición 3 (varchar)
            $category_id
        ]);

    }


}















