<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Offer;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;

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
                    'locations' => Location::all(),
                ]
            );
        } else {
            abort(404);
        }
    }

    function setOfferLocation(Offer $offer)
    {
        $location = Location::findOrFail(Input::get('location_id'));
        $offer->location()->associate($location);
        $offer->save();
        return view('offer_location_changed')->with([
            'offer' => $offer,
        ]);
    }

    function offer($id)
    {
        return view('offer')->with(
            [
                'offer' => Offer::findOrFail($id),
                'locations' => Location::all(),
            ]
        );
    }

}