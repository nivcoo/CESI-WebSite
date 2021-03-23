<?php

use App\Http\Controllers\Panel\PanelController;
use App\Http\Controllers\Panel\SocietyController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\UserController;
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
    Route::post('/login/process', [AuthController::class, 'login'])->name('login_ajax');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
});
Route::group(['prefix' => 'panel'], function () {
    Route::get('/', [PanelController::class, 'index'])->name('panel');
    Route::match(["GET", "POST"], '/permissions', [PanelController::class, 'panel_permissions'])->name('panel_permissions');
    Route::match(["GET", "POST"], '/users/{type}', [UserController::class, 'panel_users'])->name('panel_users');
    Route::group(['prefix' => 'users'], function () {
        Route::match(["GET", "POST"], '/{type}/add', [UserController::class, 'panel_users_add'])->name('panel_users_add');
        Route::match(["GET", "POST"], '/{type}/edit/{id}', [UserController::class, 'panel_users_edit'])->name('panel_users_edit');
        Route::get('/{type}/delete/{id}', [UserController::class, 'panel_users_delete'])->name('panel_users_delete');
    });
    Route::group(['prefix' => 'societies'], function () {
        Route::match(["GET", "POST"], '/', [SocietyController::class, 'panel_societies'])->name('panel_societies');
        Route::match(["GET", "POST"], '/add', [SocietyController::class, 'panel_societies_add'])->name('panel_societies_add');
        Route::match(["GET", "POST"], '/edit/{id}', [SocietyController::class, 'panel_societies_edit'])->name('panel_societies_edit');
        Route::get('/delete/{id}', [SocietyController::class, 'panel_societies_delete'])->name('panel_societies_delete');
    });
});


