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
        $units = Unit::getDefault();
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
        $booking->abortIfVerified();
        $booking->abortButOwner(Auth::id());
        $booking->setUserFields(Auth::id());
        $booking->unit_type_id = Unit::getTypeIdById($booking->unit_id);
        $units = Unit::getDefault();
        $unitTypes = UnitType::get();

        return view('booking.form', compact(['booking', 'units', 'unitTypes']));
    }

    function saveEditBooking(SaveBookingRequest $request) {
        $booking = Booking::findOrFail($request['id']);
        $booking->abortIfVerified();
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
        $booking = Booking::findOrFail($request['id']);
        $booking->verifyRequest($request);

        return redirect()->route('booking.view', ['id'=>$request['id']]);
    }

    public function waitingListBooking() {
        $booking = Booking::select('id','waktu_mulai', 'nama_acara')
                    ->where('user_id', '=', Auth::id())
                    ->get();

        return view('booking.table', compact(['booking']));
    }

    public function deleteBooking(Request $request) {
        $id = $request['id'];
        Booking::destroy($id);

        return redirect('/booking/list')->with('status', 'Data Webinar Berhasil Dihapus!');
    }

    //Admin

    public function adminListBooking(Request $request) {
        $booking = Booking::select('id','waktu_mulai', 'nama_acara')
                            ->where('disetujui', '=', NULL)
                            ->get();
        $isAdmin = true;

        return view('admin.table', compact(['civitas', 'booking', 'id', 'isAdmin']));
    }

    public function aproveBooking(Request $request) {
        $booking = Booking::select('waktu_mulai', 'nama_acara')
                            ->where('disetujui', '=', 1)
                            ->get();
        $isAdmin = true;

        return view('admin.aprove', compact(['civitas', 'booking', 'id', 'isAdmin']));
    }

    function adminViewBooking($id) {
        $booking = Booking::findOrFail($id);
        if (Auth::check()) {
            $isAdmin = User::find(Auth::id())->isAdmin();
            $isOwner = $booking->isOwner(Auth::id());
            if ($isAdmin || $isOwner) {
                $booking->setOrgFields($booking['org_id']);
                $booking->setUserFields($booking['user_id']);    
            }
        } else {
            $isAdmin = false;
            $isOwner = false;
        }
        return view(
            'admin.view', 
            compact(['booking', 'isOwner', 'isAdmin'])
        );
    }

}
