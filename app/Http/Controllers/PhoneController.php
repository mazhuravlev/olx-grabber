<?php

namespace App\Http\Controllers;

use App\Models\InvalidPhone;
use App\Models\Phone;
use App\Http\Requests;

class PhoneController extends Controller
{
    function index()
    {
        return view('phones')->with(
            [
                'phones' => Phone::query()->orderBy('offer_count', 'desc')->paginate('40'),
            ]
        );
    }

    function phone($id)
    {
        return view('phone')->with(
            [
                'phone' => Phone::findOrFail($id),
            ]
        );
    }

    function invalid()
    {
        return view('invalid_phones')->with(
            [
                'phones' => InvalidPhone::all()->sortBy('phone')
            ]
        );
    }

}