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
})->name('root');

Route::get('getReadme', function () {
    return file_get_contents(base_path().'/README.md');
})->name('getReadme');

/**
 * Home前端用户界面相关的路由
 */
Route::group(['middleware' => 'auth','namespace' => 'Home'], function () {
    Route::any('/home', 'HomeController@index')->name('home');
});

/**
 * Home前端用户界面相关的路由
 */
Route::group(['middleware' => 'CheckAuth','namespace' => 'Admin'], function () {



    Route::any('/admin', 'FeedbackController@index');
});


Auth::routes();

/**
 * 笔记相关的路由
 */
Route::group(['middleware' => 'auth','namespace' => 'Notes'], function () {

    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('/myshare', 'DashboardController@share')->name('myshare');

    // 文件夹相关的路由
    Route::post('/folder/add', 'FolderController@store');
    Route::post('/folder/del', 'FolderController@del');
    Route::post('/folder/update', 'FolderController@update');
    Route::get('/folder/list', 'FolderController@listAll');

    // 笔记相关路由
    Route::any('/note/index', 'NotesController@index');
    Route::post('/note/add', 'NotesController@add');
    Route::post('/note/del', 'NotesController@del');
    Route::post('/note/update', 'NotesController@update');
    Route::any('/note/find', 'NotesController@find');
    Route::any('/note/show', 'NotesController@show');
    Route::any('/note/latest', 'NotesController@latest');
    Route::any('/note/search', 'NotesController@search');

    // 用户操作相关路由
    Route::get('modify', 'UserController@getModify')->name('modify');
    Route::post('modify', 'UserController@postModify');

    Route::any('/forget', 'UserController@checkEmail')->name('forget');
    Route::post('/doForget', 'UserController@handleEmail')->name('doForget');

    Route::any('/reset', 'UserController@getForget')->name('reset');
    Route::post('/doReset', 'UserController@getForget')->name('doReset');

});


/**
 * 资源请求或上传路由
 * 需要验证域名只有指定的域名才可以使用
 */
Route::group(['middleware' => 'CheckHost'/*,'domain' => '*.omwteam.com'*/], function () {

    // 公共路由
    Route::any('/common/mdEditorUpload', 'CommonController@mdEditorUpload');
    Route::any('/common/wangEditorUpload', 'CommonController@wangEditorUpload');
    Route::any('/common/upload', 'CommonController@upload');


});


Route::any('/common/prompt', 'CommonController@prompt')->name('prompt');
Route::any('checkLogin', 'Notes\UserController@checkLogin')->name('checkLogin');
Route::any('/test', 'TestController@test');
Route::any('/feedback', 'Admin\FeedbackController@create')->name('feedback');
Route::any('/feedback/store', 'Admin\FeedbackController@store')->name('feedback.store');




