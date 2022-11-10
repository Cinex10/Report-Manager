<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Picture_ann extends Model
{
    public function dec()
    {
        return $this->belongsTo('App\Models\Annonce');
    }
}
