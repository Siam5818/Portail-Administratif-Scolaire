<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\NoteService;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    protected $noteService;
    public function __construct(NoteService $noteService)
    {
        $this->noteService = $noteService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return response()->json($this->noteService->index(), 200);
        } catch (\Exception $e) {
            return $this->jsonError('Erreur de recuperation des notes', $e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoteRequest $request)
    {
        try {
            $note = $this->noteService->store($request->validated());
            logger()->info('Note created successfully', ['note' => $note]);
            return response()->json($note, 201);
        } catch (\Exception $e) {
            return $this->jsonError('Erreur de creation de la note', $e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return response()->json($this->noteService->show($id), 200);
        } catch (\Exception $e) {
            return $this->jsonError('Erreur de recuperation de la note', $e);
        }
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'note' => 'required|numeric|min:0|max:20',
                'eleve_id' => 'required|exists:eleves,id',
                'matiere_id' => 'required|exists:matieres,id',
                'periode' => 'required|string|max:255',
            ]);

            $note = $this->noteService->update($validated, $id);
            return response()->json($note, 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            logger()->error('Erreur update note', [
                'id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Erreur serveur',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->noteService->destroy($id);
            return response()->json(['message' => 'Note supprimée avec succès'], 204);
        } catch (\Exception $e) {
            return $this->jsonError('la suppression de la note', $e);
        }
    }
    /**
     * Search notes based on filters.
     */
    public function search(Request $request)
    {
        try {
            $filters = $request->only(['eleve_id', 'matiere_id', 'periode']);
            $notes = $this->noteService->search($filters);
            return response()->json($notes, 200);
        } catch (\Exception $e) {
            return $this->jsonError('Erreur de recherche des notes', $e);
        }
    }

    private function jsonError(string $action, \Exception $e)
    {
        return response()->json([
            'error' => "Erreur lors de $action : " . $e->getMessage()
        ], 500);
    }
}
