<?php

use Illuminate\Support\Facades\Route;


Auth::routes();

Route::middleware('auth')->group(function(){
	Route::get('/', 'BonusController@index')->name('bonus.index');
	Route::post('/', 'BonusController@datatables')->name('bonus.datatables');
	Route::get('/create', 'BonusController@create')->name('bonus.create');
	Route::post('/create', 'BonusController@store');
	Route::get('/{id}/get', 'BonusController@get')->name('bonus.get');
	Route::get('/{id}/edit', 'BonusController@edit')->name('bonus.edit');
	Route::post('/{id}/edit', 'BonusController@update');
	Route::delete('/{id}/delete', 'BonusController@delete')->name('bonus.delete');
});


Route::get('/home', 'HomeController@index')->name('home');
