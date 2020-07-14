<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Civitas;
use App\Http\Requests\SaveBooking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    function viewNewBooking(Request $request) {
        $civitas = Civitas::getCivitasList();
<<<<<<< HEAD
        // return view('layouts.user', compact('civitas'));
        return view('booking.user', compact('civitas'));
=======
        $booking = new Booking();

        return view('booking.form', compact(['civitas', 'booking']));
    }

    function saveNewBooking(SaveBooking $request) {
        // Validation via SaveBooking form requests

        $booking = new Booking();
        $booking->saveFromRequest($request);

        return redirect()->route('booking.view', ['id'=>$booking['id']]);
>>>>>>> f15245befc653f83220798701de78b393caa7c11
    }

    function viewEditBooking(Request $request) {
        $id = $request['id'];
        $booking = Booking::findOrFail($id);
        $booking->civitas = Civitas::getNamaFromId($booking->civitas_id);
        // TODO:$booking->abortButOwner(auth()->user()->id);

        $civitas = Civitas::getCivitasList();
        return view('booking.form', compact(['civitas', 'booking', 'id']));
    }

    function saveEditBooking(SaveBooking $request) {
        // Validation via SaveBooking form requests

        $booking = Booking::findOrFail($request['id']);
        // TODO:$booking->abortButOwner(auth()->user()->id);
        $booking->saveFromRequest($request);

        return redirect()->route('booking.edit', ['id'=>$request['id']]);
    }

    function viewBooking(Request $request) {
        $booking = Booking::findOrFail($request['id']);
        $isAdmin = true;
        $isOwner = true;
        // TODO:$isOwner = $booking->isBookingOwner(auth()->user()->id);

        return view(
            'booking.view', 
            compact(['booking', 'isOwner', 'isAdmin'])
        );
    }


}
