<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Annonce extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'title',
        'description',
        'state',
        'dateDebut',
        'dateFin',
    ];

    public function pic()
    {
        return $this->hasMany("App\Models\Picture_ann", "idAnnonce");
    }
    public function user()
    {
        return $this->belongsTo("App\Models\User", 'idUser');
    }
}
