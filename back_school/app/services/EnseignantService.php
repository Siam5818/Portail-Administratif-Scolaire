<?php

namespace App\services;

use App\Models\Enseignant;

class EnseignantService{
    public function index()
    {
        return Enseignant::all();
    }

    public function store(array $request)
    {
        return Enseignant::create($request);
    }

    public function show($id)
    {
        return Enseignant::find($id);
    }

    public function update(array $request, $id)
    {
        $Enseignant = Enseignant::findOrFail($id);
        $Enseignant->update($request);
        return $Enseignant;
    }

    public function destroy($id)
    {
        Enseignant::destroy($id);
        return true;
    }
}