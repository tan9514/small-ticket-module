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
*/

// 打印机
Route::get('small_ticket/list', 'SmallTicketController@list');
Route::get('small_ticket/ajaxList', 'SmallTicketController@ajaxList');
Route::any('small_ticket/edit', 'SmallTicketController@edit');
Route::post('small_ticket/del', 'SmallTicketController@del');
Route::any('small_ticket/setting', 'SmallTicketController@setting');

