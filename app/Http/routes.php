<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\Models\Location;
use App\Models\Offer;
use App\Models\Phone;
use Carbon\Carbon;

Route::get('/', function () {
    return Redirect::to('/offers');
});

Route::get('/offers', function () {
    return view('offers')->with(
        [
            'offers' => Offer::query()->latest()->paginate('40'),
        ]
    );
});

Route::get('/offer/olx_id/{olxId}', function ($olxId) {
    if ($offer = Offer::where('olx_id', $olxId)->first()) {
        return view('offer')->with(
            [
                'offer' => $offer,
            ]
        );
    } else {
        abort(404);
    }
});

Route::get('/offer/{id}', function ($id) {
    return view('offer')->with(
        [
            'offer' => Offer::findOrFail($id),
        ]
    );
});

Route::get('/phones', function () {
    return view('phones')->with(
        [
            'phones' => Phone::query()->orderBy('offer_count', 'desc')->paginate('40'),
        ]
    );
});

Route::get('/phone/{id}', function ($id) {
    return view('phone')->with(
        [
            'phone' => Phone::findOrFail($id),
        ]
    );
});

Route::get('/locations', function () {
    return view('locations')->with(
        [
            'locations' => Location::all()->sortBy('location'),
            'regions' => ['sev', 'simf', 'evp', 'feo'],
        ]
    );
});

Route::group(
    [
        'prefix' => 'api'
    ], function () {
    Route::get('/offers/{timestamp?}', function ($timestamp = 0) {
        $offers = Offer::whereDate('created_at', '>', Carbon::createFromTimestamp($timestamp))->get();
        return response()->json($offers->toArray(), 200, [], JSON_UNESCAPED_UNICODE);
    });
    Route::get('/phone/{phone}/offers', function ($phone) {
        /** @var Phone $phone */
        $phone = Phone::findOrFail($phone);
        return response()->json($phone->offers->toArray(), 200, [], JSON_UNESCAPED_UNICODE);
    });
    Route::get('/phone/{phone}/offers/count', function ($phone) {
        /** @var Phone $phone */
        $phone = Phone::findOrFail($phone);
        return response()->json(['count' => $phone->offers()->count()], 200, [], JSON_UNESCAPED_UNICODE);
    });
}
);

Route::group(
    [
        'prefix' => 'details'
    ],
    function () {
        Route::get('/', function () {
            return view('details')->with(
                [
                    'detailsParameters' => \App\Models\DetailsParameter::all(),
                ]
            );
        });
        Route::get('/{id}', function ($id) {
            /** @var \App\Models\DetailsParameter $detailsParameter */
            $detailsParameter = \App\Models\DetailsParameter::findOrFail($id);
            return view('details_parameter')->with(
                [
                    'detailsParameter' => $detailsParameter,
                    'detailsValues' => $detailsParameter->detailsValues()->get(),
                ]
            );
        });
    }
);


Route::group(
    [
        'prefix' => 'export'
    ],
    function () {
        Route::get('/', function () {
            return view('export')->with([
                'regions' => ['sev', 'simf', 'evp', 'ubk', 'feo'],
            ]);
        });
        Route::post('/', function (\Symfony\Component\HttpFoundation\Request $request) {
            $regions = $request->get('regions');
            $daysCount = $request->get('day_count');
            $date = Carbon::createFromTimestamp(time() - $daysCount * 86400);
            $offers = Offer::query()->where('created_at', '>', $date)->get();
            echo '<ul>';
            /** @var \Illuminate\Bus\Dispatcher $dispatcher */
            $dispatcher = app('Illuminate\Bus\Dispatcher');
            foreach ($offers as $offer) {
                /** @var Offer $offer */
                if ($location = $offer->location()->first() and in_array($location->region, $regions, true)) {
                    $dispatcher->dispatch(
                        (new \App\Jobs\ExportOffer($offer))
                            ->onQueue('export_offers')
                    );
                    echo "<li>created export job for <a href='/offer/'{$offer->id}'>{$offer->id}</a></li>";
                }
            }
            echo '</ul><p>done</p>';
        });
    }
);
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {

});

Route::group(
    [
        'prefix' => 'rest'
    ], function () {
    Route::resource('location', 'LocationController');
    Route::resource('details_parameter', 'DetailsParameterController');
    Route::resource('details_parameter.details_value', 'DetailsValueController');
}
);