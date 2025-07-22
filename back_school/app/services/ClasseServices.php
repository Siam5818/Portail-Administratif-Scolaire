<?php

namespace App\services;

use App\Models\Classe;

class ClasseServices{
    public function index()
    {
        return Classe::all();
    }

    public function store(array $request)
    {
        return Classe::create($request);
    }

    public function show($id)
    {
        return Classe::find($id);
    }

    public function update(array $request, $id)
    {
        $Classe = Classe::findOrFail($id);
        $Classe->update($request);
        return $Classe;
    }

    public function destroy($id)
    {
        Classe::destroy($id);
        return true;
    }
}