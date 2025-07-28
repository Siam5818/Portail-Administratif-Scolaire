<?php

namespace App\Services;

use App\Models\Note;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NoteService
{
    public function index()
    {
        return Note::with('eleve.user', 'matiere')->get();
    }

    public function store(array $request)
    {
        $note = Note::create($request);
        return $note->load('eleve.user', 'matiere');
    }

    public function show($id)
    {
        return Note::with('eleve.user', 'matiere')->findOrFail($id);
    }

    public function update(array $request, $id)
    {
        try {
            $note = Note::findOrFail($id);
            $note->update($request);
            return $note->load(['eleve.user', 'matiere']);
        } catch (ModelNotFoundException $e) {
            throw new \Exception("La note avec l'id $id est introuvable.");
        }
    }

    public function destroy($id)
    {
        Note::destroy($id);
        return true;
    }

    public function search(array $filters)
    {
        $query = Note::query();

        if (isset($filters['eleve_id'])) {
            $query->where('eleve_id', $filters['eleve_id']);
        }

        if (isset($filters['matiere_id'])) {
            $query->where('matiere_id', $filters['matiere_id']);
        }

        if (isset($filters['periode'])) {
            $query->where('periode', $filters['periode']);
        }

        return $query->with(['eleve.user', 'matiere'])->get();
    }
}
