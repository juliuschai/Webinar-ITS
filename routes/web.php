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


Route::domain(Config::get('app.base_subdomain').'.'.Config::get('app.base_domain'))->group(function () {
    Route::get('/', 'UserController@checkLoggingIn');
    // Route::get('test','UserController@testGet');
    
    Route::get('login','UserController@login')->name('login');
    Route::get('logout','UserController@logout')->name('logout');

    Route::get('/booking/view/{id}', 'BookingController@viewBooking')->name('booking.view');
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/home', 'HomeController@index')->name('home');

        Route::get('authorize/admin/aGVzb3lhbWhlc295YW1oZXNveWFt', 'UserController@tempAdm');

        Route::get('/booking/new', 'BookingController@viewNewBooking')->name('booking.new');
        Route::post('/booking/new', 'BookingController@saveNewBooking')->name('booking.new');
        Route::get('/booking/edit/{id}', 'BookingController@viewEditBooking')->name('booking.edit');
        Route::post('/booking/edit', 'BookingController@saveEditBooking')->name('booking.edit');

            // TODO: admin middleware
        Route::group(['middleware' => 'admin'], function () {
            Route::post('/booking/verify', 'BookingController@verifyBooking')->name('booking.verify');

            Route::get('/unit', 'UnitController@viewUnit')->name('unit.view');
            Route::post('/unit/add', 'UnitController@addUnit')->name('unit.add');
            Route::post('/unit/delete/{id}', 'UnitController@delUnit')->name('unit.delete');
            Route::get('/unit/edit/{id}', 'UnitController@viewEditUnit')->name('unit.edit');
            Route::post('/unit/edit/{id}', 'UnitController@saveEditUnit')->name('unit.edit');


        });
    });
    Route::get('/booking/waitinglist', 'BookingController@waitingListBooking')->name('booking.list');
    Route::get('/booking/list', 'BookingController@listBooking')->name('booking.list');
    Route::delete('/booking/delete/{id}', 'BookingController@deleteBooking')->name('booking.delete');
});

// Auth::routes();
/* 
    Route::get('auth/check', function () {
        $ret['test'] = session('test');
        $ret['check'] = auth()->check();
        $ret['id'] = auth()->id();
        $ret['user'] = auth()->user();
        $ret['isset'] = (null !== session('id_token'));
        $ret['id_token'] = session('id_token');
        var_dump($ret);
    });
 */