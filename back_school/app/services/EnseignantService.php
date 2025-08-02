<?php

namespace App\Services;

use App\Models\Enseignant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EnseignantService
{
    public function index()
    {
        return Enseignant::with('user')->get();
    }

    public function store(array $data): array
    {
        DB::beginTransaction();

        try {
            $defaultPassword = 'Passer123!';
            // Change password : passer25
            $user = User::create([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'password' => Hash::make($defaultPassword),
                'role' => 'enseignant',
            ]);

            $enseignant = Enseignant::create([
                'user_id' => $user->id,
                'specialite' => $data['specialite'] ?? null,
                'classe_id' => $data['classe_id'] ?? null,
            ]);

            DB::commit();
            return [
                'enseignant' => $enseignant->load('user'),
                'defaultPassword' => $defaultPassword
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function show($id)
    {
        return Enseignant::with('user')->find($id);
    }

    public function update(array $request, $id)
    {
        DB::beginTransaction();

        $enseignant = Enseignant::with('user')->findOrFail($id);

        $enseignant->user->update([
            'nom' => $request['nom'],
            'prenom' => $request['prenom'],
            'email' => $request['email'],
        ]);

        $enseignant->update([
            'specialite' => $request['specialite'] ?? $enseignant->specialite,
            'classe_id' => $request['classe_id'] ?? $enseignant->classe_id,
        ]);

        DB::commit();
        return $enseignant->load('user');
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $enseignant = Enseignant::with('user')->findOrFail($id);

            // Supprimer l'enseignant
            $enseignant->delete();

            // Supprimer l'utilisateur lié
            if ($enseignant->user) {
                $enseignant->user->delete();
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function search(string $query)
    {
        if (!$query || trim($query) === '') {
            return [];
        }

        $motCle = strtolower($query);

        return Enseignant::whereHas('user', function ($query) use ($motCle) {
            $query->whereRaw('LOWER(nom) LIKE ?', ["%$motCle%"])
                ->orWhereRaw('LOWER(prenom) LIKE ?', ["%$motCle%"])
                ->orWhereRaw('LOWER(email) LIKE ?', ["%$motCle%"]);
        })->with('user')->get();
    }

    public function countEnseignants(): int
    {
        return Enseignant::count();
    }
}
