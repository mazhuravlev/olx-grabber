<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;
use App\Http\Requests;

class OfferController extends Controller
{

    function index()
    {
        return view('offers')->with(
            [
                'offers' => Offer::query()->latest()->paginate('40'),
            ]
        );
    }

    function findByOlxId($olxId)
    {
        if ($offer = Offer::where('olx_id', $olxId)->first()) {
            return view('offer')->with(
                [
                    'offer' => $offer,
                ]
            );
        } else {
            abort(404);
        }
    }

    function offer($id)
    {
        return view('offer')->with(
            [
                'offer' => Offer::findOrFail($id),
            ]
        );
    }

}