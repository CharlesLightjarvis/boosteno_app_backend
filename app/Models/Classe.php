<?php

namespace App\Models;

use App\Enums\ClassStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'number_session',
        'presential',
        'status',
        'user_id',
        'uuid',
    ];

    // Cast le champ 'status' en Enum
    protected $casts = [
        'status' => ClassStatus::class,
    ];
    /**
     * Vérifier si la classe est en cours.
     */
    public function isOngoing()
    {
        return $this->status === ClassStatus::Ongoing;
    }

    /**
     * Vérifier si la classe est terminée.
     */
    public function isCompleted()
    {
        return $this->status === ClassStatus::Completed;
    }

    /**
     * Vérifier si la classe est suspendue.
     */
    public function isSuspended()
    {
        return $this->status === ClassStatus::Suspended;
    }

    /**
     * Relation avec l'enseignant (un utilisateur avec le rôle d'enseignant).
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation avec les étudiants (plusieurs utilisateurs avec le rôle d'étudiant).
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'classe_user', 'classe_id', 'user_id')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'student');  // Rôle étudiant géré par Spatie
            });
    }

    /**
     * Relation avec les niveaux (levels).
     */
    public function levels()
    {
        return $this->belongsToMany(Level::class, 'classe_level', 'classe_id', 'level_id');
    }
}
