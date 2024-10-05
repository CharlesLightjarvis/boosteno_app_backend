<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'cni',
        'email',
        'password',
        'status',
        'phone_number',
        'photo',
        'address',
        'joinedDate',
        'uuid'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relation avec les classes enseignées (un utilisateur avec rôle enseignant).
     */
    public function teacherClasses()
    {
        return $this->hasMany(Classe::class, 'user_id')->whereHas('roles', function ($query) {
            $query->where('name', 'teacher');
        });
    }

    /**
     * Relation avec les classes auxquelles l'utilisateur (étudiant) est inscrit.
     */
    public function studentClasses()
    {
        return $this->belongsToMany(Classe::class, 'classe_user', 'user_id', 'classe_id')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'student');
            });
    }

    // Méthode pour déterminer le préfixe en fonction du rôle
    public function determinePrefixBasedOnRole()
    {
        if ($this->hasRole('admin')) {
            return 'AD';
        } elseif ($this->hasRole('teacher')) {
            return 'TH';
        } elseif ($this->hasRole('student')) {
            return 'ST';
        }
        return 'UK'; // Préfixe par défaut si aucun rôle n'est trouvé
    }

    // Générer un UUID basé sur un mélange de timestamp et de partie aléatoire
    public function generateUuid($rolePrefix)
    {
        do {
            // 1. Obtenir un timestamp réduit (par exemple, les 3 derniers chiffres du timestamp)
            $timestamp = substr(now()->timestamp, -3); // Ex: "123"

            // 2. Générer un identifiant aléatoire (par exemple, 2 chiffres)
            $randomPart = mt_rand(10, 99); // Ex: "45"

            // 3. Combiner le timestamp et la partie aléatoire
            $uniqueId = $timestamp . $randomPart; // Ex: "12345"

            // 4. Générer l'UUID complet avec le préfixe du rôle
            $uuid = $rolePrefix . '-' . $uniqueId;

            // 5. Vérifier dans la base de données si l'UUID existe déjà
            $exists = User::where('uuid', $uuid)->exists();

            // Si l'UUID existe, on régénère un autre identifiant jusqu'à ce qu'on en trouve un unique
        } while ($exists);

        // 6. Retourner l'UUID unique
        return $uuid;
    }
}
