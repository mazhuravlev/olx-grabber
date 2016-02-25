<?php

Route::get('/', function () {
    return Redirect::to('/offers');
});
Route::get('/offers', 'OfferController@index')->middleware(['web', 'auth']);
Route::get('/offer/olx_id/{olxId}', 'OfferController@findByOlxId')->middleware(['web', 'auth']);
Route::get('/offer/{id}', 'OfferController@offer')->middleware(['web', 'auth']);
Route::get('/phones', 'PhoneController@index')->middleware(['web', 'auth']);
Route::get('/phones/invalid', 'PhoneController@invalid')->middleware(['web', 'auth']);
Route::get('/phone/{id}', 'PhoneController@phone')->middleware(['web', 'auth']);
Route::get('/locations', 'LocationController@index')->middleware(['web', 'auth']);

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
        'prefix' => 'log',
        'middleware' => ['web', 'auth']

    ],
    function () {
        Route::get('/', 'LogController@index');
        Route::post('/', 'LogController@file');
        Route::post('/truncate', 'LogController@truncate');
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