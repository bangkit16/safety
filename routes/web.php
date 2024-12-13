<?php

use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PatrolController;
use App\Http\Controllers\PerbaikanController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');
Route::get('/home-data', 'App\Http\Controllers\HomeController@index')->name('home.data')->middleware('auth' );

Route::group(['middleware' => 'auth'], function () {

	Route::group(['middleware' => ['role:1']], function () {
		Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
		Route::resource('role', 'App\Http\Controllers\RoleController', ['except' => ['show']]);
		Route::resource('divisi', 'App\Http\Controllers\DivisiController', ['except' => ['show']]);
		Route::put('/patrol/{id}/admin', [PerbaikanController::class, 'approveAdmin'])->name('patrol.approve.admin');
		Route::put('/perbaikan/{id}/admin', [PerbaikanController::class, 'setujuAdmin'])->name('patrol.setuju.admin');
	});

	Route::group(['middleware' => ['role:1,2']], function () {
		Route::put('/patrol/{id}/manager', [PerbaikanController::class, 'approveManager'])->name('patrol.approve.manager');
		Route::put('/perbaikan/{id}/manager', [PerbaikanController::class, 'setujuManager'])->name('patrol.setuju.manager');
		Route::get('laporan', ['as' => 'laporan.index', 'uses' => 'App\Http\Controllers\LaporanController@index']);
		Route::get('download-pdf/{id}', [LaporanController::class, 'downloadPDF'])->name('download.pdf');
		Route::get('download-excel/{id}', [LaporanController::class, 'downloadExcel'])->name('download.excel');
		Route::get('print/{id}', [LaporanController::class, 'print'])->name('print.view');
	});

	Route::group(['middleware' => ['role:1,3']], function () {
		Route::resource('patrol', 'App\Http\Controllers\PatrolController', ['except' => ['show']]);
		Route::resource('perbaikan', 'App\Http\Controllers\PerbaikanController', ['except' => ['show']]);
		Route::put('/perbaikan/form/{id}', [PerbaikanController::class, 'FormPerbaikan'])->name('perbaikan.form');
		Route::put('/perbaikan/dokumentasi/{id}', [PerbaikanController::class, 'dokumentasi'])->name('perbaikan.dokumentasi');
	});

	Route::group(['middleware' => ['role:1,2,3,4']], function () {		
		Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
		Route::put('profile/{id}', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
		Route::put('profile/password/{id}', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
	});
});

Route::get('/test-email', function () {
    Mail::raw('Test!', function ($message) {
        $message->to('burlleyjaya@gmail.com')
                ->subject('Test Email');
    });
    return 'Email sent!';
})->name("send-email");
