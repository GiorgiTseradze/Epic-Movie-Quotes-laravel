<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\MovieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
	return $request->user();
});

Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/me', [AuthController::class, 'me'])->middleware('jwt.auth')->name('me');

Route::get('google/login', [GoogleAuthController::class, 'loginWithGoogle'])->name('google.login');
Route::get('google/callback', [GoogleAuthController::class, 'callbackGoogle'])->name('google.callback');

Route::post('add-movie', [MovieController::class, 'store'])->name('movie.store');
