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
    return view('index');
});

Route::post('sign-up', 'UserController@create');
Route::post('sign-in', 'UserController@sign_in');
Route::post('Add_course', 'HomeController@insert_information');
Route::post('edit_profile', 'HomeController@edit_profile');
Route::post('recommend', 'HomeController@recommend');
Route::post('search', 'HomeController@query_course');
Route::post('insert', 'HomeController@insert_course');
Route::post('welcome', 'HomeController@remove_course');
Route::post('query', 'HomeController@query');


Route::get('test', 'HomeController@recommend');
Route::get('sign-up', 'UserController@render_sign_up');
Route::get('edit_profile','HomeController@render_edit_profile');
Route::get('sign-out','UserController@sign_out');
Route::get('Add_course', 'HomeController@render_add_course');
Route::get('welcome', 'HomeController@render_welcome');

Route::get('info', function () {
    return view('info');
});
