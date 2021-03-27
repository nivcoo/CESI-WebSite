<?php

use App\Http\Controllers\Panel\OfferController;
use App\Http\Controllers\Panel\PanelController;
use App\Http\Controllers\Panel\SocietyController;
use App\Http\Controllers\Panel\ApplicationController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\PersonalController;
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

    Route::group(['prefix' => 'offers'], function () {
        Route::match(["GET", "POST"], '/', [OfferController::class, 'panel_offers'])->name('panel_offers');
        Route::match(["GET", "POST"], '/add', [OfferController::class, 'panel_offers_add'])->name('panel_offers_add');
        Route::match(["GET", "POST"], '/edit/{id}', [OfferController::class, 'panel_offers_edit'])->name('panel_offers_edit');
        Route::get('/delete/{id}', [OfferController::class, 'panel_offers_delete'])->name('panel_offers_delete');
    });

    Route::group(['prefix' => 'personal'], function () {
        Route::group(['prefix' => 'wishlist'], function () {
            Route::match(["GET", "POST"], '/', [PersonalController::class, 'panel_personal_wishlist'])->name('panel_personal_wishlist');
            Route::get('/add/{id}', [PersonalController::class, 'panel_personal_wishlist_add'])->name('panel_personal_wishlist_add');
            Route::get('/delete/{user_id}/{internship_offer_id}', [PersonalController::class, 'panel_personal_wishlist_delete'])->name('panel_personal_wishlist_delete');
        });

        Route::group(['prefix' => 'notification'], function () {
            Route::match(["GET", "POST"], '/', [PersonalController::class, 'panel_personal_notifications'])->name('panel_personal_notifications');
            Route::get('/see/{id}', [PersonalController::class, 'panel_personal_notifications_see'])->name('panel_personal_notifications_see');
        });
    });


    Route::group(['prefix' => 'applications'], function () {
        Route::match(["GET", "POST"], '/', [ApplicationController::class, 'panel_applications'])->name('panel_applications');
        Route::match(["GET", "POST"], '/candidate/{id}', [ApplicationController::class, 'panel_applications_participate'])->name('panel_applications_participate');
        Route::get('/show/{id}', [ApplicationController::class, 'panel_applications_show'])->name('panel_applications_show');

        Route::get('/delete/{id}', [ApplicationController::class, 'panel_applications_delete'])->name('panel_applications_delete');

        Route::post('/change/step/', [ApplicationController::class, 'panel_applications_change_step'])->name('panel_applications_change_step');

    });
});


