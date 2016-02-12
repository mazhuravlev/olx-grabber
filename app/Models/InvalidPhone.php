<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class InvalidPhone extends Model
{

    public $timestamps = false;
    public $fillable = [
        'phone'
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

}