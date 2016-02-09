<?php

namespace App\Services;


use App\Models\Offer;

class Statistics
{
    public function offerCount()
    {
        return Offer::all()->count();
    }
}