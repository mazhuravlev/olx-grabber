<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Phone;
use Illuminate\Http\Request;
use App\Http\Requests;

class ApiController extends Controller
{

    public function offers($timestamp = 0)
    {
        $offers = Offer::whereDate('created_at', '>', Carbon::createFromTimestamp($timestamp))->get();
        return response()->json($offers->toArray(), 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function offersByPhone($phone)
    {
        /** @var Phone $phone */
        $phone = Phone::findOrFail($phone);
        return response()->json($phone->offers->toArray(), 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function countByPhone($phone)
    {
        /** @var Phone $phone */
        $phone = Phone::findOrFail($phone);
        return response()->json(['count' => $phone->offers()->count()], 200, [], JSON_UNESCAPED_UNICODE);
    }

}
