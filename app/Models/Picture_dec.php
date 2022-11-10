<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Picture_dec extends Model
{
    use HasFactory;
    protected $fillable = [
        'idDeclaration',
        'picture'
    ];
    public function declaration()
    {
        return $this->belongsTo('App\Models\Declaration');
    }
}
