<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solution extends Model
{
    use HasFactory;
    protected $fillable = [
        'idDeclaration',
        'idChefService',
        'Titre',
        'Description',
        'State',
        'dateResolution',
    ];



    public function declaration()
    {
        return $this->belongsTo('App\Models\Declaration', 'idDeclaration');
    }
    public function chefService()
    {
        return $this->belongsTo('App\Models\User', 'idChefService');
    }

    public function pic()
    {
        return $this->hasMany('App\Models\Picture_sol', 'idSolution');
    }

    public function attachesParent()
    {
        return $this->belongsTo('App\Models\Declaration', 'idDeclarationParent');
    }

    public function categorie()
    {
        return $this->belongsTo('App\Models\Categorie', 'idCategorie');
    }
    public function rapport()
    {
        return $this->hasOne('App\Models\Rapport', 'idDeclaration');
    }
}
