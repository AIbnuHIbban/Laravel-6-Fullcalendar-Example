<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//fullcalender
Route::get('fullcalendar','FullCalendarController@index');
Route::post('fullcalendar/create','FullCalendarController@create');
Route::post('fullcalendar/update','FullCalendarController@update');
Route::post('fullcalendar/delete','FullCalendarController@destroy');