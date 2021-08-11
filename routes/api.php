<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Users\UserController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Articles\ArticleController;

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
Route::group(['middleware' => [],'as'=>'api.'], function () {
    # user
    Route::group(['middleware' => [], 'as' => 'user.', 'prefix' => 'user'], function () {
        Route::name("index")->get("/", [UserController::class, 'index']);
        Route::name("show")->get("/{id}", [UserController::class, 'show']);
        Route::name("store")->post("/", [UserController::class, 'store']);
    });
    # 上傳圖片
    Route::group(['as' => 'image.', 'prefix' => 'image'], function () {
        Route::name("store")->post("/", [ImageController::class, 'store']);
    });
    Route::group(['middleware' => ['VerifyApi']], function () {
        # user
        Route::group(['middleware' => [], 'as' => 'user.', 'prefix' => 'user'], function () {
            Route::name("update")->put("/{id}", [UserController::class, 'update']);
            Route::name("delete")->delete("/{id}", [UserController::class, 'destroy']);
        });
        Route::resource('article', ArticleController::class);
        # 登出相關
        Route::group(['as' => 'auth.', 'namespace' => 'Auth'], function () {
            Route::name("logout")->post("/logout", [LogoutController::class, 'logout']);
        });
    });

    # 登入相關
    Route::group(['as' => 'auth.', 'namespace' => 'Auth'], function () {
        Route::name("login")->post("/login", [LoginController::class, 'login']);
    });
});
