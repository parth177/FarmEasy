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
Route::post('login', 'API\logInContoller@login');
Route::post('login/social','API\logInContoller@socialLogin');
Route::post('registration', 'API\logInContoller@register');
route::get('user/profile/{uid}','API\logInContoller@profileView');
route::post('user/profile/{uid}','API\logInContoller@update');
route::post('user/password/reset','API\logInContoller@passReset');
route::post('add/otherinfo/{uid}','API\logInContoller@addOtherInfo');
route::get('user/info/{email}','API\userController@userInfo');

route::post('User/attendence/new','API\attendenceController@create');
route::post('User/attendence/update/{id}','API\attendenceController@update');
route::get('User/attendence/show/{id}','API\attendenceController@show');
route::get('attendence/{uid}','API\attendenceController@attendence');
route::delete('User/attendence/delete/{id}','API\attendenceController@delete');


route::post('blog/new','API\blogController@new');
route::post('blog/update/{bid}','API\blogController@update');
route::delete('blog/delete/{bid}','API\blogController@delete');
route::get('blog/list/{uid}','API\blogController@show');

route::post('User/activities/new','API\activitiesController@create');
route::post('User/activities/update/{id}','API\activitiesController@update');
route::get('activities/{uid}','API\activitiesController@show2');
route::get('activities/{date}/{uid}','API\activitiesController@showdate');
route::get('activities/self/{date}/{uid}','API\activitiesController@showdate2');
route::get('User/activities/show/{id}','API\activitiesController@show');
route::get('User/activities/show/user/{id}','API\activitiesController@userShow');
route::delete('User/activities/delete/{id}','API\activitiesController@delete');

route::get('activity/response/{aid}','API\activitiesController@activityResponse');
route::post('add/activity/response','API\activitiesController@addActivityResponse');
route::post('update/activity/response/{arid}','API\activitiesController@updateActivityResponse');

route::post('User/query/new','API\queriesController@create');
route::post('User/query/update/{id}','API\queriesController@update');
route::get('User/query/show/{id}','API\queriesController@show');
route::get('User/query/show/user/{id}','API\queriesController@userShow');
route::delete('User/query/delete/{id}','API\queriesController@delete');

route::get('dashboard/{id}','API\deshboardController@show');

route::post('User/permit/new','API\permitController@create');
route::post('User/permit/update/{id}','API\permitController@update');
route::get('User/permit/show/{id}','API\permitController@show');
route::get('User/permit/show/user/{id}','API\permitController@userShow');
route::delete('User/permit/delete/{id}','API\permitController@delete');

route::post('User/news/new','API\newsController@create');
route::post('User/news/update/{id}','API\newsController@update');
route::get('User/news/show/{id}','API\newsController@show');
route::delete('User/news/delete/{id}','API\newsController@delete');

route::post('User/notification/new','API\notificationController@create');
route::post('User/notification/update/{id}','API\notificationController@update');
route::get('User/notification/show/{id}','API\notificationController@show');
route::get('User/notification/sender/{id}','API\notificationController@senderShow');
route::get('User/notification/receiver/{id}','API\notificationController@receiverShow');
route::delete('User/notification/delete/{id}','API\notificationController@delete');

route::post('vendor/new/stock','API\stockController@create');
route::post('vendor/new/order','API\orderController@create');
route::post('vendor/update/orderSatus','API\orderController@updateStatus');
route::post('vendor/update/order/isApproved','API\orderController@updateisApproved');
route::get('vendor/history/{id}','API\orderController@history');

route::get('supplies/{uid}','API\stockController@supplies');
route::post('supplies/update/{sid}','API\stockController@update');
route::delete('supplies/delete/{sid}','API\stockController@delete');

route::post('add/output','API\outputController@new');
route::get('view/output/{fid}','API\outputController@show');
route::delete('output/delete/{oid}','API\outputController@delete');
route::post('output/update/{oid}','API\outputController@update');

route::post('add/farm','API\farmController@new');
route::post('view/farm/{uid}','API\farmController@show');

route::post('add/equipment','API\equipmentController@new');
route::post('edit/equipment/{eid}','API\equipmentController@edit');
route::get('show/equipment/{fid}','API\equipmentController@show');
route::delete('delete/equipment/{eid}','API\equipmentController@delete');

route::post('add/resource','API\logInContoller@addResource');
route::get('farmers/{uid}','API\userController@farmars');

route::get('chat/user/{uid}','API\chatController@userList');

route::post('upload/image','API\userController@uploadImage');

route::post('chart/data','API\userController@chart');
