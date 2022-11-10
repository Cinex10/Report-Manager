<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Rapport extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'iDeclaration',
        'file',
        'description',
        'title'
    ];

    public function declaration()
    {
        return $this->belongsTo('App\Models\Declaration');
    }
}
