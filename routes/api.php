<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Users\UserController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\Auth\LoginController;

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
Route::group(['middleware' => []], function () {
    # user
    Route::group(['middleware' => [], 'as' => 'user.', 'prefix' => 'user'], function () {
        Route::name("index")->get("/", [UserController::class, 'index']);
    });
    Route::group(['middleware' => ['VerifyApi']], function () {
        # user
        Route::group(['middleware' => [], 'as' => 'user.', 'prefix' => 'user'], function () {
            Route::name("store")->post("/", [UserController::class, 'store']);
            Route::name("update")->put("/{id}", [UserController::class, 'update']);
            Route::name("delete")->delete("/{id}", [UserController::class, 'destroy']);
        });
        # 上傳圖片
        Route::group(['as' => 'image.', 'prefix' => 'image'], function () {
            Route::name("store")->post("/", [ImageController::class, 'store']);
        });
    });

    # 登入相關
    Route::group(['as' => 'auth.', 'namespace' => 'Auth'], function () {
        Route::name("login")->post("/login", [LoginController::class, 'login']);
    });
});
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     dd('123');
//     return $request->user();
// });
