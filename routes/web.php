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

Route::any('/home', 'HomeController@index')->name('home');
Route::any('/mdEditorUpload', 'CommonController@mdEditorUpload');
Route::any('/wangEditorUpload', 'CommonController@wangEditorUpload');

Route::any('/test/export', 'CommonController@export')->name('export');

Route::any('/test/index', 'CommonController@index')->name('index');

Route::group(['middleware' => 'auth'], function () {

    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    Route::any('/folder/add', 'FolderController@store');
    Route::any('/folder/del', 'FolderController@del');
    Route::any('/folder/update', 'FolderController@update');
    Route::get('/folder/list', 'FolderController@listAll');

    Route::any('/note/index', 'NotesController@index');
    Route::any('/note/add', 'NotesController@add');
    Route::any('/note/del', 'NotesController@del');
    Route::any('/note/update', 'NotesController@update');
    Route::any('/note/find', 'NotesController@find');
    Route::any('/note/show', 'NotesController@show');
    Route::any('/note/latest', 'NotesController@latest');
});