<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Tuteur;
use App\Services\TuteurService;
use App\Http\Requests\UpdateTuteurRequest;

class TuteurController extends Controller
{
    protected TuteurService $tuteurService;

    public function __construct(TuteurService $tuteurService)
    {
        $this->tuteurService = $tuteurService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tuteurs = $this->tuteurService->index();
        return response()->json($tuteurs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'email' => 'required|email',
            'telephone' => 'nullable|string|max:20',
            'profession' => 'nullable|string|max:255',
        ]);


        $tuteur = $this->tuteurService->store($validatedData);
        return response()->json(['message' => 'Tuteur créé avec succès', 'data' => $tuteur], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tuteur $tuteur): JsonResponse
    {
        return response()->json($tuteur->load('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTuteurRequest $request, Tuteur $tuteur): JsonResponse
    {
        $updated = $this->tuteurService->update($request->validated(), $tuteur->id);
        return response()->json([
            'message' => 'Tuteur mis à jour avec succès',
            'data' => $updated
        ]);
    }

    /**
     * Search the specified resource from storage.
     */
    public function search(Request $request): JsonResponse
    {
        $results = $this->tuteurService->search($request->query('q'));
        return response()->json($results);
    }
}
