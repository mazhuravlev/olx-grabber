<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{

    public function getPhonesAttribute($value)
    {
        $data = json_decode($value);
        return is_array($data) ? $data : [$data];
    }

    public function getDetailsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setDetailsAttribute($value)
    {
        $this->attributes['details'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function phones()
    {
        return $this->belongsToMany(Phone::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }


}