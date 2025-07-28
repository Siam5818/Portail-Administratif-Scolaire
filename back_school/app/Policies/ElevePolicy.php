<?php

namespace App\Policies;

use App\Models\Eleve;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ElevePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'enseignant';
    }

    /**
     * L'eleve voit ses propres infos, enseignant/admin voient tout
     */
    public function view(User $user, Eleve $eleve): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'enseignant' && $user->enseignant && $user->enseignant->classe_id === $eleve->classe_id) {
            return true;
        }

        return $user->eleve && $user->eleve->id === $eleve->id;
    }


    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Eleve $eleve): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Eleve $eleve): bool
    {
        return $user->role === 'admin';
    }
}
