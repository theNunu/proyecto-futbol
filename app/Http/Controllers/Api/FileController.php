<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CatalogService;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Models\File;
use Illuminate\Http\Request;
class FileController extends Controller
{
    public function __construct(private CatalogService $service)
    {
    }

    public function store(Request $request)
    {
        // 1. Validar estrictamente (puedes ajustar según requieras PDF, videos, etc.)
        $request->validate([
            'file' => 'required|file|max:10240', // Máximo 10MB temporalmente general
        ]);

        $uploadedFile = $request->file('file');

        // 2. Guardar en el disco configurado (ej: 'public' o 's3')
        // $path = $uploadedFile->store('uploads/temp', 'public');
        // Asegúrate de pasar 'public' como segundo parámetro:
        $path = $uploadedFile->store('uploads/temp', 'public');

        // 3. Registrar en la base de datos
        $file = File::create([
            'name' => $uploadedFile->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $uploadedFile->getClientMimeType(),
            'size' => $uploadedFile->getSize(),
            // fileable_id y fileable_type se quedan en NULL por ahora
        ]);

        // 4. Retornar el ID al Frontend
        return response()->json(['file_id' => $file->file_id, 'url' => asset('storage/' . $path)], 201);
    }

    public function index()
    {
        try {
            $catalogs = $this->service->getAll();
            return $this->respondOk($catalogs);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

}
