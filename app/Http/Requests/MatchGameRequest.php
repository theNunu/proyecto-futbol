<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class MatchGameRequest extends FormRequest
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

            'tournament_id' => 'required|exists:tournaments,tournament_id',
            'phase_id' => 'required|exists:phases,phase_id',
            'home_team_id' => 'required|exists:teams,team_id',
            'away_team_id' => 'required|exists:teams,team_id',
            // 'away_team_id' => 'required|exists:teams,team_id','different:home_team_id',
            'match_date' => 'nullable|date',
            // 'status' => ['in:scheduled,live,finished']
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
