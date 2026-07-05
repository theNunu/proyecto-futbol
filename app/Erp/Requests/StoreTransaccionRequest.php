<?php

namespace App\Erp\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class StoreTransaccionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Habilitado para pruebas
    }

    public function rules(): array
    {
        // dd("oui");
        return [
            'rubro_id' => 'required|exists:rubros,rubro_id',
            'tipo_pago_id' => 'required|exists:tipo_pagos,tipo_pago_id',
            'descripcion' => 'required|string|max:255',
            'tipo' => 'required|in:ingreso,egreso,abono',
            'monto_bruto' => 'required|numeric|min:0.01',
            'fecha_transaccion' => 'required|date',
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