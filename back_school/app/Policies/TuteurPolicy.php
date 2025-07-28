<?php

namespace App\Policies;

use App\Models\Tuteur;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TuteurPolicy
{
    /**
     * Seuls les admins peuvent voir la liste des tuteurs
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Un utilisateur peut voir son propre profil tuteur
     * ou un admin peut voir tous les tuteurs
     */
    public function view(User $user, Tuteur $tuteur): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->id === $tuteur->user_id) {
            return true;
        }
        return false;
    }

    /**
     * Seuls les admins peuvent créer un tuteur
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Un tuteur peut modifier ses propres infos, ou un admin peut modifier tous
     */
    public function update(User $user, Tuteur $tuteur): bool
    {
        return $user->role === 'admin' || $user->id === $tuteur->user_id;
    }

    /**
     * Seuls les admins peuvent supprimer un tuteur
     */
    public function delete(User $user, Tuteur $tuteur): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Seuls les admins peuvent restaurer un tuteur soft deleted
     */
    public function restore(User $user, Tuteur $tuteur): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Seuls les admins peuvent forcer la suppression définitive
     */
    public function forceDelete(User $user, Tuteur $tuteur): bool
    {
        return $user->role === 'admin';
    }
}
