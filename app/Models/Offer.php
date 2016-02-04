<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{

    public function getPhoneAttribute($value)
    {
        $data = json_decode($value);
        return is_array($data) ? $data : [$data];
    }

}