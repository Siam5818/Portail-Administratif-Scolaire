<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use Illuminate\Http\Request;
use App\Services\MatiereService;
use App\Http\Requests\StoreMatiereRequest;

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
        $this->middleware('auth:sanctum');
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
        $this->authorize('create', Matiere::class);
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
        $matiere = Matiere::findOrFail($id);

        $this->authorize('view', $matiere);
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
        $matiere = Matiere::findOrFail($id);

        $this->authorize('update', $matiere);
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
        $matiere = Matiere::findOrFail($id);

        $this->authorize('delete', $matiere);
        try {
            $this->matiereService->destroy($id);
            return response()->json(['message' => 'Matiere supprimée avec succès'], 200);
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

    public function count()
    {
        try {
            $total = $this->matiereService->countMatiere();
            return response()->json(['total' => $total], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Erreur lors du comptage',
                'error' => $e->getMessage()
            ], 500);
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
