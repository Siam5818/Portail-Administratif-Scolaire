<?php

namespace App\Services;

use App\Models\Bulletin;
use App\Models\Eleve;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\NotificationService;

class BulletinService
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Génère un bulletin pour un élève donné, à une période et une année précises
     */
    public function generateBulletin(Eleve $eleve, string $periode, int $annee): Bulletin
    {
        // Récupération des notes
        $notes = $eleve->notes()->where('periode', $periode)->get();

        // Calcul moyenne pondérée
        $moyenne = $this->calculerMoyennePonderee($notes);
        $mention = $this->calculerMention($moyenne);

        // Création du bulletin
        $bulletin = Bulletin::create([
            'eleve_id' => $eleve->id,
            'periode' => $periode,
            'annee' => $annee,
            'pdf_name' => null,
        ]);

        // Génération du PDF
        $pdfFileName = "bulletin_{$eleve->id}_{$periode}_{$annee}.pdf";
        $pdfContent = View::make('pdf.bulletin', [
            'bulletin' => $bulletin,
            'eleve' => $eleve,
            'notes' => $notes,
            'moyenne' => $moyenne,
            'mention' => $mention,
        ]);

        $pdf = Pdf::loadHTML($pdfContent)->setPaper('A4', 'portrait');
        Storage::disk('public')->put("bulletins/{$pdfFileName}", $pdf->output());

        $bulletin->update(['pdf_name' => $pdfFileName]);

        // Envoi des notifications
        $this->notificationService->envoyerNotificationsBulletin($bulletin);

        return $bulletin;
    }

    /**
     * Regenere un bulletin
     */
    public function regenererPdf(Bulletin $bulletin): Bulletin
    {
        $notes = $bulletin->eleve->notes()->where('periode', $bulletin->periode)->get();
        $moyenne = $this->calculerMoyennePonderee($notes);
        $mention = $this->calculerMention($moyenne);

        $pdfFileName = "bulletin_{$bulletin->eleve->id}_{$bulletin->periode}_{$bulletin->annee}.pdf";
        $pdfContent = View::make('pdf.bulletin', [
            'bulletin' => $bulletin,
            'eleve' => $bulletin->eleve,
            'notes' => $notes,
            'moyenne' => $moyenne,
            'mention' => $mention,
        ]);

        $pdf = Pdf::loadHTML($pdfContent)->setPaper('A4', 'portrait');
        Storage::disk('public')->put("bulletins/{$pdfFileName}", $pdf->output());

        $bulletin->update(['pdf_name' => $pdfFileName]);

        $this->notificationService->envoyerNotificationsBulletin($bulletin);

        return $bulletin;
    }

    /**
     * Retourne tous les bulletins avec PDF disponibles
     */
    public function bulletinsDisponibles()
    {
        return Bulletin::whereNotNull('pdf_name')->get();
    }

    /**
     *  Vérifier existence d'un bulletins
     */
    public function bulletinExiste(Eleve $eleve, string $periode, int $annee): ?Bulletin
    {
        return Bulletin::where('eleve_id', $eleve->id)
            ->where('periode', $periode)
            ->where('annee', $annee)
            ->first();
    }

    /**
     * Supprime le fichier PDF associé à un bulletin
     */
    public function supprimerFichierPdf(Bulletin $bulletin): bool
    {
        return Storage::disk('public')->delete("bulletins/{$bulletin->pdf_name}");
    }

    /**
     * Suppression complet
     */
    public function supprimerBulletinComplet(Bulletin $bulletin): bool
    {
        $this->supprimerFichierPdf($bulletin);
        return $bulletin->delete();
    }

    /**
     * Calcule la moyenne pondérée d’un ensemble de notes
     */
    public function calculerMoyennePonderee($notes): float
    {
        $totalPoints = 0;
        $totalCoeff = 0;

        foreach ($notes as $note) {
            $coeff = $note->matiere->coefficient ?? 1;
            $totalPoints += $note->note * $coeff;
            $totalCoeff += $coeff;
        }

        return $totalCoeff > 0 ? round($totalPoints / $totalCoeff, 2) : 0;
    }

    /**
     * Déduit la mention à partir de la moyenne
     */
    public function calculerMention(float $moyenne): string
    {
        return match (true) {
            $moyenne >= 16 => 'Très Bien',
            $moyenne >= 14 => 'Bien',
            $moyenne >= 12 => 'Assez Bien',
            $moyenne >= 10 => 'Passable',
            default => 'Insuffisant',
        };
    }

    /**
     * Génère un tableau structuré JSON pour affichage
     */
    public function getBulletinJson(Bulletin $bulletin): array
    {
        $notes = $bulletin->eleve->notes()->where('periode', $bulletin->periode)->get();
        $moyenne = $this->calculerMoyennePonderee($notes);
        $mention = $this->calculerMention($moyenne);

        return [
            'eleve' => [
                'nom' => $bulletin->eleve->nom,
                'classe' => $bulletin->eleve->classe->libelle ?? '',
            ],
            'periode' => $bulletin->periode,
            'annee' => $bulletin->annee,
            'notes' => $notes->map(fn($note) => [
                'matiere' => $note->matiere->nom,
                'note' => $note->note,
                'coefficient' => $note->matiere->coefficient ?? 1,
            ]),
            'moyenne' => $moyenne,
            'mention' => $mention,
            'pdf' => $bulletin->pdf_name ? asset("storage/bulletins/{$bulletin->pdf_name}") : null,
        ];
    }

    public function searchBulletins(array $criteria)
    {
        $query = Bulletin::query();

        if (!empty($criteria['eleve_id'])) {
            $query->where('eleve_id', $criteria['eleve_id']);
        }

        if (!empty($criteria['periode'])) {
            $query->where('periode', $criteria['periode']);
        }

        if (!empty($criteria['annee'])) {
            $query->where('annee', $criteria['annee']);
        }

        return $query->with('eleve.classe')->get();
    }
}
