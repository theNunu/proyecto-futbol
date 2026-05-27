<?php

namespace App\Repositories;

use App\Models\News;

class NewsRepository
{

    public function getAll()
    {
        return News::get();
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
        ]);
    }

}















