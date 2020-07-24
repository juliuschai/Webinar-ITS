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
use DateTime;
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

        return view('booking.table', compact(['bookings']));
    }

    public function deleteBooking(Request $request) {
        $id = $request['id'];
        Booking::destroy($id);

        return redirect()->route('booking.list');
    }

    public function adminDeleteBooking(Request $request) {
        $id = $request['id'];
        Booking::destroy($id);

        return redirect()->route('admin.list');
    }

    //Admin

    public function adminListBooking(Request $request) {

        $bookings = \DB::table('bookings')
        ->join('units', 'units.id', '=', 'bookings.unit_id')
        ->select('bookings.*', 'units.nama')
        ->paginate('10');

        return view('admin.table', compact(['bookings']));
    }

    public function aproveBooking(Request $request) {
        $bookings = \DB::table('bookings')
        ->join('units', 'units.id', '=', 'bookings.unit_id')
        ->where('bookings.disetujui', '=', '1')
        ->select('bookings.*', 'units.nama')
        ->paginate('10');

        $isAdmin = true;

        return view('admin.aprove', compact(['bookings', 'isAdmin']));
    }

    function getEvents() {
        $extra = new Booking();
        $extra->id = 0;
        $extra->title = 'start';
        $extra->start = (new DateTime)->setTimestamp(1);
        $extra->end = (new DateTime)->setTimestamp(2);
        $extra2 = new Booking();
        $extra2->id = -1;
        $extra2->title = 'end';
        $extra2->start = (new DateTime)->setTimestamp(2000000000);
        $extra2->end = (new DateTime)->setTimestamp(2000000001);
        $extras = collect([$extra, $extra2]);
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
        $end = $bookings->merge($extras);
        return json_encode($end);
    }
}
