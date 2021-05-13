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

// node.js filesystem API endpoints
Route::group(['middleware' => ['comesFromLocalhost']], function () {
    Route::prefix('fs')->group(function () {
        Route::post('added', [App\Http\Controllers\CmsRecordingController::class, 'store']);
    });
});

// Authentication Routes
Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::get('recordings/shared/{cmsRecording}', [App\Http\Controllers\CmsRecordingController::class, 'shared'])
    ->name('recordings.shared');

Route::group(['middleware' => ['auth', 'hasCoSpacesOrIsAdmin']], function () {
    // Home Route
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // CMS Routes
    Route::resource('cms', App\Http\Controllers\CmsController::class, ['except' => ['show']])->parameters(['cms' => 'cms']);;

    // CMS CoSpace Routes
    Route::get('cospaces', [App\Http\Controllers\CmsCoSpaceController::class, 'index'])->name('cospaces.index');

    // Users Routes
    Route::resource('user', App\Http\Controllers\UserController::class, ['except' => ['show']]);

    // Recordings Routes
    Route::get('recordings/play/{cmsRecording}/{timeStamp}', [App\Http\Controllers\CmsRecordingController::class, 'play'])
        ->name('recordings.play')
        ->middleware('canAccessRecording');
    Route::get('recordings/download', [App\Http\Controllers\CmsRecordingController::class, 'download'])
        ->name('recordings.download')
        ->middleware('canAccessRecording');
    Route::get('recordings', [App\Http\Controllers\CmsRecordingController::class, 'index'])->name('recordings.index');

    // Settings Routes
    Route::get('settings', [App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::put('ldap-settings', [App\Http\Controllers\SettingController::class, 'updateLdapSettings'])->name('ldap.settings.update');
    Route::put('nfs-settings', [App\Http\Controllers\SettingController::class, 'updateNfsSettings'])->name('nfs.settings.update');
});

