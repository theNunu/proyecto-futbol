<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\Module;
use App\Repositories\MenuRepository;
use App\Repositories\ModuleRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ModuleService
{
    protected $moduleRepository;

    public function __construct(ModuleRepository $moduleRepository)
    {
        $this->moduleRepository = $moduleRepository;
    }

    public function getFullMenu()
    {
        return $this->moduleRepository->getTree();
    }

    public function storeModule(array $data)
    {
        // 1. Generar el slug automáticamente basado en el nombre
        // $data['slug'] = Str::slug($data['name']);

        if (isset($data['parent_id'])) {
            // dd("wwww");
            $this->findParent($data['parent_id']);
            // $data['order'] = 0;
        } else {
            $data['parent_id'] = null;

        }

        return $this->moduleRepository->create($data);
    }

    private function findParent($parentId){
        $exists = Module::where('module_id', $parentId)->exists();
        
        if(!$exists){
            throw new NotFoundHttpException("El módulo padre con ID $parentId no existe.");
        }
        return $exists;
    }


}