<?php

namespace App\services;

use App\Models\Eleve;

class EleveServices
{
    public function index()
    {
        return Eleve::all();
    }

    public function store(array $request)
    {
        return Eleve::create($request);
    }

    public function show($id)
    {
        return Eleve::find($id);
    }

    public function update(array $request, $id)
    {
        $Eleve = Eleve::findOrFail($id);
        $Eleve->update($request);
        return $Eleve;
    }

    public function destroy($id)
    {
        Eleve::destroy($id);
        return true;
    }
}
