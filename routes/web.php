<?php

use Illuminate\Support\Facades\Auth;
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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::resource('role', 'App\Http\Controllers\RoleController', ['except' => ['show']]);
	Route::resource('divisi', 'App\Http\Controllers\DivisiController', ['except' => ['show']]);
	Route::resource('patrol', 'App\Http\Controllers\PatrolController', ['except' => ['show']]);
	Route::resource('perbaikan', 'App\Http\Controllers\PerbaikanController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile/{id}', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password/{id}', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
});

