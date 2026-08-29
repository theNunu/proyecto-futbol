<?php

namespace App\Http\Requests;

use App\FileRelationType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class StoreMediaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'images' => 'nullable|array',
            'images.*' => 'integer|exists:files,file_id',
            'videos' => 'nullable|array',

            // 'videos.*' => [
            //     'required',
            //     function ($attribute, $value, $fail) {
            //         // Si no es un número entero válido y tampoco es una URL válida, falla.
            //         if (!filter_var($value, FILTER_VALIDATE_INT) && !filter_var($value, FILTER_VALIDATE_URL)) {
            //             $fail("El elemento en {$attribute} debe ser un ID entero o una URL válida.");
            //         }
            //     },
            // ],
            // 'videos.*' => 'integer',
            // 'images' => 'nullable|integer|exists:files,file_id',
            // 'videos' => 'nullable',
        ];

        // 
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
