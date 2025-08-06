<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $guarded = [];

    protected $casts = [
        'note' => 'float',
    ];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    protected static function booted()
    {
        static::creating(function ($note) {
            if (empty($note->annee_scolaire)) {
                $note->annee_scolaire = now()->month >= 9
                    ? now()->year . '-' . (now()->year + 1)
                    : (now()->year - 1) . '-' . now()->year;
            }
        });
    }
}
