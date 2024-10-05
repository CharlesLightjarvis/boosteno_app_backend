<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ClasseRequest extends FormRequest
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
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'number_session' => ['required', 'integer', 'min:1'],
            'presential' => ['required', 'boolean'],
            'status' => ['required', 'in:ongoing,completed,suspended'], // Doit correspondre à une des valeurs de ton Enum
            'user_id' => ['required', 'exists:users,id'], // Vérifie que l'enseignant existe
            'levels' => ['required', 'array'], // Tableau d'IDs pour les niveaux
            'levels.*' => ['exists:levels,id'], // Chaque niveau doit exister dans la table 'levels'
        ];
    }
}
