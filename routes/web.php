<?php

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

// Authentication Routes
Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth'], function () {
    // Home Route
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // Users Routes
    Route::resource('user', App\Http\Controllers\UserController::class, ['except' => ['show']]);

    // Settings Routes
    Route::get('settings', [App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');

    Route::put('ldap-settings', [App\Http\Controllers\SettingController::class, 'updateLdapSettings'])->name('ldap.settings.update');
});

Route::group(['middleware' => 'auth'], function () {
	Route::get('{page}', ['as' => 'page.index', 'uses' => 'App\Http\Controllers\PageController@index']);
});

