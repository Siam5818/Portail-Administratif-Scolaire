<?php

namespace App\Services;

use App\Models\Bulletin;
use App\Models\Note;
use App\Models\Classe;
use Illuminate\Support\Collection;

class ClasseServices
{
    public function index()
    {
        return Classe::with(['eleves', 'matieres'])->get()->map(function ($classe) {
            return [
                'id' => $classe->id,
                'libelle' => $classe->libelle,
                'niveau' => $classe->niveau,
                'effectif' => $classe->eleves->count(),
                'nombre_matieres' => $classe->matieres->count(),
                'created_at' => $classe->created_at->toDateTimeString()
            ];
        });
    }

    public function store(array $request)
    {
        return Classe::create($request);
    }

    public function show($id)
    {
        $classe = Classe::with(['eleves', 'matieres'])->findOrFail($id);

        return [
            'id' => $classe->id,
            'libelle' => $classe->libelle,
            'niveau' => $classe->niveau,
            'effectif' => $classe->eleves->count(),
            'nombre_matieres' => $classe->matieres->count(),
            'created_at' => $classe->created_at->toDateTimeString()
        ];
    }


    public function update(array $request, $id)
    {
        $Classe = Classe::findOrFail($id);
        $Classe->update($request);
        return $Classe;
    }

    public function destroy($id)
    {
        Classe::destroy($id);
        return true;
    }

    public function search(string $query)
    {
        if (!$query || trim($query) === '') {
            return [];
        }

        $motCle = strtolower($query);

        return Classe::where(function ($q) use ($motCle) {
            $q->whereRaw('LOWER(libelle) LIKE ?', ["%$motCle%"])
                ->orWhereRaw('LOWER(niveau) LIKE ?', ["%$motCle%"]);
        })->get();
    }

    public function countClasses(): int
    {
        return Classe::count();
    }

    public function getRecentActivities(): Collection
    {
        $bulletins = Bulletin::latest()->take(3)->get()->map(function ($b) {
            $eleve = $b->eleve?->user;
            return [
                'type' => 'bulletin',
                'message' => "Bulletin ajouté pour " . ($eleve ? "{$eleve->prenom} {$eleve->nom}" : 'Élève inconnu'),
                'timestamp' => $b->created_at,
                'icon' => 'fas fa-file-alt',
                'color' => 'blue',
                'source' => 'bulletin',
                'details' => [
                    'eleve_id' => $b->eleve_id,
                    'periode' => $b->periode,
                    'annee' => $b->annee
                ]
            ];
        });

        $notes = Note::latest()->take(3)->get()->map(function ($n) {
            $eleve = $n->eleve?->user;
            $matiere = $n->matiere?->nom ?? 'Matière inconnue';
            return [
                'type' => 'note',
                'message' => "Note saisie en {$matiere} pour " . ($eleve ? "{$eleve->prenom} {$eleve->nom}" : 'Élève inconnu'),
                'timestamp' => $n->created_at,
                'icon' => 'fas fa-pen',
                'color' => 'green',
                'source' => 'note',
                'details' => [
                    'eleve_id' => $n->eleve_id,
                    'matiere' => $matiere,
                    'note' => $n->note
                ]
            ];
        });

        $classes = Classe::latest()->take(2)->get()->map(function ($c) {
            return [
                'type' => 'classe',
                'message' => "Nouvelle classe créée : {$c->libelle}",
                'timestamp' => $c->created_at,
                'icon' => 'fas fa-chalkboard',
                'color' => 'purple',
                'source' => 'classe',
                'details' => [
                    'niveau' => $c->niveau,
                    'libelle' => $c->libelle
                ]
            ];
        });

        return collect([...$bulletins, ...$notes, ...$classes])
            ->sortByDesc('timestamp')
            ->values();
    }

    public function getMeilleureMoyenne(): array
    {
        // Récupérer toutes les périodes et années disponibles
        $groupes = Bulletin::select('periode', 'annee')
            ->distinct()
            ->orderByDesc('annee')
            ->orderByDesc('periode')
            ->get();

        foreach ($groupes as $groupe) {
            $bulletins = Bulletin::where('periode', $groupe->periode)
                ->where('annee', $groupe->annee)
                ->with('eleve.classe') // Charger la classe de l'élève
                ->get();

            // Filtrer ceux qui ont une moyenne calculable
            $bulletinsAvecMoyenne = $bulletins->filter(fn($b) => $b->calculMoyenne() > 0);

            if ($bulletinsAvecMoyenne->isNotEmpty()) {
                $meilleur = $bulletinsAvecMoyenne->sortByDesc(fn($b) => $b->calculMoyenne())->first();
                $moyenne = $meilleur->calculMoyenne();

                return [
                    'message' => "Meilleure moyenne de la période {$groupe->periode} {$groupe->annee} : {$moyenne} pour {$meilleur->eleve->nom}",
                    'moyenne' => $moyenne,
                    'periode' => $groupe->periode,
                    'annee' => $groupe->annee,
                    'periode_complete' => "{$groupe->periode} {$groupe->annee}",
                    'eleve' => [
                        'id' => $meilleur->eleve->id,
                        'nom' => $meilleur->eleve->nom,
                        'prenom' => $meilleur->eleve->prenom ?? '',
                        'classe' => $meilleur->eleve->classe->libelle ?? 'Classe inconnue',
                        'photo' => $meilleur->eleve->photo ?? null
                    ]
                ];
            }
        }

        // Si aucune période n’a de moyenne valide
        return [
            'message' => 'Aucune moyenne disponible pour les périodes précédentes.',
            'status' => 'empty'
        ];
    }
}
