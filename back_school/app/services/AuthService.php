<?php

namespace App\Services;

use App\Models\User;
use App\Models\Eleve;
use App\Models\Tuteur;
use App\Models\Enseignant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login(string $email, string $password): ?User
    {
        $user = User::where('email', $email)->first();
        if ($user && Hash::check($password, $user->password)) {
            return $user;
        }
        return null;
    }

    public function changePassword(User $user, string $newPassword): bool
    {
        $user->password = Hash::make($newPassword);
        $user->must_change_password = false;
        return $user->save();
    }

    public function getUtilisateursRecents(): Collection
    {
        $eleves = Eleve::with('user')->latest()->take(3)->get()->map(function ($e) {
            return [
                'type' => 'eleve',
                'nom' => $e->user->nom ?? '',
                'prenom' => $e->user->prenom ?? '',
                'role' => 'Élève',
                'created_at' => $e->created_at,
            ];
        });

        $enseignants = Enseignant::with('user')->latest()->take(3)->get()->map(function ($e) {
            return [
                'type' => 'enseignant',
                'nom' => $e->user->nom ?? '',
                'prenom' => $e->user->prenom ?? '',
                'role' => 'Enseignant',
                'created_at' => $e->created_at,
            ];
        });

        $tuteurs = Tuteur::with('user')->latest()->take(3)->get()->map(function ($t) {
            return [
                'type' => 'tuteur',
                'nom' => $t->user->nom ?? '',
                'prenom' => $t->user->prenom ?? '',
                'role' => 'Tuteur',
                'created_at' => $t->created_at,
            ];
        });

        return collect([...$eleves, ...$enseignants, ...$tuteurs])
            ->sortByDesc('created_at')
            ->values();
    }
}
