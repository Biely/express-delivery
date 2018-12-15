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
Route::get('/waitodo', 'HomeController@waitodo')->name('waitodo');
Route::get('/hasget', 'HomeController@hasget')->name('hasget');
Route::get('/isok', 'HomeController@isok')->name('isok');
Route::resource('subtask', 'SubTaskController');
Route::resource('person', 'PersonController');
Route::post('person/{person}','PersonController@update');
Route::post('subtask/{subtask}','SubTaskController@update');
Route::post('comment','CommentController@store')->name('comment.store');
Route::get('score/{taskid}','SubTaskController@score')->name('score');
Route::post('moretask/{taskid}','SubTaskController@moretask')->name('moretask');
Route::post('searchtasks','HomeController@search')->name('searchtask');
Route::get('searchtasks', function () {
    return redirect('/home');
});
Route::get('notifications', 'HomeController@notif')->name('notifications.index');