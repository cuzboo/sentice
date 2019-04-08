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

Route::get('/', 'CustomerController@index')->name('index');
Route::get('/report/{date?}', 'CustomerController@report')->name('index');
Route::get('/edit-random-user', 'CustomerController@editUser')->name('editUser');
Route::get('/add/{id}/{amount}', 'CustomerController@addMoney')->name('addMoney');
Route::get('/withdraw/{id}/{amount}', 'CustomerController@withdrawMoney')->name('withdrawMoney');

