<?php

namespace App\Http\Controllers;

use App\Models\Phone;
use Illuminate\Http\Request;
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

}