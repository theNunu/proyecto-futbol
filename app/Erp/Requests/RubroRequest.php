<?php

namespace App\Erp\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class RubroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Habilitado para pruebas
    }

    public function rules(): array
    {
        // dd("oui");
        return [
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string|max:20',
            'porcentaje_impuesto' => 'nullable|numeric|min:0|max:100',
            'porcentaje_retencion' => 'nullable|numeric|min:0|max:100',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Errores de validación',
            'errors' => $validator->errors(),
        ], 422));
    }

}