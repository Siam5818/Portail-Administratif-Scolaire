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
}
