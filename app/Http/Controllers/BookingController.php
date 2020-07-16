<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Group;
use App\Http\Requests\SaveBookingRequest;
use App\Http\Requests\VerifyBookingRequest;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    function viewNewBooking(Request $request) {
        $booking = new Booking();

        return view('booking.form', compact(['booking']));
    }

    function saveNewBooking(SaveBookingRequest $request) {
        $booking = new Booking();
        $booking->saveFromRequest($request);

        return redirect()->route('booking.view', ['id'=>$booking['id']]);
    }

    function viewEditBooking(Request $request) {
        $id = $request['id'];
        $booking = Booking::findOrFail($id);
        // TODO:$booking->abortButOwner(auth()->user()->id);

        return view('booking.form', compact(['booking', 'id']));
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
        $booking['group'] = Group::getNameFromId($booking['group_id']);
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
