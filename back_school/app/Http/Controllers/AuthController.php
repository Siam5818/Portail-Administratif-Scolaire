<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\EleveWelcomeNotification;
use App\Notifications\TuteurWelcomeNotification;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
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
