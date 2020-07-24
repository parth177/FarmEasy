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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login', 'API\loginContoller@login');
// Route::post('registration', 'API\loginContoller@register');

route::post('User/attendence/new','API\attendenceController@create');
route::post('User/attendence/update/{id}','API\attendenceController@update');
route::get('User/attendence/show/{id}','API\attendenceController@show');
route::delete('User/attendence/delete/{id}','API\attendenceController@delete');

route::post('User/activities/new','API\activitiesController@create');
route::post('User/activities/update/{id}','API\activitiesController@update');
route::get('User/activities/show/{id}','API\activitiesController@show');
route::delete('User/activities/delete/{id}','API\activitiesController@delete');

route::post('User/query/new','API\queriesController@create');
route::post('User/query/update/{id}','API\queriesController@update');
route::get('User/query/show/{id}','API\queriesController@show');
route::delete('User/query/delete/{id}','API\queriesController@delete');
    
route::get('dashboard/{id}','API\deshboardController@show');

route::post('User/permit/new','API\permitController@create');
route::post('User/permit/update/{id}','API\permitController@update');
route::get('User/permit/show/{id}','API\permitController@show');
route::post('User/permit/delete/{id}','API\permitController@delete');

route::post('User/news/new','API\newsController@create');
route::post('User/news/update/{id}','API\newsController@update');
route::get('User/news/show/{id}','API\newsController@show');
route::delete('User/news/delete/{id}','API\newsController@delete');

route::post('User/notification/new','API\notificationController@create');
route::post('User/notification/update/{id}','API\notificationController@update');
route::get('User/notification/show/{id}','API\notificationController@show');
route::delete('User/notification/delete/{id}','API\notificationController@delete');