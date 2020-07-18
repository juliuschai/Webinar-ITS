<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Http\Requests\SaveBookingRequest;
use App\Http\Requests\VerifyBookingRequest;
use App\Unit;
use App\UnitType;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    function viewNewBooking(Request $request) {
        $booking = new Booking();
        $booking->setUserFields(Auth::id());
        $units = Unit::get();
        $unitTypes = UnitType::get();

        return view('booking.form', compact(['booking', 'units', 'unitTypes']));
    }

    function saveNewBooking(SaveBookingRequest $request) {
        $booking = new Booking();
        $booking->setUserId(Auth::id());
        $booking->saveFromRequest($request);

        return redirect()->route('booking.view', ['id'=>$booking['id']]);
    }

    function viewEditBooking($id) {
        $booking = Booking::findOrFail($id);
        $booking->abortIfApproved();
        $booking->abortButOwner(Auth::id());
        $booking->setUserFields(Auth::id());
        $booking->unit_type_id = Unit::getTypeIdById($booking->unit_id);
        $units = Unit::get();
        $unitTypes = UnitType::get();

        return view('booking.form', compact(['booking', 'units', 'unitTypes']));
    }

    function saveEditBooking(SaveBookingRequest $request) {
        $booking = Booking::findOrFail($request['id']);
        $booking->abortIfApproved();
        $booking->abortButOwner(Auth::id());
        $booking->saveFromRequest($request);

        return redirect()->route('booking.view', ['id'=>$request['id']]);
    }

    function viewBooking($id) {
        $booking = Booking::findOrFail($id);
        if (Auth::check()) {
            $isAdmin = User::findOrLogout(Auth::id())->isAdmin();
            $isOwner = $booking->isOwner(Auth::id());
            if ($isAdmin || $isOwner) {
                $booking->setOrgFields($booking['unit_id']);
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

    function verifyBooking(VerifyBookingRequest $request) {
        User::findOrLogout(Auth::id())->abortButAdmin();
        $booking = Booking::findOrFail($request['id']);
        $booking->verifyRequest($request);

        return redirect()->route('booking.view', ['id'=>$request['id']]);
    }

    public function waitingListBooking() {
        $booking = Booking::all();
        return view('booking.table', compact(['civitas', 'booking', 'id']));
    }

    public function listBooking(Request $request) {
        $booking = Booking::select('waktu_mulai', 'nama_acara')
                            ->where('disetujui', '!=', NULL)
                            ->get();

        return view('booking.list', compact(['civitas', 'booking', 'id']));
    }

    public function detailBooking(Request $request) 
    { 
        // $id = $request['id'];
        // $booking = Booking::findOrFail($id);
        // $booking['civitas'] = Civitas::getNamaFromId($booking['civitas_id']);

        // $civitas = Civitas::getCivitasList();
        $booking = Booking::all();
        return view('booking.detail', compact(['civitas', 'booking', 'id']));
        
    }

    public function deleteBooking(Request $request) {

        $id = $request['id'];
        Booking::destroy($id);

        return redirect('/booking/list')->with('status', 'Data Webinar Berhasil Dihapus!');
    }
}
