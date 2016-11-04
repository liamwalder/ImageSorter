<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/upload', 'UploadController@upload');
Route::get('/order/{uniqueId}', 'ImageController@listing');
Route::post('/order', 'OrderController@order');
Route::get('/download/{uniqueId}', 'DownloadController@download');