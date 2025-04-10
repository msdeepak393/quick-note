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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/add-note', 'NoteController@store')->name('addNote');
Route::get('/all-data', 'NoteController@show')->name('all-data');
Route::post('/update-data', 'NoteController@update')->name('update-data');
Route::post('/delete-data', 'NoteController@destroy')->name('delete-data');