<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailsValue extends Model
{

    public $timestamps = false;
    public $fillable = [
        'value'
    ];

    public function detailsParameter()
    {
        return $this->belongsTo(DetailsParameter::class);
    }

}
