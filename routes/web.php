<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

//Route::get('/', 'ImageController@filesSelect');
//Route::post('/', 'ImageController@uploadFiles');
//
//Route::get('/order/{uniqueId}', 'ImageController@fileOrderView');
//
//Route::get('/download/{uniqueId}', 'ImageController@download');

Route::get('/', function() {
    return view('index');
});
