<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Articles\ArticleController;
use App\Http\Controllers\Web\Users\UserController;
use App\Http\Controllers\Web\Socials\SocialController;

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

Route::get('/', function () {
    return redirect()->route('article.index');
});
Route::get('/user', [UserController::class, 'index']);
Route::resource('article', ArticleController::class)->only(['index', 'create', 'show', 'edit']);

Route::get('/upload', function () {
    return view('upload');
});
Route::get('/trigger/{data}', function ($data) {
    echo "<p>You have sent $data</p>";
    event(new App\Events\GetRequestEvent($data));
});
Route::group(['as' => 'social.', 'prefix' => 'social'], function () {
    Route::group(['as' => 'line.', 'prefix' => 'line'], function () {
        Route::name("login")->get("/login", [SocialController::class, 'lineLogin']);
        Route::name("return")->get("/return", [SocialController::class, 'lineReturn']);
        #用戶資料刪除
        Route::name("delete")->get("/delete", [SocialController::class, 'lineDelete']);
    });
    Route::group(['as' => 'facebook.', 'prefix' => 'facebook'], function () {
        Route::name("login")->get("/login", [SocialController::class, 'facebookLogin']);
        Route::name("return")->get("/return", [SocialController::class, 'facebookReturn']);
        #用戶資料刪除
        Route::name("delete")->get("/delete", [SocialController::class, 'facebookDelete']);
    });
});
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
