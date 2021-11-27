<?php

use App\Http\Controllers\ActionController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\ScooterController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckAuth;
use App\Http\Middleware\CheckNotAuth;
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

Route::middleware([CheckNotAuth::class])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('index');
    Route::get('/reg', [UserController::class, 'signUp'])->name('reg');
    Route::post('/auth', [UserController::class, 'authenticate'])->name('auth');
});

Route::middleware([CheckAuth::class])->group(function () {

    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [AppController::class, 'dashboard'])->name('dashboard');

    Route::resource('/scooters', ScooterController::class)->except(['show'])->middleware(CheckAdmin::class);
    Route::resource('/points', PointController::class)->except(['show'])->middleware(CheckAdmin::class);
    Route::resource('/users', UserController::class)->except(['show']);
    Route::resource('/orders', OrderController::class)->except(['create', 'index', 'show']);

// Ajax actions

    Route::post('/get_scooters', [ActionController::class, 'getScooters']);

});
