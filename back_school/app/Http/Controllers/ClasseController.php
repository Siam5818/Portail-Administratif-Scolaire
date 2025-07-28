<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Services\ClasseServices;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    protected $classeService;

    public function __construct()
    {
        $this->classeService = new ClasseServices();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $classes = $this->classeService->index();
            return response()->json($classes, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve classes', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:255',
            'niveau' => 'required|string|max:100',
        ]);

        try {
            $classe = $this->classeService->store($validated);
            return response()->json(['message' => 'Classe créée avec succès', 'classe' => $classe], 201);
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
            $classe = $this->classeService->show($id);
            if (!$classe) {
                return response()->json(['message' => 'Classe non trouvée'], 404);
            }
            return response()->json($classe, 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Classe introuvable', 'error' => $e->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:255',
            'niveau' => 'required|string|max:100',
        ]);

        try {
            $classe = Classe::find($id);
            if (!$classe) {
                return response()->json(['message' => 'Classe non trouvée'], 404);
            }
            $classe = $this->classeService->update($validated, $id);
            return response()->json(['message' => 'Classe mise à jour avec succès', 'classe' => $classe], 200);
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
            $classe = Classe::find($id);
            if (!$classe) {
                return response()->json(['message' => 'Classe non trouvée'], 404);
            }
            $this->classeService->destroy($id);
            return response()->json(['message' => 'Classe supprimée avec succès'], 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Erreur lors de la suppression.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Search for classes based on a query.
     */
    public function search(Request $request)
    {
        $query = $request->input('query', '');
        try {
            $classes = $this->classeService->search($query);
            return response()->json($classes, 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Erreur lors de la recherche.', 'error' => $e->getMessage()], 500);
        }
    }
}
