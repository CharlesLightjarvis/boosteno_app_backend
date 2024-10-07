<?php

namespace App\Http\Controllers\Services\v1\Admin;

use App\Http\Resources\v1\Admin\ClasseResource;
use App\Models\Classe;
use App\Models\User;
use Exception;

class ClasseService
{
    public function getAllClasses()
    {
        $classes = Classe::with(['teacher', 'students', 'levels'])->get();  // Charger les relations
        return ClasseResource::collection($classes);
    }

    public function getClasseById($id)
    {
        $classe = Classe::with(['teacher', 'students', 'levels'])->findOrFail($id);  // Charger les relations
        return new ClasseResource($classe);
    }

    /**
     * Créer une nouvelle classe et attacher les niveaux.
     */
    public function createClasse($data)
    {
        try {
            // Vérifier si l'utilisateur a le rôle d'enseignant
            $teacher = User::findOrFail($data['user_id']);
            if (!$teacher->hasRole('teacher')) {
                throw new Exception("L'utilisateur assigné n'a pas le rôle d'enseignant.");
            }

            // Créer la classe
            $classe = Classe::create($data);

            // Attacher les niveaux à la classe
            if (isset($data['levels'])) {
                $classe->levels()->sync($data['levels']);
            }

            // Retourner la classe créée sous forme de resource JSON
            return response()->json(new ClasseResource($classe->load(['teacher', 'students', 'levels'])), 201);
        } catch (Exception $e) {
            // Retourner une erreur JSON
            return response()->json([
                'error' => 'Création de classe échouée',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Mettre à jour une classe existante et les niveaux.
     */
    public function updateClasse($data, $id)
    {
        $classe = Classe::findOrFail($id);

        // Mettre à jour la classe
        $classe->update($data);

        // Mettre à jour les niveaux associés
        if (isset($data['levels'])) {
            $classe->levels()->sync($data['levels']);
        }

        return new ClasseResource($classe->load(['teacher', 'students', 'levels']));
    }
    /**
     * Supprimer une classe.
     */
    public function deleteClasse($id)
    {
        $classe = Classe::findOrFail($id);
        $classe->delete();

        return new ClasseResource($classe->load(['teacher', 'students', 'levels']));
    }
}
