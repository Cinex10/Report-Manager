<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Categorie extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'idService',
        'name',
        'Description'
    ];

    public function service()
    {
        return $this->belongsTo('App\Models\Service', 'idService');
    }

    public function declaration()
    {
        return $this->hasMany('App\Models\Declaration', 'idCategorie');
    }
}
