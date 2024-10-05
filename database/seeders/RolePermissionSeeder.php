<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création des permissions avec descriptions
        $permissions = [
            ['name' => 'manage users', 'description' => 'Gérer les utilisateurs du système'],
            ['name' => 'manage courses', 'description' => 'Gérer les cours et leur contenu'],
            ['name' => 'view courses', 'description' => 'Voir les cours disponibles'],
            ['name' => 'create assignments', 'description' => 'Créer des devoirs pour les étudiants'],
            ['name' => 'grade assignments', 'description' => 'Noter les devoirs soumis par les étudiants'],
            ['name' => 'submit assignments', 'description' => 'Soumettre des devoirs pour évaluation'],
        ];

        // Seed des permissions avec descriptions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                ['description' => $permission['description']]
            );
        }

        // Création des rôles avec leurs permissions associées
        $roles = [
            'admin' => ['manage users', 'manage courses', 'view courses'],
            'teacher' => ['manage courses', 'create assignments', 'grade assignments'],
            'student' => ['view courses', 'submit assignments'],
        ];

        // Seed des rôles et assignation des permissions
        foreach ($roles as $role => $rolePermissions) {
            $roleModel = Role::firstOrCreate(['name' => $role]);

            // Assigner les permissions au rôle
            $roleModel->syncPermissions($rolePermissions);
        }
    }
}
