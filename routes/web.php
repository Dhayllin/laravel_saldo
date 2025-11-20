<?php

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

Route::group(['middleware' => ['auth', 'checkAuthorized'], 'namespace' => 'Admin', 'prefix'=>'admin'], function(){
    Route::get('/','AdminController@index')->name('admin.home');
    Route::get('balance','FirebaseService@index')->name('admin.balance');

    Route::get('deposit','FirebaseService@deposit')->name('balance.deposit');
    Route::post('deposit','FirebaseService@depositStore')->name('deposit.store');

    Route::get('withdraw','FirebaseService@withdraw')->name('balance.withdraw');
    Route::post('withdraw','FirebaseService@withdrawStore')->name('withdraw.store');

    Route::get('transfer','FirebaseService@transfer')->name('balance.transfer');
    Route::post('confirm-transfer','FirebaseService@confirmTransfer')->name('confirm.transfer');
    Route::post('transfer','FirebaseService@transferStore')->name('transfer.store');

    Route::get('historic','FirebaseService@historic')->name('admin.historic');
    Route::any('historic-search','FirebaseService@searchHistoric')->name('historic.search');
    Route::get('balance/where','FirebaseService@testWhere')->name('admin.where');
});

Route::get('/','Site\SiteController@index')->name('home');
Route::get('meu-perfil','Admin\UserController@profile')->name('profile')->middleware('auth');
Route::post('atualizar-perfil','Admin\UserController@profileUpdate')->name('profile.update')->middleware('auth');

Auth::routes();

