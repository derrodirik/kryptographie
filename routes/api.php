<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('caesar')->name('caesar.')->group(function () {
	Route::post('encrypt', 'Api\CaesarController@encrypt');
	Route::post('decrypt', 'Api\CaesarController@decrypt');
	Route::post('crack', 'Api\CaesarController@crack');
});
