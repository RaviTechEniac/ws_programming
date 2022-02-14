<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Broadcast;


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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



// authentication
Route::post('/auth/register', [AuthController::class, 'register']);

Route::post('/auth/login', [AuthController::class, 'login']);


Route::group(['middleware' => ['auth:sanctum']], function () {

     Route::post('/auth/logout', [AuthController::class, 'logout']);

     Route::group(['prefix'=>'staff'], function(){

               Route::get('/show',[StaffController::class,'show']);

               Route::post('/store',[StaffController::class,'store']);

     });

});



Route::get('/socket', function () {
     $user = User::all();
     event(new App\Events\GetRequestEvent($user));  
});

Route::get('/check-socket-connection', function () {
     return Config::get('global.SOCKET_CONNECTION');
});

Route::get('/', function () {
     

});



