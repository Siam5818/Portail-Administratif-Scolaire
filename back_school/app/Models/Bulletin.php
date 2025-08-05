<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bulletin extends Model
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $guarded = [];

    protected $date = ['deleted_at'];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    public function getPdfUrlAttribute()
    {
        return url('storage/bulletins/' . $this->pdf_name);
    }

    public function getMentionAttribute()
    {
        $moyenne = $this->calculMoyenne();

        return match (true) {
            $moyenne >= 16 => 'Très Bien',
            $moyenne >= 14 => 'Bien',
            $moyenne >= 12 => 'Assez Bien',
            $moyenne >= 10 => 'Passable',
            default => 'Insuffisant',
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
