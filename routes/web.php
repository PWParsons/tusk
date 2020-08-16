<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
