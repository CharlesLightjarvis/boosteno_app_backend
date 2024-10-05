<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PermissionRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        if (!$user || !$user->hasRole('administrateur')) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Récupérer l'id du Role actuel via la route /roles/{role}
        $roleId = $this->route('role');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[^\d]+$/', // Ajout de la règle regex pour interdire les chiffres
                Rule::unique('roles', 'name')->ignore($roleId),
            ]
        ];
    }
}
