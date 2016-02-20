<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailsParameter extends Model
{

    public $timestamps = false;
    public $fillable = [
        'parameter'
    ];

    public function detailsValues()
    {
        return $this->hasMany(DetailsValue::class);
    }

}
