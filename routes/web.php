<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialiteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])
    ->name('socialite.redirect');
Route::get('/callback/{provider}', [SocialiteController::class, 'callback'])
    ->name('socialite.callback');

Route::get('/', function () {
        return redirect('/home');
    });