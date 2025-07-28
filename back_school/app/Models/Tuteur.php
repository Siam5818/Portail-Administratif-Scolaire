<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tuteur extends Model
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function enfants()
    {
        return $this->hasMany(Eleve::class, 'tuteur_id');
    }
}
