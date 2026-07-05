<?php

namespace App\Repositories;

use App\Models\News;
use Illuminate\Http\Request;
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
            })
            ->get(); // Al final se ejecuta la consulta
        return $news;
        // return News::get();
    }

    public function create(array $data): News
    {
        //  dd($data);
        return News::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'summary' => $data['summary'],
            'begin_date' => $data['begin_date'],
            'end_date' => $data['end_date'],
            'is_active' => $data['is_active'],
        ]);
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



}















