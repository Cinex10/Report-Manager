<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'phone',
        'email',
        'description',
        'tel',
        'idChefService',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */


    public function categories()
    {
        return $this->hasMany('App\Models\Categorie', 'idService');
    }
    public function chefService()
    {
        return $this->belongsTo('App\Models\User', 'idChefService');
    }
}
