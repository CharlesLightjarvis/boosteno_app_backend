<?php

namespace App\Observers;

use App\Models\Classe;

class ClasseObserver
{
    public function creating(Classe $classe)
    {
        // Générer l'UUID qui commence par "CL" suivi de 4 ou 5 chiffres
        $classe->uuid = $this->generateUuid();
    }

    /**
     * Générer un UUID qui commence par "CL" suivi de 4 à 5 chiffres.
     */
    private function generateUuid(): string
    {
        do {
            // Générer 4 à 5 chiffres aléatoires
            $randomDigits = mt_rand(1000, 99999);

            // Préfixer avec "CL"
            $uuid = 'CL' . $randomDigits;

            // Vérifier si cet UUID existe déjà dans la base de données
            $exists = Classe::where('uuid', $uuid)->exists();
        } while ($exists);

        // Retourner l'UUID unique
        return $uuid;
    }
}
