<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\User;

class NotePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'enseignant', 'eleve']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Note $note): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'enseignant') {
            // L'enseignant voit les notes des ses eleves dans ses matieres
            return $user->enseignant && $user->enseignant->matieres->contains($note->matiere_id);
        }

        if ($user->role === 'eleve') {
            return $user->eleve && $user->eleve->id === $note->eleve_id;
        }

        return false;
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
    public function update(User $user, Note $note): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'enseignant') {
            // L'enseignant modifie ses notes
            return $user->enseignant && $user->enseignant->matieres->contains($note->matiere_id);
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Note $note): bool
    {
        return $user->role === 'admin';
    }
}
