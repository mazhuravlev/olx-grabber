<?php

Route::get('/', function () {
    return Redirect::to('/offers');
});
Route::get('/offers', 'OfferController@index');
Route::get('/offer/olx_id/{olxId}', 'OfferController@findByOlxId');
Route::get('/offer/{id}', 'OfferController@offer');
Route::get('/phones', 'PhoneController@index');
Route::get('/phone/{id}', 'PhoneController@phone');
Route::get('/locations', 'LocationController@index');

Route::group(
    [
        'prefix' => 'api'
    ],
    function () {
        Route::get('/offers/{timestamp?}', 'ApiController@offers');
        Route::get('/phone/{phone}/offers', 'ApiController@offersByPhone');
        Route::get('/phone/{phone}/offers/count', 'ApiController@countByPhone');
}
);

Route::group(
    [
        'prefix' => 'details'
    ],
    function () {
        Route::get('/', 'DetailsController@index');
        Route::get('/{id}', 'DetailsController@parameter');
    }
);

Route::group(
    [
        'prefix' => 'export'
    ],
    function () {
        Route::get('/', 'ExportController@index');
        Route::post('/', 'ExportController@export');
    }
);

Route::group(
    [
        'prefix' => 'rest'
    ], function () {
    Route::resource('location', 'LocationController');
    Route::resource('details_parameter', 'DetailsParameterController');
    Route::resource('details_parameter.details_value', 'DetailsValueController');
}
);