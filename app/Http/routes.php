<?php

Route::get('/', function () {
    return Redirect::to('/offers');
});
Route::get('/offers', ['middleware' => ['web', 'auth'], 'OfferController@index']);
Route::get('/offer/olx_id/{olxId}', ['middleware' => ['web', 'auth'], 'OfferController@findByOlxId']);
Route::get('/offer/{id}', ['middleware' => ['web', 'auth'], 'OfferController@offer']);
Route::get('/phones', ['middleware' => ['web', 'auth'], 'PhoneController@index']);
Route::get('/phone/{id}', ['middleware' => ['web', 'auth'], 'PhoneController@phone']);
Route::get('/locations', ['middleware' => ['web', 'auth'], 'LocationController@index']);

Route::group(
    [
        'prefix' => 'api',
        'middleware' => ['web', 'auth']
    ],
    function () {
        Route::get('/offers/{timestamp?}', 'ApiController@offers');
        Route::get('/phone/{phone}/offers', 'ApiController@offersByPhone');
        Route::get('/phone/{phone}/offers/count', 'ApiController@countByPhone');
}
);

Route::group(
    [
        'prefix' => 'details',
        'middleware' => ['web', 'auth']
    ],
    function () {
        Route::get('/', 'DetailsController@index');
        Route::get('/{id}', 'DetailsController@parameter');
    }
);

Route::group(
    [
        'prefix' => 'export',
        'middleware' => ['web', 'auth']
    ],
    function () {
        Route::get('/', 'ExportController@index');
        Route::post('/', 'ExportController@export');
    }
);

Route::group(
    [
        'prefix' => 'rest',
        'middleware' => ['web', 'auth']
    ], function () {
    Route::resource('location', 'LocationController');
    Route::resource('details_parameter', 'DetailsParameterController');
    Route::resource('details_parameter.details_value', 'DetailsValueController');
}
);

Route::group(
    [
        'prefix' => 'auth',
        'middleware' => ['web']
    ],
    function () {
        Route::get('login', 'Auth\AuthController@getLogin');
        Route::post('login', 'Auth\AuthController@postLogin');
        Route::get('logout', 'Auth\AuthController@getLogout');
    }
);