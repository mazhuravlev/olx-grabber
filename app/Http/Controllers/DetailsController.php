<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DetailsController extends Controller
{

    function index()
    {
        return view('details')->with(
            [
                'detailsParameters' => \App\Models\DetailsParameter::all(),
            ]
        );
    }

    function parameter($id)
    {
        /** @var \App\Models\DetailsParameter $detailsParameter */
        $detailsParameter = \App\Models\DetailsParameter::findOrFail($id);
        return view('details_parameter')->with(
            [
                'detailsParameter' => $detailsParameter,
                'detailsValues' => $detailsParameter->detailsValues()->get(),
            ]
        );
    }

}