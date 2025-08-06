<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use Illuminate\Http\Request;
use App\Services\EleveServices;
use App\Services\NotificationService;
use App\Http\Requests\StoreEleveRequest;

class EleveController extends Controller
{
    protected EleveServices $eleveServices;
    protected NotificationService $notificationService;

    public function __construct(EleveServices $eleveServices, NotificationService $notificationService)
    {
        $this->eleveServices = $eleveServices;
        $this->notificationService = $notificationService;
        $this->middleware('auth:sanctum');
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
        $this->authorize('create', Eleve::class);
        $validated = $request->validated();
        logger($validated);
        try {
            $result = $this->eleveServices->store($validated);
            $eleve = $result['eleve'];
            $defaultPasswordEleve = $result['elevePassword'];

            // Envoi des notifications de bienvenue
            $this->notificationService->envoyerNotificationsCreationEleve(
                $eleve,
                $defaultPasswordEleve,
                $result['tuteurPassword'] ?? null
            );

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
        $eleve = Eleve::findOrFail($id);
        $this->authorize('view', $eleve);
        try {
            $eleve = $this->eleveServices->show($id);
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
            $eleve = Eleve::findOrFail($id);
            $this->authorize('update', $eleve);
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

    public function count()
    {
        try {
            $total = $this->eleveServices->countEleves();
            return response()->json(['total' => $total], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Erreur lors du comptage',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getMatieres($id)
    {
        try {
            $matieres = $this->eleveServices->getMatieresByEleveId($id);
            return response()->json($matieres);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Erreur lors de la recuperation.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAnnotationStatus()
    {
        try {
            $statusList = $this->eleveServices->getAnnotationStatus();
            return response()->json($statusList);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Erreur lors du calcul du statut.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function searchAnnotationStatus(Request $request)
    {
        try {
            $statusList = $this->eleveServices->searchAnnotationStatus($request);
            return response()->json($statusList);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Erreur lors de la recherche des annotations.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
