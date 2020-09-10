<?php

use Illuminate\Support\Facades\Route;

Route::any('login', 'UserController@login')->name('login');

Route::middleware(['auth'])->group(function() {

    Route::get('/', 'IndexController@index');
    Route::post('logout', 'UserController@logout');

    Route::post('record/batch', 'RecordController@batch');
    Route::get('record/week', 'RecordController@week');

});

