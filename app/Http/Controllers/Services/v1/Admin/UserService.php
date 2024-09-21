<?php

namespace App\Http\Controllers\Services\v1\Admin;

use App\Http\Resources\v1\Admin\UserResource;
use App\Mail\NewUserPassword;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
        DB::beginTransaction(); // Démarrer une transaction

        try {
            // Générer un mot de passe temporaire
            $password = Str::random(10);

            // Valider les données
            $userData = [
                'cni' => $data['cni'],
                'name' => $data['name'],
                'surname' => $data['surname'],
                'email' => $data['email'],
                'phone_number' => $data['phone_number'],
                'address' => $data['address'],
                'joinedDate' => $data['joinedDate'],
                'password' => Hash::make($password),
            ];

            // Gérer l'upload de la photo si elle existe
            if (isset($data['photo']) && $data['photo']->isValid()) {
                $path = $data['photo']->store('photos', 'public');
                $userData['photo'] = $path;
            }

            // Créer l'utilisateur (UUID et rôle gérés par l'Observer si tu en as un)
            $user = User::create($userData);

            $user->syncRoles($data['role']);

            $prefix = $user->determinePrefixBasedOnRole(); // Déterminer le préfixe après le rôle
            $user->update(['uuid' => $user->generateUuid($prefix)]); // Mettre à jour l'UUID avec le préfixe correct

            // Gérer l'envoi d'email
            try {
                Mail::to($user->email)->send(new NewUserPassword($user, $password));
            } catch (\Exception $emailException) {
                // Si l'envoi d'email échoue, enregistrer l'erreur mais continuer
                Log::error('Erreur lors de l\'envoi de l\'email : ' . $emailException->getMessage());
            }

            DB::commit(); // Confirmer la transaction

            // Retourner les informations de l'utilisateur sous forme de ressource JSON
            return new UserResource($user);
        } catch (\Exception $e) {
            DB::rollBack(); // Annuler la transaction en cas d'erreur

            // Enregistrer les erreurs dans les logs
            Log::error('Erreur lors de la création de l\'utilisateur : ' . $e->getMessage());

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
                'cni' => $data['cni'],
                'name' => $data['name'],
                'surname' => $data['surname'],
                'email' => $data['email'],
                'phone_number' => $data['phone_number'],
                'address' => $data['address'],
                'photo' => $data['photo'],
                'joinedDate' => $data['joinedDate'],
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
