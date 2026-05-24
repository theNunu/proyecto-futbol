<?php

namespace App\Repositories;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Collection;

class MenuRepository
{
    /**
     * Obtiene todos los menús raíz con sus hijos cargados recursivamente.
     */
    public function getTree(): Collection
    {
        return Menu::with('allChildren')
            ->whereNull('parent_id') // Solo los padres principales
            ->orderBy('order')
            ->orderBy('name', 'asc')
            ->get();
    }

    public function create(array $data): Menu
    {
        return Menu::create($data);
    }
    
    // Aquí irían métodos como find($id), update($id, $data), etc.
}