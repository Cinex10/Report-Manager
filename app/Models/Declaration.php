<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;




class Declaration extends Model
{
    use HasFactory;
    protected $fillable = [
        'idUser',
        'idCategorie',
        'Titre',
        'Description',
        'Lieu',
        'State',
        'dateValidation',

    ];

    public function attache($id)
    {
        $this->idDeclarationParent = $id;
        $this->save();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'idUser');
    }

    public function pic()
    {
        return $this->hasMany('App\Models\Picture_dec', 'idDeclaration');
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
    public function solution()
    {
        return $this->hasOne('App\Models\Solution', 'idDeclaration');
    }
}
