<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class UserRequest extends BaseRequest
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
            'name' => 'required|string|max:255|regex:/^[^\d]+$/', // Ajout de la règle regex pour n'autoriser que les lettres de a à z et A à Z',
            'surname' => 'required|string|max:255|regex:/^[^\d]+$/',
            'cni' => 'required|string|max:255|unique:users,cni,' . $this->route('user'), // Ajout de la règle unique pour vérifier que l'email est unique et ne correspond pas à l'email du user courant'
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->route('user'), // Ajout de la règle unique pour vérifier que l'email est unique et ne correspond pas à l'email du user courant
            'role' => 'required|exists:roles,id',
            // 'password' => 'required|string|min:8', // Ajout des règles pour le mot de passe
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Le champ nom est obligatoire.',
            'name.string' => 'Le champ nom doit être une chaîne de caractères.',
            'name.max' => 'Le champ nom ne doit pas dépasser 255 caractères.',
            'name.regex' => 'Le champ nom ne doit contenir que des lettres de a à z et A à Z.',
            'email.required' => 'Le champ email est obligatoire.',
            'email.string' => 'Le champ email doit être une chaîne de caractères.',
            'email.email' => 'Le champ email doit être une adresse email valide.',
            'email.max' => 'Le champ email ne doit pas dépasser 255 caractères.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'role.required' => 'Le champ rôle est obligatoire.',
            'role.exists' => 'Le rôle sélectionné n\'existe pas.',
            // 'password.required' => 'Le champ mot de passe est obligatoire.',
            // 'password.string' => 'Le champ mot de passe doit être une chaîne de caractères.',
            // 'password.min' => 'Le champ mot de passe doit contenir au moins 8 caractères.',
            'cni.required' => 'Le champ CNI est obligatoire.',
            'cni.string' => 'Le champ CNI doit être une chaîne de caractères.',
            'cni.max' => 'Le champ CNI ne doit pas dépasser 255 caractères.',
            'cni.unique' => 'Cette CNI est déjà utilisée.',
            'surname.required' => 'Le champ nom est obligatoire.',
            'surname.string' => 'Le champ nom doit être une chaîne de caractères.',
            'surname.max' => 'Le champ nom ne doit pas dépasser 255 caractères.',
        ];
    }
}
