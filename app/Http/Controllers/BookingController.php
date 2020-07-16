<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Civitas;
use App\Http\Requests\SaveBookingRequest;
use App\Http\Requests\VerifyBookingRequest;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    function viewNewBooking(Request $request) {
        $civitas = Civitas::getCivitasList();
        $booking = new Booking();

        return view('booking.form', compact(['civitas', 'booking']));
    }

    function saveNewBooking(SaveBookingRequest $request) {
        $booking = new Booking();
        $booking->saveFromRequest($request);

        return redirect()->route('booking.view', ['id'=>$booking['id']]);
    }

    function viewEditBooking(Request $request) {
        $id = $request['id'];
        $booking = Booking::findOrFail($id);
        $booking['civitas'] = Civitas::getNamaFromId($booking['civitas_id']);
        // TODO:$booking->abortButOwner(auth()->user()->id);

        $civitas = Civitas::getCivitasList();
        return view('booking.form', compact(['civitas', 'booking', 'id']));
    }

    function saveEditBooking(SaveBookingRequest $request) {
        $booking = Booking::findOrFail($request['id']);
        // TODO:$booking->abortButOwner(auth()->user()->id);
        $booking->saveFromRequest($request);

        return redirect()->route('booking.view', ['id'=>$request['id']]);
    }

    function viewBooking(Request $request) {
        $id = $request['id'];
        $booking = Booking::findOrFail($id);
        $booking['civitas'] = Civitas::getNamaFromId($booking['civitas_id']);
        $isAdmin = true;
        $isOwner = true;
        // TODO:$isOwner = $booking->isBookingOwner(auth()->user()->id);

        return view(
            'booking.view', 
            compact(['id', 'booking', 'isOwner', 'isAdmin'])
        );
    }

    function verifyBooking(VerifyBookingRequest $request) {
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
