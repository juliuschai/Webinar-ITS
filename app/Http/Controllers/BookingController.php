<?php

namespace App\Http\Controllers;

use App\Civitas;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    function viewNewBooking(Request $request) {
        $civitas = Civitas::getCivitasList();
        return view('booking.new', compact('civitas'));
    }

    function newBooking(Request $request) {
        $this->validate($request, [
            'body' => 'required|max:500'
        ]);

        if ( ! $request->has('nameOfCheckBoxHere')) {
            // Do something when checkbox isn't checked.
        }
        
    }
}
