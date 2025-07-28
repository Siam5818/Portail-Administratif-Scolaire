<?php

namespace App\Services;

use App\Models\Eleve;
use App\Models\User;
use App\Models\Tuteur;
use Illuminate\Support\Facades\DB;
use App\Services\TuteurService;
use Illuminate\Support\Facades\Hash;

class EleveServices
{
    protected TuteurService $tuteurService;

    public function __construct(TuteurService $tuteurService)
    {
        $this->tuteurService = $tuteurService;
    }


    public function index()
    {
        return Eleve::with(['user', 'classe', 'tuteur'])->get();
    }

    public function store(array $request)
    {
        try {

            return DB::transaction(function () use ($request) {
                $defaultPasswordEleve = 'Passer123!';

                $tuteur = $this->tuteurService->store([
                    'nom' => $request['tuteur']['nom'],
                    'prenom' => $request['tuteur']['prenom'],
                    'email' => $request['tuteur']['email'],
                    'profession' => $request['tuteur']['profession'] ?? null,
                    'telephone' => $request['tuteur']['telephone'] ?? null,
                ]);

                $userEleve = User::create([
                    'nom' => $request['nom'],
                    'prenom' => $request['prenom'],
                    'email' => $request['email'],
                    'password' => Hash::make('Passer123!'),
                    'role' => 'eleve',
                ]);

                $eleve = Eleve::create([
                    'user_id' => $userEleve->id,
                    'date_naissance' => $request['date_naissance'] ?? null,
                    'classe_id' => $request['classe_id'] ?? null,
                    'document_justificatif' => $request['document_justificatif'] ?? null,
                    'tuteur_id' => $tuteur['tuteur']->id,
                ]);

                return [
                    'eleve' => $eleve->load('user', 'classe', 'tuteur.user'),
                    'tuteurPassword' => $tuteur['defaultPassword'],
                    'elevePassword' => $defaultPasswordEleve,
                ];
            });
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Erreur création élève : ' . $e->getMessage());
            throw $e;
        }
    }

    public function show($id)
    {
        return Eleve::with(['user', 'classe', 'tuteur'])->findOrFail($id);
    }

    public function update(array $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $eleve = Eleve::with(['user', 'tuteur'])->findOrFail($id);

                $eleve->update([
                    'date_naissance' => $request['date_naissance'] ?? $eleve->date_naissance,
                    'classe_id' => $request['classe_id'] ?? $eleve->classe_id,
                    'document_justificatif' => $request['document_justificatif'] ?? $eleve->document_justificatif,
                ]);

                if (isset($request['nom']) || isset($request['prenom']) || isset($request['email'])) {
                    $eleve->user->update([
                        'nom' => $request['nom'] ?? $eleve->user->nom,
                        'prenom' => $request['prenom'] ?? $eleve->user->prenom,
                        'email' => $request['email'] ?? $eleve->user->email,
                    ]);
                }

                if (isset($request['tuteur'])) {
                    // Chercher si le tuteur existe déjà
                    $userTuteur = User::where('email', $request['tuteur']['email'])->first();

                    if (!$userTuteur) {
                        // Créer un nouveau tuteur
                        $userTuteur = User::create([
                            'nom' => $request['tuteur']['nom'],
                            'prenom' => $request['tuteur']['prenom'],
                            'email' => $request['tuteur']['email'],
                            'password' => Hash::make('Passer123!'),
                            'role' => 'tuteur',
                        ]);

                        $nouveauTuteur = Tuteur::create([
                            'user_id' => $userTuteur->id,
                            'profession' => $request['tuteur']['profession'] ?? null,
                            'telephone' => $request['tuteur']['telephone'] ?? null,
                        ]);

                        // Mettre à jour l'élève avec le nouveau tuteur
                        $eleve->update(['tuteur_id' => $nouveauTuteur->id]);
                    } else {
                        // Mettre à jour le tuteur existant
                        $userTuteur->update([
                            'nom' => $request['tuteur']['nom'] ?? $userTuteur->nom,
                            'prenom' => $request['tuteur']['prenom'] ?? $userTuteur->prenom,
                        ]);

                        $userTuteur->tuteur->update([
                            'profession' => $request['tuteur']['profession'] ?? $userTuteur->tuteur->profession,
                            'telephone' => $request['tuteur']['telephone'] ?? $userTuteur->tuteur->telephone,
                        ]);

                        // Mettre à jour l'élève avec le tuteur existant
                        $eleve->update(['tuteur_id' => $userTuteur->tuteur->id]);
                    }
                }

                return $eleve->fresh(['user', 'classe', 'tuteur']);
            });
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Erreur mise à jour élève : ' . $e->getMessage());
            throw $e;
        }
    }

    public function destroy($id)
    {
        Eleve::destroy($id);
        return true;
    }

    public function search(string $query)
    {
        if (!$query || trim($query) === '') {
            return [];
        }

        $motCle = strtolower($query);

        return Eleve::where(function ($q) use ($motCle) {
            $q->whereRaw('LOWER(nom) LIKE ?', ["%$motCle%"])
                ->orWhereRaw('LOWER(prenom) LIKE ?', ["%$motCle%"])
                ->orWhereRaw('LOWER(email) LIKE ?', ["%$motCle%"]);
        })->get();
    }
}
