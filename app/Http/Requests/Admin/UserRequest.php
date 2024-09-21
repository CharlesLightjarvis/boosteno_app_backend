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
            'name' => 'required|string|max:255|regex:/^[^\d]+$/',
            'surname' => 'required|string|max:255|regex:/^[^\d]+$/',
            'cni' => 'required|string|max:255|unique:users,cni,' . $this->route('user'),
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->route('user'),
            'phone_number' => 'required|string|max:255|unique:users,phone_number,' . $this->route('user'),
            'address' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'joinedDate' => 'required|date',
            'role' => 'required|exists:roles,name',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le champ nom est obligatoire.',
            'surname.required' => 'Le champ prénom est obligatoire.',
            'cni.required' => 'Le champ CNI est obligatoire.',
            'email.required' => 'Le champ email est obligatoire.',
            'phone_number.required' => 'Le champ numéro de téléphone est obligatoire.',
            'address.required' => 'Le champ adresse est obligatoire.',
            'joinedDate.required' => 'Le champ date d\'adhésion est obligatoire.',
            'role.required' => 'Le champ rôle est obligatoire.',
            'name.regex' => 'Le champ nom ne doit contenir que des lettres.',
            'surname.regex' => 'Le champ prénom ne doit contenir que des lettres.',
            'cni.unique' => 'Cette CNI est déjà utilisée.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'phone_number.unique' => 'Ce numéro de téléphone est déjà utilisé.',
            'role.exists' => 'Le rôle sélectionné n\'existe pas.',
            'photo.image' => 'Le champ photo doit être une image.',
            'photo.mimes' => 'Le champ photo doit être une image de type jpeg, png, jpg, gif, svg.',
            'photo.max' => 'Le champ photo ne doit pas dépasser 2048 Ko.',
            'joinedDate.date' => 'Le champ date d\'adhésion doit être une date valide.',
        ];
    }
}
