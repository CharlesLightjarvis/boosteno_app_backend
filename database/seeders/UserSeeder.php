<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::findByName('admin');
        $teacherRole = Role::findByName('teacher');
        $studentRole = Role::findByName('student');

        // Création des utilisateurs avec assignation des rôles
        $admin = User::create([
            'name' => 'Admin',
            'surname' => 'Administrator',
            'cni' => 'AA01CD',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole($adminRole);
        $admin->update(['uuid' => $this->generateUuid('AD')]);

        $teacher = User::create([
            'name' => 'Teacher',
            'surname' => 'Professor',
            'cni' => 'AA08DT',
            'email' => 'teacher@example.com',
            'password' => bcrypt('password'),
        ]);
        $teacher->assignRole($teacherRole);
        $teacher->update(['uuid' => $this->generateUuid('TH')]);

        $student = User::create([
            'name' => 'Student',
            'surname' => 'Learner',
            'cni' => 'AA09GT',
            'email' => 'student@example.com',
            'password' => bcrypt('password'),
        ]);
        $student->assignRole($studentRole);
        $student->update(['uuid' => $this->generateUuid('ST')]);
    }

    /**
     * Générer un UUID basé sur un mélange de timestamp et de partie aléatoire
     */
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
        } while ($exists); // Si l'UUID existe, on régénère un autre identifiant jusqu'à en trouver un unique

        // 6. Retourner l'UUID unique
        return $uuid;
    }
}
