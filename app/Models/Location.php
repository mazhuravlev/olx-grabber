<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{

    public $timestamps = false;
    public $fillable = [
        'location'
    ];

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

}
