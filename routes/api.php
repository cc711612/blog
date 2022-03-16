<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Users\UserController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Articles\ArticleController;
use App\Http\Controllers\Api\Comments\CommentController;
use App\Http\Controllers\Api\Bots\LineController;
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
Route::group(['middleware' => [], 'as' => 'api.'], function () {
    # 需要member_token的
    Route::group(['middleware' => ['VerifyApi']], function () {
        # user
        Route::group(['middleware' => [], 'as' => 'user.', 'prefix' => 'user'], function () {
            Route::name("update")->put("/{id}", [UserController::class, 'update']);
            Route::name("delete")->delete("/{id}", [UserController::class, 'destroy']);
        });
        # 文章
        Route::resource('article', ArticleController::class)->except(['index', 'show']);
        # 留言
        Route::resource('comment', CommentController::class)->except(['index']);
        # 登出相關
        Route::group(['as' => 'auth.', 'namespace' => 'Auth'], function () {
            Route::name("logout")->post("/logout", [LogoutController::class, 'logout']);
        });
    });
    # 登入相關
    Route::group(['as' => 'auth.', 'namespace' => 'Auth'], function () {
        Route::name("login")->post("/login", [LoginController::class, 'login']);
    });
    # user
    Route::group(['middleware' => [], 'as' => 'user.', 'prefix' => 'user'], function () {
        Route::name("index")->get("/", [UserController::class, 'index']);
        Route::name("show")->get("/{id}", [UserController::class, 'show']);
        Route::name("store")->post("/", [UserController::class, 'store']);
    });
    # 文章
    Route::group(['middleware' => [], 'as' => 'article.', 'prefix' => 'article'], function () {
        Route::name("index")->get("/", [ArticleController::class, 'index']);
        Route::name("show")->get("/{article}", [ArticleController::class, 'show']);
    });
    # 留言
    Route::group(['middleware' => [], 'as' => 'comment.', 'prefix' => 'comment'], function () {
        Route::name("index")->get("/", [CommentController::class, 'index']);
    });
    # 上傳圖片
    Route::group(['as' => 'image.', 'prefix' => 'image'], function () {
        Route::name("store")->post("/", [ImageController::class, 'store']);
    });
    # Webhook
    Route::group(['as' => 'webhook.', 'prefix' => 'webhook'], function () {
        Route::group(['as' => 'line.', 'prefix' => 'line'], function () {
            Route::name("reply")->any("/reply", [LineController::class, 'reply']);
            Route::name("send")->any("/send", [LineController::class, 'send']);
            Route::name("test")->any("/test", [LineController::class, 'test']);
        });
    });
});
Route::fallback(function () {
    return response([
        'code' => 400,
        'status' => false,
        'message' => '不支援此方法'
    ]);
});
