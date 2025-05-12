<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Enregistrement d'un nouvel utilisateur
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'UserName' => 'required|string|max:255',
            'UserPassword' => 'required',
            'UserPhone' => 'nullable|string|max:20',
            'UserEmail' => 'required|string|email|max:255|unique:TUsers',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $userData = [
                'UserName' => $request->UserName,
                'UserEmail' => $request->UserEmail,
                'UserPhone'=>$request->UserPhone,
                'UserPassword' => Hash::make($request->UserPassword),
            ];

            $user = User::create($userData);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur enregistré avec succès',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'enregistrement : ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Échec de l\'enregistrement'
            ], 500);
        }
    }


    /**
     * Connexion de l'utilisateur
     */

     public function login(Request $request){
    $validator = Validator::make($request->all(), [
        'UserEmail' => 'required|string', // on accepte email ou téléphone
        'UserPassword' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur de validation',
            'errors' => $validator->errors()
        ], 422);
    }

    $identifier = $request->input('UserEmail');
    $password = $request->input('UserPassword');

    // Détermine si c'est un email ou un téléphone
    $field = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'UserEmail' : 'UserPhone';

    // Recherche de l'utilisateur par email ou téléphone
    $user = User::where($field, $identifier)->first();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Utilisateur non trouvé'
        ], 404);
    }

    if (!Hash::check($password, $user->UserPassword)) {
        return response()->json([
            'success' => false,
            'message' => 'Mot de passe incorrect'
        ], 401);
    }

    try {
        $token = $user->createToken('auth_token')->plainTextToken;

        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie',
            'data' => [
                'user' => $user,
                'role' => $user->Role,
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Erreur de connexion : ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Échec de la connexion'
        ], 500);
    }
}


    /**
     * Récupérer le profil utilisateur
     */
    public function profile(Request $request)
    {
        try {
            $user = $request->user();
            return response()->json([
                'success' => true,
                'message' => 'Profil utilisateur récupéré avec succès',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur de récupération du profil : ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Échec de la récupération du profil'
            ], 500);
        }
    }

    /**
     * Déconnexion de l'utilisateur
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Déconnexion réussie'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur de déconnexion : ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Échec de la déconnexion'
            ], 500);
        }
    }

    /**
     * Changement de mot de passe
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();

            if (!Hash::check($request->current_password, $user->UserPassword)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le mot de passe actuel est incorrect'
                ], 401);
            }

            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mot de passe changé avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur de changement de mot de passe : ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Échec du changement de mot de passe'
            ], 500);
        }
    }


    /**
     * Réinitialisation du mot de passe
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'UserEmail' => 'required|email',
            'UserPassword' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $status = Password::reset(
                $request->only('UserEmail', 'UserPassword', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'UserPassword' => Hash::make($password)
                    ])->setRememberToken(Str::random(60));

                    $user->save();

                    event(new PasswordReset($user));
                }
            );

            return $status === Password::PASSWORD_RESET
                ? response()->json(['success' => true, 'message' => 'Mot de passe réinitialisé avec succès'])
                : response()->json(['success' => false, 'message' => 'Impossible de réinitialiser le mot de passe'], 400);

        } catch (\Exception $e) {
            Log::error('Erreur de réinitialisation : ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Échec de la réinitialisation'
            ], 500);
        }
    }

    /**
     * Suppression du compte utilisateur
     */
    public function deleteAccount(Request $request)
    {
        try {
            $user = $request->user();

            // Supprimer tous les tokens d'accès
            $user->tokens()->delete();

            // Supprimer l'utilisateur
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Compte supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur de suppression du compte : ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Échec de la suppression du compte'
            ], 500);
        }
    }
}
