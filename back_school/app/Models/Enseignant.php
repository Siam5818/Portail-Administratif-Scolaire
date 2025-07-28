<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enseignant extends Model
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function matieres()
    {
        return $this->hasMany(Matiere::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }
}
