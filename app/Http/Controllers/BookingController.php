<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Http\Requests\SaveBookingRequest;
use App\Http\Requests\VerifyBookingRequest;
use App\Organisasi;
use App\OrgType;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    function viewNewBooking(Request $request) {
        $booking = new Booking();
        $booking->setUserFields(Auth::id());
        $organisasis = Organisasi::get();

        return view('booking.form', compact(['booking', 'organisasis']));
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
        $booking->org_type_id = Organisasi::getTypeIdById($booking->org_id);
        $organisasis = Organisasi::get();
        $orgTypes = OrgType::get();

        return view('booking.form', compact(['booking', 'organisasis', 'orgTypes']));
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
            'booking.view', 
            compact(['booking', 'isOwner', 'isAdmin'])
        );
    }

    function verifyBooking(VerifyBookingRequest $request) {
        User::find(Auth::id())->abortButAdmin();
        $booking = Booking::findOrFail($request['id']);
        $booking->verifyRequest($request);

        return redirect()->route('booking.view', ['id'=>$request['id']]);
    }

    public function waitingListBooking() {
        Auth::id();
        $booking = Booking::select('id','waktu_mulai', 'nama_acara')
                    ->where('disetujui', '=', NULL)
                    ->get();
        return view('booking.table', compact(['civitas', 'booking', 'id']));
    }

    public function listBooking(Request $request) {
        Auth::id();
        $booking = Booking::select('waktu_mulai', 'nama_acara')
                            ->where('disetujui', '!=', NULL)
                            ->get();

        return view('booking.list', compact(['civitas', 'booking', 'id']));
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
                            ->where('disetujui', '!=', NULL)
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
