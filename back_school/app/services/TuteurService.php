<?php

namespace App\Services;

use App\Models\Tuteur;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TuteurService
{
    public function index()
    {
        return Tuteur::with('user')->get();
    }

    public function store(array $request)
    {
        DB::beginTransaction();

        try {
            $defaultPassword = 'Passer123!';
            $isNewUser = false;
            $isNewTuteur = false;

            // 1. Vérifie si le User existe déjà
            $user = User::where('email', $request['email'])->first();

            // 2. Si le User n'existe pas, on le crée
            if (!$user) {
                $user = User::create([
                    'nom' => $request['nom'],
                    'prenom' => $request['prenom'],
                    'email' => $request['email'],
                    'password' => Hash::make($defaultPassword),
                    'must_change_password' => true,
                    'role' => 'tuteur',
                ]);
                $isNewUser = true;
            }

            // 3. Cherche si un Tuteur existe déjà avec le même user_id + infos
            $tuteur = Tuteur::where('user_id', $user->id)
                ->where('telephone', $request['telephone'] ?? null)
                ->where('profession', $request['profession'] ?? null)
                ->first();

            // 4. Si pas de Tuteur correspondant, on le crée
            if (!$tuteur) {
                $tuteur = Tuteur::create([
                    'user_id' => $user->id,
                    'telephone' => $request['telephone'] ?? null,
                    'profession' => $request['profession'] ?? null,
                ]);
                $isNewTuteur = true;
            }

            DB::commit();

            return [
                'tuteur' => $tuteur->load('user'),
                'defaultPassword' => $isNewUser ? $defaultPassword : null,
                'isNewUser' => $isNewUser,
                'isNewTuteur' => $isNewTuteur,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function show($id)
    {
        return Tuteur::with('user', 'eleve')->findOrFail($id);
    }

    public function update(array $request, $id)
    {
        $Tuteur = Tuteur::findOrFail($id);
        $Tuteur->update($request);
        return $Tuteur;
    }

    public function search(string $query)
    {
        if (!$query || trim($query) === '') {
            return [];
        }

        $motCle = strtolower($query);

        return Tuteur::where(function ($q) use ($motCle) {
            $q->whereRaw('LOWER(nom) LIKE ?', ["%$motCle%"])
                ->orWhereRaw('LOWER(prenom) LIKE ?', ["%$motCle%"])
                ->orWhereRaw('LOWER(email) LIKE ?', ["%$motCle%"]);
        })->get();
    }
}
