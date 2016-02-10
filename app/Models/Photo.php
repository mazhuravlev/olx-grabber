<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{

    public $timestamps = false;
    public $fillable = [
        'url',
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

}