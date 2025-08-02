<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BulletinService;
use App\Models\Bulletin;
use App\Models\Eleve;

class BulletinController extends Controller
{
    protected BulletinService $bulletinService;

    public function __construct(BulletinService $bulletinService)
    {
        $this->bulletinService = $bulletinService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $bulletins = $this->bulletinService->bulletinsDisponibles();
            return response()->json($bulletins, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des bulletins.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'eleve_id' => 'required|exists:eleves,id',
            'periode' => 'required|string',
            'annee' => 'required|integer',
        ]);

        // Vérifie si le bulletin existe déjà
        $existing = Bulletin::where('eleve_id', $request->eleve_id)
            ->where('periode', $request->periode)
            ->where('annee', $request->annee)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Bulletin déjà existant pour cet élève, cette période et cette année.',
                'bulletin_id' => $existing->id,
                'pdf' => $existing->pdf_url,
            ], 409);
        }

        // Création via le service
        $eleve = Eleve::findOrFail($request->eleve_id);
        $bulletin = $this->bulletinService->generateBulletin($eleve, $request->periode, $request->annee);

        return response()->json([
            'message' => 'Bulletin généré avec succès.',
            'bulletin_id' => $bulletin->id,
            'pdf' => $bulletin->pdf_url,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bulletin = Bulletin::findOrFail($id);
        try {
            $json = $this->bulletinService->getBulletinJson($bulletin);
            return response()->json($json);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération du bulletin.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'periode' => 'nullable|string',
            'annee' => 'nullable|integer',
        ]);
        $bulletin = Bulletin::findOrFail($id);

        $bulletin->periode = $request->periode ?? $bulletin->periode;
        $bulletin->annee = $request->annee ?? $bulletin->annee;
        $bulletin->save();

        $this->bulletinService->supprimerFichierPdf($bulletin);

        $updatedBulletin = $this->bulletinService->generateBulletin(
            $bulletin->eleve,
            $bulletin->periode,
            $bulletin->annee
        );

        return response()->json([
            'message' => 'Bulletin mis à jour et PDF regénéré. Notifications envoyées.',
            'bulletin_id' => $updatedBulletin->id,
            'pdf' => $updatedBulletin->pdf_url,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bulletin = Bulletin::findOrFail($id);

        // Supprime le fichier PDF associé
        if ($this->bulletinService->supprimerFichierPdf($bulletin)) {
            $bulletin->delete();
            return response()->json(['message' => 'Bulletin supprimé avec succès.'], 200);
        }

        return response()->json(['error' => 'Erreur lors de la suppression du bulletin.'], 500);
    }

    /**
     * Search for bulletins based on criteria.
     */
    public function search(Request $request)
    {
        $criteria = $request->only(['eleve_id', 'periode', 'annee']);

        try {
            $results = $this->bulletinService->searchBulletins($criteria);
            return response()->json($results, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la recherche.'], 500);
        }
    }
}
