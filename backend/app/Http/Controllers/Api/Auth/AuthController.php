<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use App\Models\Client;
use App\Models\Intervenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        try {
            // Nettoyer les données : convertir les chaînes vides en null
            $data = $request->all();
            foreach ($data as $key => $value) {
                if ($value === '' || $value === null) {
                    $data[$key] = null;
                }
            }
            $request->merge($data);

            $validated = $request->validate([
                'nom' => 'required|string|max:100',
                'prenom' => 'required|string|max:100',
                'email' => 'required|string|email|max:150|unique:utilisateur,email',
                'password' => 'required|string|min:8',
                'confirmPassword' => 'nullable|same:password',
                'telephone' => 'nullable|string|max:20',
                'type' => 'nullable|string|in:client,intervenant',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors(),
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Créer l'utilisateur
            $userId = DB::table('utilisateur')->insertGetId([
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'] ?? null,
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'telephone' => $validated['telephone'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ], 'id');

            $userType = $validated['type'] ?? 'client';

            // Créer le client ou l'intervenant selon le type
            if ($userType === 'client') {
                DB::table('client')->insert([
                    'id' => $userId,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } elseif ($userType === 'intervenant') {
                DB::table('intervenant')->insert([
                    'id' => $userId,
                    'is_active' => false, // Nouveau intervenant en attente de validation
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            // Recharger l'utilisateur avec Eloquent pour avoir le modèle complet
            $user = Utilisateur::find($userId);
            
            if (!$user) {
                throw new \Exception('Utilisateur non trouvé après création');
            }
            
            // Charger les relations
            $user->load(['client', 'intervenant', 'admin']);

            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'message' => 'Utilisateur créé avec succès',
                'user' => $user,
                'token' => $token,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de l\'inscription: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'message' => 'Erreur lors de la création de l\'utilisateur',
                'error' => config('app.debug') ? $e->getMessage() : 'Une erreur est survenue. Veuillez réessayer.',
            ], 500);
        }
    }

    /**
     * Login a user
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors(),
            ], 422);
        }

        $user = Utilisateur::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Les identifiants sont incorrects.',
                'errors' => [
                    'email' => ['Les identifiants sont incorrects.'],
                ],
            ], 401);
        }

        // Révoquer les anciens tokens
        $user->tokens()->delete();

        $token = $user->createToken('auth-token')->plainTextToken;

        // Charger les relations
        $user->load(['client', 'intervenant', 'admin']);

        return response()->json([
            'message' => 'Connexion réussie',
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * Logout the authenticated user
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie',
        ]);
    }

    /**
     * Get the authenticated user
     */
    public function user(Request $request)
    {
        return response()->json([
            'user' => $request->user()->load(['client', 'intervenant', 'admin']),
        ]);
    }

    /**
     * Update the authenticated user's profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'nom' => 'sometimes|string|max:100',
            'prenom' => 'sometimes|string|max:100',
            'telephone' => 'sometimes|string|max:20',
            'photoPath' => 'sometimes|string',
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Profil mis à jour avec succès',
            'user' => $user,
        ]);
    }

    /**
     * Change the authenticated user's password
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Le mot de passe actuel est incorrect.'],
            ]);
        }

        $user->update([
            'password' => $validated['password'],
        ]);

        return response()->json([
            'message' => 'Mot de passe changé avec succès',
        ]);
    }
}
