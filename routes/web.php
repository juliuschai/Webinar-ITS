<?php

use App\Http\Controllers\UserController;
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
/*     Route::get('temp', function() {
        $booking = \App\Booking::find(1);
        $booking->file_pendukung = 'jpg';
        $booking->save();
    }); */
    // Route::get('/tes', 'BookingController@tes')->name('booking.list');
    Route::get('calendar/event', 'BookingController@getEvents')->name('calendar.event');
    
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

        
        Route::get('/booking/waitinglist', 'BookingController@waitingListBooking')->name('booking.list');
        Route::delete('/booking/delete/{id}', 'BookingController@deleteBooking')->name('booking.delete');
        
        Route::group(['middleware' => 'owneroradmin'], function () {
            Route::get('storage/dokumen/get/{bookingId}', 'FileController@getDokumen')->name('dokumen.get');
            Route::get('storage/dokumen/download/{bookingId}', 'FileController@downloadDokumen')->name('dokumen.download');
            Route::get('storage/dokumen/delete/{bookingId}', 'FileController@deleteDokumen')->name('dokumen.delete');
        });
        // TODO: admin middleware
        Route::group(['middleware' => 'admin'], function () {
            Route::post('/booking/verify', 'BookingController@verifyBooking')->name('booking.verify');

            Route::get('/unit', 'UnitController@viewUnit')->name('admin.unit.view');
            Route::post('/unit/add', 'UnitController@addUnit')->name('admin.unit.add');
            Route::post('/unit/delete/{id}', 'UnitController@delUnit')->name('admin.unit.delete');
            Route::get('/unit/edit/{id}', 'UnitController@viewEditUnit')->name('admin.unit.edit');
            Route::post('/unit/edit/{id}', 'UnitController@saveEditUnit')->name('admin.unit.edit');

            Route::get('/users', 'UserController@viewUsers')->name('admin.users.view');
            Route::post('/users/give/{id}', 'UserController@giveAdmin')->name('admin.users.give');
            Route::post('/users/revoke/{id}', 'UserController@revokeAdmin')->name('admin.users.revoke');

            // Admin
            Route::get('/admin/booking/list', 'BookingController@adminListBooking')->name('admin.list');
            Route::get('/admin/booking/aprove', 'BookingController@aproveBooking')->name('admin.aprove');
            Route::get('/admin/booking/view/{id}', 'BookingController@adminViewBooking')->name('admin.view');
        });
    });
});