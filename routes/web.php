<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Http\Request;
use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;
use App\WeOneWebSocketHandler;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

WebSocketsRouter::webSocket('/my-websocket', WeOneWebSocketHandler::class);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/socket-open', function (){

     return Config::get('global.SOCKET_CONNECTION');
});

Route::get('/my-websocket',function(){

     return Config::get('global.SOCKET_CONNECTION');

});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
