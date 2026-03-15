<?php

namespace App\Repositories;

use App\Models\Phase;
use App\Models\Catalog;
use Illuminate\Database\Eloquent\Collection;

class CatalogRepository
{

    public function getAll()
    {
        return Catalog::get();
    }

    public function create(array $data): Catalog
    {
        return Catalog::create([
            'name' => $data['name'],
            'key' => $data['key'],
        ]);
    }

    public function update(Phase $phase, array $data): Phase
    {
        $phase->update($data);
        return $phase;
    }

    public function delete(Phase $phase): void
    {
        $phase->delete();
    }
}
