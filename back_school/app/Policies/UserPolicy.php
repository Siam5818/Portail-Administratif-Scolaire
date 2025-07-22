<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Seul l'admin peut voir tous les utilisateurs.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Seul un utilisateur peut voir son propre profil ou un admin peut voir tous.
     */
    public function view(User $user, User $model): bool
    {
        return $user->id === $model->id || $user->role == 'admin';
    }

    /**
     * Seul l'admin peut creer des utilisateur
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * L'utilisateur peut mettre a jour son profil ou l'admin peut tous faire.
     */
    public function update(User $user, User $model): bool
    {
        return $user->id === $model->id || $user->role === 'admin';
    }

    /**
     * Seul l'admin peut supprimer un utilisateur
     */
    public function delete(User $user, User $model): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Seul un admin peut restaurer un utilisateur supprime.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Seul un admin peut supprime definitivement un utilisateur.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->role === 'admin';
    }
}
