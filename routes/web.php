<?php

use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Panel\PanelController;
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


Route::get('/', [UserController::class, 'login'])->name('login');
Route::group(['prefix' => 'user'], function () {
    Route::match(['GET', 'POST'], '/login/process', [AuthController::class, 'login'])->name('login_ajax');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
});


Route::group(['prefix' => 'panel'], function () {
    Route::get('/', [PanelController::class, 'index'])->name('panel');
});
