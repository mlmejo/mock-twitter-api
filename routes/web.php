<?php

use App\Http\Controllers\FollowerController;
use App\Http\Controllers\TweetController;
use Illuminate\Support\Facades\Route;

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
    return ['Laravel' => app()->version()];
});

Route::resource('/tweets', TweetController::class)
    ->except(['create', 'edit'])
    ->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/users/suggested', [FollowerController::class, 'index']);
    Route::post('/users/{user}/followers', [FollowerController::class, 'store']);
    Route::get('/users/{user}/followers', [FollowerController::class, 'show']);
    Route::delete('/users/{user}/followers', [FollowerController::class, 'destroy']);
});

require __DIR__ . '/auth.php';
