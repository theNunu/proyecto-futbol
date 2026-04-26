<?php

namespace App\Services;

use App\Repositories\MenuRepository;
use Illuminate\Support\Str;

class MenuService
{
    protected $menuRepository;

    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }

    public function getFullMenu()
    {
        return $this->menuRepository->getTree();
    }

    public function storeMenu(array $data)
    {
        // 1. Generar el slug automáticamente basado en el nombre
        $data['slug'] = Str::slug($data['name']);

        // 2. Si no viene un orden, lo ponemos al final (opcional)
        if (!isset($data['order'])) {
            $data['order'] = 0; 
        }

        return $this->menuRepository->create($data);
    }
}