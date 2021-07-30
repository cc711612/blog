<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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
Route::group(['middleware' => [] ], function () {
    # user
    Route::group(['as' => 'user.','prefix'=>'user'], function(){
        Route::name("index")->get("/",[UserController::class, 'index']);
        Route::name("store")->post("/",[UserController::class, 'store']);
        Route::name("update")->put("/{id}",[UserController::class, 'update']);
        Route::name("delete")->delete("/{id}",[UserController::class, 'destroy']);
    });
});
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     dd('123');
//     return $request->user();
// });
