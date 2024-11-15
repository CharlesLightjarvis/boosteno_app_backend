<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class RoleRequest extends BaseRequest
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
            'name' => 'required|string|regex:/^[^\d]+$/|max:255' . $this->route('user'),
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id', // Vérifie que chaque permission existe
        ];
    }
}
