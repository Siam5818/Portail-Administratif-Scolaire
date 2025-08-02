<?php

namespace App\Services;

use App\Models\Matiere;

class MatiereService
{
    public function index()
    {
        return Matiere::with(['enseignant', 'classe'])->get();
    }

    public function store(array $request)
    {
        return Matiere::create($request);
    }

    public function show($id)
    {
        return Matiere::with(['enseignant', 'classe'])->findOrFail($id);
    }

    public function update(array $request, $id)
    {
        $Matiere = Matiere::findOrFail($id);
        $Matiere->update($request);
        return $Matiere;
    }

    public function destroy($id)
    {
        Matiere::destroy($id);
        return true;
    }

    public function search(string $query)
    {
        if (!$query || trim($query) === '') {
            return [];
        }

        $motCle = strtolower($query);

        return Matiere::whereRaw('LOWER(nom) LIKE ?', ["%$motCle%"])->get();
    }

    public function countMatiere(): int
    {
        return Matiere::count();
    }
}
