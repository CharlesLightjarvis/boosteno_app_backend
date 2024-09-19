<?php

namespace App\Http\Controllers\Services\v1\Admin;

use App\Http\Resources\v1\Admin\UserResource;
use App\Mail\NewUserPassword;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class UserService
{
    public function getAllUsers()
    {
        $users = User::all();
        return UserResource::collection($users);
    }

    public function getUserById($id)
    {
        $user =  User::findOrFail($id);
        return new UserResource($user);
    }



    public function createUser($data)
    {
        try {

            // Générer un mot de passe temporaire
            $password = Str::random(10);

            // Valider les données
            $userData = [
                'name' => $data['name'],
                'surname' => $data['surname'],
                'cni' => $data['cni'],
                'email' => $data['email'],
                'password' => Hash::make($password), // Cryptage du mot de passe
            ];

            // Créer l'utilisateur sans UUID
            $user = User::create($userData);

            // Assigner les rôles
            $user->assignRole($data['role']);

            // Générer et mettre à jour l'UUID en fonction du rôle
            $prefix = $user->determinePrefixBasedOnRole();
            $user->update(['uuid' => $user->generateUuid($prefix)]);

            // Envoyer un email avec le mot de passe temporaire
            Mail::to($user->email)->send(new NewUserPassword($user, $password));

            return new UserResource($user);
        } catch (\Exception $e) {
            // Retourner une réponse d'erreur
            return response()->json(['error' => 'User creation failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateUser($data, $id)
    {
        try {
            $user = User::findOrFail($id);

            // Vérifiez si le rôle a changé
            $roleChanged = $data['role'] !== $user->getRoleNames()->first();

            // Valider les données à mettre à jour
            $updateData = [
                'name' => $data['name'],
                'surname' => $data['surname'],
                'cni' => $data['cni'],
                'email' => $data['email'],
            ];

            // Si un nouveau mot de passe est fourni, le crypter et l'ajouter aux données à mettre à jour
            if (!empty($data['password'])) {
                $updateData['password'] = bcrypt($data['password']);
            }

            // Mettre à jour les données de l'utilisateur
            $user->update($updateData);

            // Si le rôle a changé, synchronisez les rôles et générez un nouvel UUID
            if ($roleChanged) {
                // Synchroniser les rôles
                $user->syncRoles($data['role']);

                // Générer et mettre à jour l'UUID en fonction du nouveau rôle
                $prefix = $user->determinePrefixBasedOnRole(); // Générer le nouveau préfixe basé sur le nouveau rôle
                $user->update(['uuid' => $user->generateUuid($prefix)]);
            }

            return new UserResource($user);
        } catch (\Exception $e) {
            // Retourner une réponse d'erreur
            return response()->json(['error' => 'User update failed', 'message' => $e->getMessage()], 500);
        }
    }


    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->roles()->detach();
        $user->delete();
        return new UserResource($user);
    }
}
