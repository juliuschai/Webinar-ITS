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
}
