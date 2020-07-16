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
    Route::get('/', 'UserController@checkLoggingIn');
    Route::get('test','UserController@testGet');
    Route::get('auth/login','UserController@login')->name('OIDCLogin');
    Route::get('auth/logout','UserController@logout')->name('OIDCLogout');
    
    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/booking/view', 'BookingController@viewBooking')->name('booking.view');
    Route::group(['middleware' => 'auth'], function () {
    });
    Route::get('auth/check', function () {
        $ret['test'] = session('test');
        $ret['check'] = auth()->check();
        $ret['id'] = auth()->id();
        $ret['user'] = auth()->user();
        $ret['isset'] = (null !== session('id_token'));
        $ret['id_token'] = session('id_token');
        var_dump($ret);
    });
    Route::get('/booking/new', 'BookingController@viewNewBooking')->name('booking.new');
    Route::post('/booking/new', 'BookingController@saveNewBooking')->name('booking.new');
    Route::get('/booking/edit/{id}', 'BookingController@viewEditBooking')->name('booking.edit');
    Route::post('/booking/edit', 'BookingController@saveEditBooking')->name('booking.edit');

    Route::post('/booking/verify', 'BookingController@verifyBooking')->name('booking.verify');
    Route::group(['middleware' => 'auth'], function () {
    
    });
    Route::get('/booking/waitinglist', 'BookingController@waitingListBooking')->name('booking.list');
    Route::get('/booking/list', 'BookingController@listBooking')->name('booking.list');
    Route::get('/booking/detail/{id}', 'BookingController@detailBooking')->name('booking.detail');
    Route::delete('/booking/delete/{id}', 'BookingController@deleteBooking')->name('booking.delete');

    Auth::routes();
});
