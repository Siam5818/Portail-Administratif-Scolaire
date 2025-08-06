<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bulletin extends Model
{
    use HasFactory, Notifiable, HasApiTokens;
    public const ETAT_NON_GENERE = 'non_generé';
    public const ETAT_PRE_REMPLI = 'pré_rempli';
    public const ETAT_VALIDE = 'validé';


    protected $guarded = [];

    protected $date = ['deleted_at'];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    public function getPdfUrlAttribute(): ?string
    {
        return $this->pdf_name
            ? asset("storage/bulletins/{$this->pdf_name}")
            : null;
    }

    public function getMentionAttribute()
    {
        $moyenne = $this->calculMoyenne();

        return match (true) {
            $moyenne >= 16 => 'Excellent',
            $moyenne >= 14 => 'Très Bien',
            $moyenne >= 12 => 'Bien',
            $moyenne >= 10 => 'Passable',
            $moyenne !== null => 'Insuffisant',
            default => '—',
        };
    }

    public function getAppreciationAttribute(): string
    {
        return match (true) {
            $this->note >= 16 => 'Excellent',
            $this->note >= 14 => 'Très Bien',
            $this->note >= 12 => 'Bien',
            $this->note >= 10 => 'Passable',
            $this->note !== null => 'Insuffisant',
            default => '—',
        };
    }

    public function calculMoyenne(): float
    {
        $notes = $this->eleve->notes()->where('periode', $this->periode)->get();

        $totalPoints = 0;
        $totalCoeff = 0;

        foreach ($notes as $note) {
            $coeff = $note->matiere->coefficient ?? 1;
            $totalPoints += $note->note * $coeff;
            $totalCoeff += $coeff;
        }

        return $totalCoeff > 0 ? round($totalPoints / $totalCoeff, 2) : 0;
    }

    public function notesParMatiere()
    {
        return $this->eleve->notes()
            ->where('periode', $this->periode)
            ->with('matiere')
            ->get()
            ->groupBy('matiere.nom');
    }

    public function hasPdf()
    {
        return !empty($this->pdf_name) && file_exists(storage_path("app/public/bulletins/{$this->pdf_name}"));
    }
}
