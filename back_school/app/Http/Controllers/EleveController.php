<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEleveRequest;
use App\Services\EleveServices;
use Illuminate\Http\Request;
use App\Notifications\EleveWelcomeNotification;
use App\Notifications\TuteurWelcomeNotification;

class EleveController extends Controller
{
    protected EleveServices $eleveServices;
    public function __construct(EleveServices $eleveServices)
    {
        $this->eleveServices = $eleveServices;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $eleves = $this->eleveServices->index();
            return response()->json($eleves, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve eleves', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEleveRequest $request)
    {
        $validated = $request->validated();
        logger($validated);
        try {
            $result = $this->eleveServices->store($validated);
            $eleve = $result['eleve'];
            $defaultPasswordEleve = $result['elevePassword'];

            // Optionally, you can send a welcome notification here
            $eleve->user->notify(new EleveWelcomeNotification($defaultPasswordEleve));

            if ($result['tuteurPassword']) {
                $eleve->tuteur->user->notify(new TuteurWelcomeNotification($result['tuteurPassword']));
                logger("Nouveau tuteur créé — envoi de l’e-mail");
            } else {
                logger("Tuteur déjà existant — pas de notification envoyée");
            }
            logger($result);

            return response()->json(['message' => 'Eleve créé avec succès', 'eleve' => $result], 201);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Erreur lors de la création.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $eleve = $this->eleveServices->show($id);
            if (!$eleve) {
                return response()->json(['message' => 'Eleve non trouvé'], 404);
            }
            return response()->json($eleve, 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Erreur lors de la récupération.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $eleveUpdate = $this->eleveServices->update($request->all(), $id);
            return response()->json(['message' => 'Eleve mis à jour avec succès', 'eleve' => $eleveUpdate], 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Erreur lors de la mise à jour.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->eleveServices->destroy($id);
            return response()->json(['message' => 'Eleve supprimé avec succès'], 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Erreur lors de la suppression.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Search for eleves by name.
     */
    public function search(Request $request)
    {
        $query = $request->input('query', '');
        try {
            $eleves = $this->eleveServices->search($query);
            return response()->json($eleves, 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Erreur lors de la recherche.', 'error' => $e->getMessage()], 500);
        }
    }
}
