<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Http\Requests\AcceptBookingRequest;
use App\Http\Requests\DenyBookingRequest;
use App\Http\Requests\NewBookingRequest;
use App\Http\Requests\EditBookingRequest;
use App\Unit;
use App\UnitType;
use App\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
	function viewNewBooking(Request $request) {
		$booking = new Booking();
		$booking->setUserFields(Auth::id());
		$units = Unit::getDefault();
		$unitTypes = UnitType::get();

		return view('booking.form', compact(['booking', 'units', 'unitTypes']));
	}

	function saveNewBooking(NewBookingRequest $request) {
		$booking = new Booking();
		$booking->setUserId(Auth::id());
		$booking->saveFromRequest($request);

		return redirect()->route('booking.view', ['id'=>$booking['id']]);
	}

	function viewEditBooking($id) {
		$booking = Booking::findOrFail($id);
		$booking->abortIfVerified();
		$booking->setUserFields($booking->user_id);
		$booking->unit_type_id = Unit::getTypeIdById($booking->unit_id);
		$units = Unit::getDefault();
		$unitTypes = UnitType::get();

		return view('booking.form', compact(['booking', 'units', 'unitTypes']));
	}

	function saveEditBooking(EditBookingRequest $request) {
		$booking = Booking::findOrFail($request['id']);
		$booking->abortIfVerified();
		$booking->saveFromRequest($request);

		return redirect()->route('booking.view', ['id'=>$request['id']]);
	}

	function viewBooking($id) {
		$booking = Booking::findOrFail($id);
		$booking->setOrgFields($booking['unit_id']);
		if (Auth::check()) {
			$isAdmin = User::findOrLogout(Auth::id())->isAdmin();
			$isOwner = $booking->isOwner(Auth::id());
			if ($isAdmin || $isOwner) {
				$booking->setUserFields($booking['user_id']);    
			}
		} else {
			$isAdmin = false;
			$isOwner = false;
		}
		return view(
			'booking.view', 
			compact(['booking', 'isOwner', 'isAdmin'])
		);
	}

	
	function acceptBooking(AcceptBookingRequest $request) {
		$booking = Booking::findOrFail($request['id']);
		$booking->acceptBooking($request);

		return redirect()->route('booking.view', ['id'=>$request['id']]);
	}

	function cancelBooking(Request $request) {
		$booking = Booking::findOrFail($request['id']);
		$booking->cancelBooking($request);

		return redirect()->route('booking.view', ['id'=>$request['id']]);
	}

	function denyBooking(DenyBookingRequest $request) {
		$booking = Booking::findOrFail($request['id']);
		$booking->denyBooking($request);

		return redirect()->route('booking.view', ['id'=>$request['id']]);
	}

	public function waitingListBooking() {

		$bookings = \DB::table('bookings')
					->join('units', 'units.id', '=', 'bookings.unit_id')
					->where('bookings.user_id', '=', Auth::id())
					->select('bookings.*', 'units.nama')
					->paginate('10');
		$length = Booking::where('bookings.user_id', '=', Auth::id())
			->count();
		return view('booking.table', compact(['bookings', 'length']));
	}

	public function deleteBooking(Request $request) {
		$id = $request['id'];
		Booking::destroy($id);

		// if(Route::is('booking.list')){
			return redirect()->route('booking.list');
		// } else if(Route::is('admin.list')){
			// return redirect()->route('admin.list');
		// }
	}

	public function adminDeleteBooking(Request $request) {
		$id = $request['id'];
		Booking::destroy($id);

		return redirect()->route('admin.list');
	}

	function adminAproveBookingData() {
		$model = Booking::viewBookingList()
			->where('bookings.disetujui', '=', '1')
			->newQuery();

		return DataTables::eloquent($model)
			->toJson();
	}

	function listBookingData() {
		$model = Booking::viewBookingList()
			->where('bookings.user_id', '=', Auth::id())
			->newQuery();

		return DataTables::eloquent($model)
			->filterColumn('disetujui', function($query, $keyword) {
				if ($keyword == "true") {
					$query->whereRaw("disetujui = true");
				} else if ($keyword == "false"){
					$query->whereRaw("disetujui = false");
				} else if ($keyword == "none"){
					$query->whereRaw("disetujui IS NULL");
				}	
			})
			->toJson();
	}

	//Admin
	function adminListBookingData() {
		$model = Booking::viewBookingList()
			->newQuery();

		return DataTables::eloquent($model)
			->filterColumn('disetujui', function($query, $keyword) {
				if ($keyword == "true") {
					$query->whereRaw("disetujui = true");
				} else if ($keyword == "false"){
					$query->whereRaw("disetujui = false");
				} else if ($keyword == "none"){
					$query->whereRaw("disetujui IS NULL");
				}	
			})
			->toJson();
	}

	public function adminListBooking(Request $request) {
		$bookings = Booking::viewBookingList()
					->paginate('10');

		$length = Booking::count();
		return view('admin.table', compact(['bookings', 'length']));
	}

	public function aproveBooking(Request $request) {
		$bookings = Booking::viewBookingList()
					->where('bookings.disetujui', '=', '1')
					->paginate('10');

			$length = Booking::where('bookings.disetujui', '=', '1')
				->count();
		return view('admin.aprove', compact(['bookings', 'length']));
	}

	function getEvents() {
		$bookings = Booking::where('disetujui', true)
			->get([
				'id', 
				'nama_acara as title',
				'waktu_mulai as start', 
				'waktu_akhir as end'
			]);
		foreach ($bookings as $booking) {
			$booking['color'] = "#fae9e8";
		}

		return json_encode($bookings);
	}
}
