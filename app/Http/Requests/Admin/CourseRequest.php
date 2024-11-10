<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class CourseRequest extends BaseRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Limite la taille des images à 2 Mo
            'pdf' => 'nullable|mimes:pdf|max:10240', // Limite la taille des fichiers PDF à 10 Mo
            'class_ids' => 'nullable|array',
            'class_ids.*' => 'exists:classes,id',
        ];
    }

    /**
     * Messages d'erreurs personnalisés (facultatif).
     */
    public function messages()
    {
        return [
            'name.required' => 'Le nom du cours est obligatoire.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'Le format de l\'image doit être l\'un des suivants : jpeg, png, jpg, gif.',
            'pdf.mimes' => 'Le fichier doit être un PDF.',
            'class_ids.*.exists' => 'L\'une des classes sélectionnées n\'existe pas.',
        ];
    }
}
