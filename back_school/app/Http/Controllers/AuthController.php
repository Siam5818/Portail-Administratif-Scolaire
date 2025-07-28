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
     * Inscription d'un élève avec un tuteur
     * Seul un admin peut inscrire un élève
     */
    public function registerEleveAvecTuteur(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Accès refusé'], 403);
        }
        // Validation des données d'inscription
        $validatedData = $request->validate([
            'eleve.nom' => 'required|string|max:255',
            'eleve.prenom' => 'required|string|max:255',
            'eleve.email' => 'required|email|unique:users,email',
            'eleve.date_naissance' => 'nullable|date',
            'eleve.classe_id' => 'nullable|exists:classes,id',
            'eleve.document_justificatif' => 'nullable|string',
            'tuteur.nom' => 'required|string|max:255',
            'tuteur.prenom' => 'required|string|max:255',
            'tuteur.email' => 'required|email|unique:users,email',
            'tuteur.telephone' => 'nullable|string|max:20',
            'tuteur.profession' => 'nullable|string|max:255',
        ]);

        $eleveData = $validatedData['eleve'];
        $tuteurData = $validatedData['tuteur'];

        $result = $this->authService->createEleveAvecTuteur($eleveData, $tuteurData);

        $eleve = $result['eleve'];
        $eleveUser = $result['eleveUser'];
        $tuteurUser = $result['tuteurUser'];

        // Envoi des emails
        //$tuteurUser->notify(new TuteurWelcomeNotification('Passer123!'));
        //$eleveUser->notify(new EleveWelcomeNotification('Passer123!'));

        return response()->json([
            'status' => 'success',
            'message' => 'Inscription réussie',
            'eleve' => $eleve,
            'tuteur' => $eleve->tuteur ?? null,
        ], 201);
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
            'message' => 'Connexion réussie',
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
