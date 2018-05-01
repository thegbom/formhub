<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/formlines', 'FormHubController@index');

Route::get('/formlines/{id}', 'FormHubController@destroy');

Route::get('/table', 'FormHubController@getTable');

Route::get('/column/{table}', 'FormHubController@getColumnList');

Route::get('/select', 'FormHubController@languageSelect');

Route::get('/tablehub', 'FormHubController@tableHub');
Route::post('/tablehub', 'FormHubController@storeTableHub');

Route::get('/formlines/componenttype/{table}', 'FormHubController@generateComponentTypeForm');

Route::post('/formlines','FormHubController@storeFormLines');

Route::get('/formlines/labels/{table}','FormLabelController@showLabelController');
Route::get('/formlines/labels/{language}/{formid}','FormLabelController@showLabel');
Route::post('/formlines/labels/{table}','FormLabelController@saveLabel');

Route::get('/formlines/select/{language}/{formid}','FormLabelController@showSelectLabel');
Route::post('/formlines/select/{language}/{formid}','FormLabelController@saveSelectLabel');
Route::get('/formlines/select/delete/row/{id}','FormLabelController@deleteSelectLabel');

Route::get('/form/{table}/{language}','FormHubController@showUserTable');
Route::post('/form/{table}','FormHubController@storeUserTable');