<?php

use Illuminate\Support\Facades\Route;


Route::get('/','HomeController@HomeIndexAdmin')->middleware('logincheck');
Route::get('/getUserData','HomeController@getUserData')->middleware('logincheck');
Route::post('/userAdd','HomeController@userAdd')->middleware('logincheck');
Route::post('/send-mail','HomeController@sendMail')->middleware('logincheck');


Route::get('/getIdeaData','idea_controller@getIdeaData')->middleware('logincheck');
Route::get('/idea','idea_controller@idea')->middleware('logincheck');
Route::get('/ideaCount','idea_controller@ideaCount')->middleware('logincheck');
Route::get('/get_end_time/{lm}/{tour_id}','idea_controller@get_end_time')->middleware('logincheck');
Route::post('/ideaAdd','idea_controller@ideaAdd')->middleware('logincheck');


Route::get('/LoginIndex', 'LoginController@LoginIndex');
Route::get('/onLogout', 'LoginController@onLogout');
Route::post('/onLogin', 'LoginController@onLogin');


