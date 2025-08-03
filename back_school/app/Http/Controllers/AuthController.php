<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function recents()
    {
        try {
            return response()->json($this->authService->getUtilisateursRecents());
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Erreur lors de la récupération des utilisateurs récentes.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Authentification utilisateur + token Sanctum
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = $this->authService->login($data['email'], $data['password']);

        if (!$user) {
            return response()->json(['message' => 'Identifiants invalides'], 401);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Connexion reussie',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    /**
     * Changement de mot de passe sécurisé
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|string|min:8',
        ]);

        $user = Auth::user();
        $success = $this->authService->changePassword($user, $request->new_password);

        return response()->json([
            'message' => $success ? 'Mot de passe modifié' : 'Échec de la modification'
        ]);
    }

    public function logout()
    {
        $user = Auth::user();

        /** @var \Laravel\Sanctum\PersonalAccessToken|null $token */
        $token = $user->currentAccessToken();

        if ($token) {
            $token->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Déconnexion réussie'
            ]);
        }

        return response()->json([
            'status' => 'failed',
            'message' => 'Déconnexion non réussie, aucun token trouvé'
        ]);
    }
}
