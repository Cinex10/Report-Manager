<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Picture_sol extends Model
{
    use HasFactory;
    protected $fillable = [
        'idSolution',
        'picture'
    ];
    public function solution()
    {
        return $this->belongsTo('App\Models\Solution');
    }
}
