<?php

namespace App\Http\Controllers\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Admin\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login the user and start a session.
     */
    public function login(Request $request)
    {
        // $request->validate([
        //     'email' => 'required|email',
        //     'password' => 'required'
        // ]);

        // $user = User::where('email', $request->email)->first();

        // if (! $user || ! Hash::check($request->password, $user->password)) {
        //     return response()->json(['message' => 'Invalid credentials'], 401);
        // }

        // // Supprime les tokens existants pour éviter les doublons
        // $user->tokens()->delete();

        // // Créer un nouveau token pour l'utilisateur
        // $token = $user->createToken('authToken')->plainTextToken;

        // // Retourne le token en réponse
        // return response()->json([
        //     'message' => 'Authenticated',
        //     'token' => $token,
        // ]);

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $request->session()->regenerate();

            return response()->json(['success' => 'true', 'user' => new UserResource($user)]);
        }

        throw ValidationException::withMessages([
            'message' => __('The provided credentials do not match our records.'),
        ]);
    }

    /**
     * Logout the user and destroy the session.
     */
    public function logout(Request $request)
    {
        // Supprimer le token actuel de l'utilisateur
        // $request->user()->currentAccessToken()->delete();

        // return response()->json(['message' => 'Logged out']);

        // Invalide la session active
        Auth::guard('web')->logout();

        // Invalide le token CSRF et régénère une session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Retourne un message de succès
        return response()->json(['message' => 'Logged out successfully']);
    }

    /**
     * Get the authenticated user details.
     */
    public function me(Request $request)
    {
        // récupérer l'utilisateur Authentifié
        $user = Auth::user();

        // Retourner les informations de l'utilisateur authentifié
        return response()->json(['user' => new UserResource($user)], 200);
    }
}
