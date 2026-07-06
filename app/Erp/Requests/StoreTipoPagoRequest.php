<?php

namespace App\Erp\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class StoreTipoPagoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Habilitado para pruebas
    }

    public function rules(): array
    {
        // dd("oui");
        return [
            'nombre' => 'required|string|unique:tipo_pagos,nombre|max:100',
            'porcentaje_comision' => 'required|numeric|min:0|max:100',
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