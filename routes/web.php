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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/users', 'UsersController@index')->name('users.index');
    Route::get('/users/{user}', 'UsersController@show')->name('users.show');
    Route::delete('/users/{user}', 'UsersController@destroy')->name('users.destroy');
    Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit');
    Route::post('/users/{user}', 'UsersController@update')->name('users.update');

    Route::post('/orders', 'OrdersController@store')->name('orders.store');
    Route::get('/orders/create', 'OrdersController@create')->name('orders.create');
});
