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


Route::domain('webinar.'.Config::get('app.base_domain'))->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
    
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/booking/new', 'BookingController@viewNewBooking')->name('booking.view.new');
    Route::post('/booking/new', 'BookingController@newBooking')->name('booking.new');

    Auth::routes();
});
