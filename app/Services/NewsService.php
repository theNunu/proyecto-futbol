<?php

namespace App\Services;

use App\Models\News;
use App\Repositories\NewsRepository;

// use App\Repositories\TournamentRepository;
class NewsService
{
    public function __construct(private NewsRepository $repository)
    {

    }

    public function getAll()
    {
        return  $this->repository->getAll();
    }

    public function store(array $data): News
    {
        return $this->repository->create($data);
    
    }

}
