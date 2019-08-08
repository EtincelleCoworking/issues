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
/*
Route::get('/', function () {
    return view('welcome');
});
*/
Auth::routes(['register' => false]);

Route::get('/', 'HomeController@index')->name('home');
Route::get('/issues', 'IssueController@index')->name('issues');
Route::get('/issues/create', 'IssueController@create')->name('issue_create');
Route::get('/issue/{id}', 'IssueController@show')->name('issue_show');
