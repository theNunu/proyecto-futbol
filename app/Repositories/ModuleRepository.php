<?php

namespace App\Repositories;

use App\Models\Menu;
use App\Models\Module;
use Illuminate\Database\Eloquent\Collection;

class ModuleRepository
{
    /**
     * Obtiene todos los menús raíz con sus hijos cargados recursivamente.
     */
    public function getTree(): Collection
    {
        return Module::with('children')
            ->whereNull('parent_id') // Solo los padres principales
            ->orderBy('order')
            ->orderBy('name', 'asc')
            ->get();
    }

    public function create(array $data): Module
    {
        return Module::create($data);
    }
    
    // Aquí irían métodos como find($id), update($id, $data), etc.
}