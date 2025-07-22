<?php

namespace App\Policies;

use App\Models\Matiere;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MatierePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'enseignant']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Matiere $matiere): bool
    {
        if ($user->role === 'admin') {
            return true;
        }
        return $user->enseignant && $user->enseignant->matieres->contains($matiere);
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
    public function update(User $user, Matiere $matiere): bool
    {
        if ($user->role === 'admin') {
            return true;
        }
        return $user->enseignant && $user->enseignant->matieres->contains($matiere);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Matiere $matiere): bool
    {
        return $user->role === 'admin';
    }
}
