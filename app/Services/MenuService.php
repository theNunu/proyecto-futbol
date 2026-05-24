<?php

namespace App\Services;

use App\Models\Menu;
use App\Repositories\MenuRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MenuService
{
    protected $menuRepository;

    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }

    public function getFullMenu()
    {
        // dd("???");
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

    public function changePosition(array $data, int $menuId)
    {
        // dd($data);
        $menu = Menu::where("menu_id", $menuId)->first();

        if (!$menu) {
            throw new NotFoundHttpException('ID del menu no encontrada.');
        }

        $step = $data['step'];

        // $operator = $step < 0 ? '<' : '>';
        // $orderBy = $step < 0 ? 'desc' : 'asc';

        // $swap = Menu::where('parent_id', $menuId)->where('order', $menu->order + $step)
        //     ->first();
        // 🔍 buscar el vecino (arriba o abajo)


        $hijos = Menu::where('parent_id', $menu->parent_id)->get();
        // dd($hijos, $data['parent_id']);
        $ourSon = null;
        foreach ($hijos as $hijo) {
            // echo "Fruta: " . $fruta . "<br>";
            if ($hijo->parent_id == $data['parent_id']) {
                $ourSon = $data['parent_id'];
            }
        }
        // dd(Menu::where('parent_id', $menu->parent_id)->get(), $data['parent_id'], $ourSon);
        $listo =$this->verificate($menuId, $ourSon, $hijos, $step, $menu);


        // dd("feel it");



        // $swap = Menu::where('parent_id', $menu->parent_id)
        //     ->where('order', $menu->order + $step)
        //     ->first();

        // dd($swap);

        // if (!$swap) {
        //     throw new NotFoundHttpException('No se puede mover. ');
        // }
        // 🔄 intercambiar posiciones
        // DB::transaction(function () use ($menu, $swap) {
        //     // dd($menu->order);
        //     $temp = $menu->order;

        //     $menu->update(['order' => $swap->order]);
        //     $swap->update(['order' => $temp]);
        // });

        return $listo;
        // return response()->json([
        //     'message' => 'Orden actualizado'
        // ]);
    }

    private function verificate($menuId, $ourSon, $hijos, $step, $menu)
    {

        $resultado = $hijos->sortBy('order')->map(function ($item) {
            return [
                'order' => $item->order,
                'name' => $item->name,
            ];
        });
        //  $hijos->sortBy('order'),

        $res = Menu::where('menu_id', $menuId)->where("parent_id", $ourSon)->first();
        // dd(
        //     $menuId,
        //     $ourSon,

        //     $resultado->toArray(),
        //     $res->toArray()
        // );

        if ($step == "+1") {
            $result = $res->order + 1;
            // return $result;

        } else if ($step == "-1") {
            $result = $res->order - 1;
            // return $result;

        }
        $hijos = Menu::where('parent_id', $menu->parent_id)->get();

        $nuevo = $res->update(['order' => $result]);
        return $nuevo;

        // dd($result, $hijos->sortBy('order')->toArray(), $res->toArray(), $nuevo);

        // dd("wazaaaa");

    }
}