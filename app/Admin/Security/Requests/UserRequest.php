<?php

namespace App\Admin\Security\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class UserRequest extends FormRequest
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

        $ruleName = 'required|string|max:30';
        return [
            'first_name' => $ruleName,
            'last_name' => $ruleName,
            'identification_type' => 'required|string|max:20',
            'identification_number' => 'required|string|max:20',
            'image_url' => 'nullable|string|max:30',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|max:10',
            // Valida que sea una fecha real, con formato Año-Mes-Día y que no sea una fecha futura
            'birth_date' => ['required', 'date', 'date_format:Y-m-d', 'before:today'],
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
