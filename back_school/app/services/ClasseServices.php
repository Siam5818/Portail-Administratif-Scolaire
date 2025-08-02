<?php

namespace App\Services;

use App\Models\Classe;

class ClasseServices
{
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

    public function search(string $query)
    {
        if (!$query || trim($query) === '') {
            return [];
        }

        $motCle = strtolower($query);

        return Classe::where(function ($q) use ($motCle) {
            $q->whereRaw('LOWER(libelle) LIKE ?', ["%$motCle%"])
                ->orWhereRaw('LOWER(niveau) LIKE ?', ["%$motCle%"]);
        })->get();
    }

    public function countClasses(): int
    {
        return Classe::count();
    }
}
