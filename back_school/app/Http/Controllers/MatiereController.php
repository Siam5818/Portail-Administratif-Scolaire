<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMatiereRequest;
use Illuminate\Http\Request;
use App\Services\MatiereService;

class MatiereController extends Controller
{
    /**
     * The MatiereService instance.
     *
     * @var MatiereService
     */
    protected $matiereService;

    /**
     * Create a new controller instance.
     *
     * @param MatiereService $matiereService
     */
    public function __construct(MatiereService $matiereService)
    {
        $this->matiereService = $matiereService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return response()->json($this->matiereService->index(), 200);
        } catch (\Exception $e) {
            return $this->jsonError('Erreur de recuperation des matières', $e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMatiereRequest $request)
    {
        try {
            $matiere = $this->matiereService->store($request->validated());
            return response()->json($matiere, 201);
        } catch (\Exception $e) {
            return $this->jsonError('Erreur de creation de la matière', $e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return response()->json($this->matiereService->show($id), 200);
        } catch (\Exception $e) {
            return $this->jsonError('Erreur de recuperation de la matière', $e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreMatiereRequest $request, string $id)
    {
        try {
            $matiere = $this->matiereService->update($request->validated(), $id);
            return response()->json($matiere, 200);
        } catch (\Exception $e) {
            return $this->jsonError('Erreur de mise a jour de la matière', $e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->matiereService->destroy($id);
            return response()->json(['message' => 'Matiere supprimée avec succès'], 204);
        } catch (\Exception $e) {
            return $this->jsonError('la suppression de la matière', $e);
        }
    }
    /**
     * Search for a resource by name.
     */
    public function search(Request $request)
    {
        try {
            $query = $request->input('query');
            $result = $this->matiereService->search($query);
            return response()->json($result, 200);
        } catch (\Exception $e) {
            return $this->jsonError('Erreur de recherche de la matiere', $e);
        }
    }

    /**
     * Handle JSON error responses.
     *
     * @param string $action
     * @param \Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    private function jsonError(string $action, \Exception $e)
    {
        return response()->json([
            'error' => "Erreur lors de $action : " . $e->getMessage()
        ], 500);
    }
}
