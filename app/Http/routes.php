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
            'phones' => Phone::query()->latest()->paginate('40'),
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
    //
});
