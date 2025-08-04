<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $guarded = [];

    public function eleves()
    {
        return $this->hasMany(Eleve::class);
    }

    public function matieres()
    {
        return $this->hasMany(Matiere::class);
    }

    public function getEffectifAttribute(): int
    {
        return $this->eleves()->count();
    }

    public function getNombreMatieresAttribute(): int
    {
        return $this->matieres()->count();
    }
}
