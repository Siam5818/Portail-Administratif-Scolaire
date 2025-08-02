<?php

namespace App\Http\Controllers;

use App\Models\Enseignant;
use Illuminate\Http\Request;
use App\Services\EnseignantService;
use App\Http\Requests\StoreEnseignantRequest;
use App\Http\Requests\UpdateEnseignantRequest;
use App\Notifications\EnseignantWelcomeNotification;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EnseignantController extends Controller
{
    /**
     * The EnseignantService instance.
     *
     * @var EnseignantService
     */
    protected $proService;

    public function __construct()
    {
        $this->proService = new EnseignantService();
        $this->middleware('auth:sanctum');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $enseignants = $this->proService->index();
            return response()->json($enseignants, 200);
        } catch (\Exception $e) {
            return $this->jsonError('Erreur de recuperation des enseignants', $e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEnseignantRequest $request)
    {
        $this->authorize('create', Enseignant::class);
        $validated = $request->validated();

        try {
            $result = $this->proService->store($validated);
            $enseignant = $result['enseignant'];
            $defaultPassword = $result['defaultPassword'];
            // Optionally, you can send a welcome notification here
            $enseignant->user->notify(new EnseignantWelcomeNotification($defaultPassword));
            return response()->json(['message' => 'Enseignant créé avec succès', 'enseignant' => $enseignant], 201);
        } catch (\Throwable $e) {
            return $this->jsonError('Erreur lors de la création de l\'enseignant', $e);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $enseignant = Enseignant::findOrFail($id);

        $this->authorize('view', $enseignant);
        try {
            $enseignant = $this->proService->show($id);
            return response()->json($enseignant, 200);
        } catch (\Throwable $e) {
            return $this->jsonError('Erreur lors de la récupération de l\'enseignant', $e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEnseignantRequest $request, string $id)
    {
        try {
            $enseignant = Enseignant::findOrFail($id);
            $this->authorize('update', $enseignant);

            $EnseiUpdate = $this->proService->update($request->validated(), $id);
            return response()->json(['message' => 'Enseignant mis à jour avec succès', 'enseignant' => $EnseiUpdate], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Enseignant non trouvé'], 404);
        } catch (\Throwable $e) {
            return $this->jsonError('Erreur lors de la mise à jour de l\'enseignant', $e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $enseignant = Enseignant::findOrFail($id);

        $this->authorize('delete', $enseignant);
        try {
            $this->proService->destroy($id);

            return response()->json(['message' => 'Enseignant supprimé avec succès'], 200);
        } catch (\Throwable $e) {
            return $this->jsonError('Erreur lors de la suppression de l\'enseignant', $e);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Enseignant non trouvé'], 404);
        }
    }

    /**
     * Search for enseignants by name.
     */
    public function search(Request $request)
    {
        $query = $request->input('query', '');
        try {
            $enseignants = $this->proService->search($query);
            return response()->json($enseignants, 200);
        } catch (\Throwable $e) {
            return $this->jsonError('Erreur lors de la recherche de l\'enseignant', $e);
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

    public function count()
    {
        try {
            $total = $this->proService->countEnseignants();
            return response()->json(['total' => $total], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Erreur lors du comptage',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
