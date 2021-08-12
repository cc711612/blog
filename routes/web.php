<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Articles\ArticleController;
use App\Http\Controllers\Web\Users\UserController;
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

Route::get('/',function (){
    return redirect()->route('article.index');
});
Route::get('/user',[UserController::class, 'index']);
Route::resource('article', ArticleController::class)->only(['index','create','show','edit']);

Route::get('/upload', function () {
    return view('upload');
});
Route::get('/trigger/{data}', function ($data) {
    echo "<p>You have sent $data</p>";
    event(new App\Events\GetRequestEvent($data));
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
