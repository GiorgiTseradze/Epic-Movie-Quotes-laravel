<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
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

Route::get('/me', [AuthController::class, 'me'])->middleware('jwt.auth')->name('me');

Route::get('google/login', [GoogleAuthController::class, 'loginWithGoogle'])->name('google.login');
Route::get('google/callback', [GoogleAuthController::class, 'callbackGoogle'])->name('google.callback');

Route::controller(AuthController::class)
->group(function () {
	Route::post('register', 'register')->name('register');
	Route::post('login', 'login')->name('login');
	Route::post('forgot-password', 'forgot')->name('password.email');
	Route::post('reset-password', 'reset')->name('password.update');
	Route::post('/verify-user', 'verify')->name('user.verify');
	Route::get('logout', 'logout')->name('logout');
});

Route::controller(MovieController::class)
->group(function () {
	Route::post('add-movie', 'store')->middleware('jwt.auth')->name('movie.store');
	Route::post('update-movie/{movie:id}', 'update')->middleware('jwt.auth')->name('movie.update');
	Route::post('delete-movie/{movie:id}', 'destroy')->middleware('jwt.auth')->name('movie.destroy');
	Route::get('movies/show', 'show')->middleware('jwt.auth')->name('movie.show');
	Route::get('movies/{movie:id}', 'get')->middleware('jwt.auth')->name('movie.get');
});

Route::controller(QuoteController::class)
->group(function () {
	Route::post('add-quote', 'store')->middleware('jwt.auth')->name('quote.store');
	Route::post('update-quote/{quote:id}', 'update')->middleware('jwt.auth')->name('quote.update');
	Route::post('delete-quote/{quote:id}', 'destroy')->middleware('jwt.auth')->name('quote.destroy');
	Route::get('quotes/show', 'show')->middleware('jwt.auth')->name('quote.show');
	Route::get('quotes/{quote:id}', 'get')->middleware('jwt.auth')->name('quote.get');
	Route::post('search', 'search')->middleware('jwt.auth')->name('quote.search');
	Route::get('search', 'search')->middleware('jwt.auth')->name('search.show');
	Route::post('refresh', 'refresh')->middleware('jwt.auth')->name('quote.refresh');
});

Route::controller(EmailController::class)
->group(function () {
	Route::post('add-email', 'store')->middleware('jwt.auth')->name('email.store');
	Route::post('verify-email', 'verify')->middleware('jwt.auth')->name('email.verify');
	Route::post('delete-email/{email:id}', 'destroy')->middleware('jwt.auth')->name('email.destroy');
});

Route::post('update-profile', [ProfileController::class, 'update'])->middleware('jwt.auth')->name('thumbnail.update');

Route::post('add-like', [LikeController::class, 'store'])->middleware('jwt.auth')->name('like.store');

Route::post('add-comment', [CommentController::class, 'store'])->middleware('jwt.auth')->name('comment.store');
Route::get('comments/show', [CommentController::class, 'show'])->middleware('jwt.auth')->name('comment.show');

Route::post('read', [NotificationController::class, 'read'])->middleware('jwt.auth')->name('notifications.read');
Route::get('notifications/show', [NotificationController::class, 'show'])->middleware('jwt.auth')->name('notifications.show');
