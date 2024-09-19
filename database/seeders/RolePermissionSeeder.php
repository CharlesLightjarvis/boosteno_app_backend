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
        // Création des permissions
        $permissions = [
            'manage users', // Par exemple pour l'admin
            'manage courses', // Par exemple pour l'admin et teacher
            'view courses',   // Pour le student
            'create assignments', // Pour le teacher
            'grade assignments',  // Pour le teacher
            'submit assignments', // Pour le student
        ];

        // Seed des permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Création des rôles
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
