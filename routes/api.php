<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\QuoteController;
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
Route::post('forgot-password', [AuthController::class, 'forgot'])->name('password.email');
Route::post('reset-password', [AuthController::class, 'reset'])->name('password.update');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/me', [AuthController::class, 'me'])->middleware('jwt.auth')->name('me');

Route::get('google/login', [GoogleAuthController::class, 'loginWithGoogle'])->name('google.login');
Route::get('google/callback', [GoogleAuthController::class, 'callbackGoogle'])->name('google.callback');

Route::post('add-movie', [MovieController::class, 'store'])->name('movie.store');
Route::post('update-movie/{movie:id}', [MovieController::class, 'update'])->name('movie.update');
Route::post('delete-movie/{movie:id}', [MovieController::class, 'destroy'])->name('movie.destroy');
Route::get('movies/show', [MovieController::class, 'show'])->name('movie.show');
Route::get('movies/{movie:id}', [MovieController::class, 'get'])->name('movie.get');

Route::post('add-quote', [QuoteController::class, 'store'])->name('quote.store');
Route::post('update-quote/{quote:id}', [QuoteController::class, 'update'])->name('quote.update');
Route::post('delete-quote/{quote:id}', [QuoteController::class, 'destroy'])->name('quote.destroy');
Route::get('quotes/show', [QuoteController::class, 'show'])->name('quote.show');
Route::get('quotes/{quote:id}', [QuoteController::class, 'get'])->name('quote.get');

Route::post('add-comment', [CommentController::class, 'store'])->name('comment.store');
Route::get('comments/show', [CommentController::class, 'show'])->name('comment.show');
