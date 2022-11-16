<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailsController;

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

use App\Http\Controllers\AuthController;

//Route::post('/register',[AuthController::class,'register']);
Route::post('/login/{id}', [AuthController::class, 'loginWithToken']);

Route::group(['middleware' => 'auth:sanctum','prefix' => 'admin',], function () {

  //Route::resource('emails', EmailsController::class);
  Route::post('/email', [EmailsController::class, 'email']);
  Route::post('/lote_emails', [EmailsController::class, 'lote_emails']);
  Route::post('/solicitud_lote_emails', [EmailsController::class, 'solicitud_lote_emails']);

});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
