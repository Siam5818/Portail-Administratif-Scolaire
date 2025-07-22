<?php

namespace App\services;

use App\Models\Matiere;

class MatiereService{
    public function index()
    {
        return Matiere::all();
    }

    public function store(array $request)
    {
        return Matiere::create($request);
    }

    public function show($id)
    {
        return Matiere::find($id);
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
}