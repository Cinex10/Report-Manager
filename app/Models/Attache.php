<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Attache extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'idDeclaration1',
        'idDeclaration2'
    ];

    public function dec()
    {
        return $this->belongsTo('App\Models\Declaration', 'idDeclaration2');
    }
}
