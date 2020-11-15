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

    Route::get('/booking/{tipe_zoom}/view/{id}', 'BookingController@viewBooking')->name('booking.view');
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/home', 'HomeController@index')->name('home');

        Route::get('/booking/{tipe_zoom}/new', 'BookingController@viewNewBooking')->name('booking.new');
        Route::post('/booking/{tipe_zoom}/new', 'BookingController@saveNewBooking')->name('booking.new');

        Route::get('/booking/{tipe_zoom}/list/data', 'BookingController@listBookingData')->name('list.data');
        Route::get('/booking/{tipe_zoom}/waitinglist', 'BookingController@waitingListBooking')->name('booking.list');

        Route::group(['middleware' => 'owneroradmin'], function () {
            Route::get('storage/dokumen/get/{id}', 'FileController@getDokumen')->name('dokumen.get');
            Route::get('storage/dokumen/download/{id}', 'FileController@downloadDokumen')->name('dokumen.download');

            Route::get('/booking/{tipe_zoom}/edit/{id}', 'BookingController@viewEditBooking')->name('booking.edit');
            Route::post('/booking/{tipe_zoom}/edit/{id}', 'BookingController@saveEditBooking')->name('booking.edit');
            Route::post('/booking/{tipe_zoom}/delete/{id}', 'BookingController@deleteBooking')->name('booking.delete');
        });

        Route::group(['middleware' => 'admin'], function () {
            // Admin
            Route::get('/admin/booking/{tipe_zoom}/list', 'BookingController@adminListBooking')->name('admin.list');
            Route::get('/admin/booking/{tipe_zoom}/list/data', 'BookingController@adminListBookingData')->name('admin.list.data');
            Route::get('/admin/booking/{tipe_zoom}/aprove', 'BookingController@aproveBooking')->name('admin.aprove');
            Route::get('/admin/booking/{tipe_zoom}/list/aprove', 'BookingController@adminAproveBookingData')->name('admin.list.aprove');
            Route::post('/admin/booking/{tipe_zoom}/delete/{id}', 'BookingController@adminDeleteBooking')->name('admin.delete');
            Route::post('/admin/booking/{tipe_zoom}/verify/{id}', 'BookingController@verifyBooking')->name('booking.verify');

            Route::get('/unit', 'UnitController@viewUnit')->name('admin.unit.view');
            Route::get('/unit/data', 'UnitController@viewUnitData')->name('admin.unit.view.data');
            Route::post('/unit/add', 'UnitController@addUnit')->name('admin.unit.add');
            Route::post('/unit/delete/{id}', 'UnitController@delUnit')->name('admin.unit.delete');
            Route::get('/unit/edit/{id}', 'UnitController@viewEditUnit')->name('admin.unit.edit');
            Route::post('/unit/edit/{id}', 'UnitController@saveEditUnit')->name('admin.unit.edit');

            Route::get('/users', 'UserController@viewUsers')->name('admin.users.view');
            Route::get('/users/data', 'UserController@viewUsersData')->name('admin.users.view.data');
            Route::post('/users/admin/give/{id}', 'UserController@giveAdmin')->name('admin.users.admin.give');
            Route::post('/users/admin/revoke/{id}', 'UserController@revokeAdmin')->name('admin.users.admin.revoke');
            Route::post('/users/verifier/give/{id}', 'UserController@giveVerifier')->name('admin.users.verifier.give');
            Route::post('/users/verifier/revoke/{id}', 'UserController@revokeVerifier')->name('admin.users.verifier.revoke');

            Route::get('/admin/webinar/accounts', 'HostAccountController@getAccounts')->name('admin.host.accounts');
            Route::get('/admin/webinar/accounts/data', 'HostAccountController@getData')->name('admin.host.data');

            Route::get('/admin/export', 'ExportController@view')->name('export.form');
            Route::post('/admin/export/booking', 'ExportController@downloadBooking')->name('export.booking');
        });
    });
});
