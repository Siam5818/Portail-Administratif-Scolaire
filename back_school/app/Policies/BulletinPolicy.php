<?php

namespace App\Policies;

use App\Models\Bulletin;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BulletinPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'enseignant';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Bulletin $bulletin): bool
    {
        // Admin peut tout voir
        if ($user->role === 'admin') {
            return true;
        }

        // Élève concerné par le bulletin
        if ($user->role === 'eleve' && $user->eleve && $user->eleve->id === $bulletin->eleve_id) {
            return true;
        }

        // Tuteur de l’élève
        if ($user->role === 'tuteur' && $user->tuteur) {
            return $user->tuteur->enfants->contains('id', $bulletin->eleve_id);
        }

        // Enseignant peut voir tous
        return $user->role === 'enseignant';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'enseignant' || $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Bulletin $bulletin): bool
    {
        return $user->role === 'admin' || $user->role === 'enseignant';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Bulletin $bulletin): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Bulletin $bulletin): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Bulletin $bulletin): bool
    {
        return $user->role === 'admin';
    }
}
