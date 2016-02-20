<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailsParameter extends Model
{

    public $timestamps = false;
    public $fillable = [
        'parameter', 'export_property', 'is_integer_field'
    ];

    public function detailsValues()
    {
        return $this->hasMany(DetailsValue::class);
    }

}
