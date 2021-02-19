<?php

namespace App\Http\Controllers;

use App\Booking;
use App\BookingTime;
use App\Helpers\EmailHelper;
use App\Helpers\ZoomAPIHelper;
use App\Http\Requests\NewBookingRequest;
use App\Http\Requests\EditBookingRequest;
use App\Http\Requests\VerifyBookingRequest;
use App\Kategori;
use App\Unit;
use App\UnitType;
use App\User;
use Carbon\Carbon;
use Exception;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // Meeting middleware
    static function abortIfMeetingMahasiswa($tipe_zoom) {
        if ($tipe_zoom == 'meeting' && User::findOrLogout(Auth::id())->isMahasiswa()) {
            abort(403, 'meeting hanya untuk tendik/dosen');
        }
    }

    // Show new booking form
    function viewNewBooking($tipe_zoom, Request $request)
    {
        // BookingController::abortIfMeetingMahasiswa($tipe_zoom);
        $booking = new Booking();
        $booking->setUserFields(Auth::id());
        $units = Unit::getDefault();
        $unitTypes = UnitType::get();
        $booking_times = null;
        $kategoris = Kategori::get();
        $isAdmin = User::findOrLogout(Auth::id())->isAdmin();

        return view('booking.form', compact(['booking', 'units', 'unitTypes', 'booking_times', 'kategoris', 'tipe_zoom', 'isAdmin']));
    }

    // Save a new booking
    function saveNewBooking($tipe_zoom, NewBookingRequest $request)
    {
        // BookingController::abortIfMeetingMahasiswa($tipe_zoom);
        $booking = new Booking();
        $booking->setUserId(Auth::id());
        $booking->saveFromRequest($tipe_zoom, $request);

        try {
            EmailHelper::notifyAdminNewBooking($booking, $request, $tipe_zoom);
        } catch (\Throwable $th) {
        }

        $id = $booking['id'];
        return redirect()->route('booking.view', compact(['tipe_zoom', 'id']));
    }

    // View edit booking form
    function viewEditBooking($tipe_zoom, $id)
    {
        // BookingController::abortIfMeetingMahasiswa($tipe_zoom);
        $booking = Booking::findOrFail($id);
        $booking->abortIfVerified();
        $booking->setUserFields($booking->user_id);
        $booking->unit_type_id = Unit::getTypeIdById($booking->unit_id);
        $units = Unit::getDefault();
        $unitTypes = UnitType::get();
        $booking_times = $booking->getTimes();
        $kategoris = Kategori::get();
        $isAdmin = User::findOrLogout(Auth::id())->isAdmin();

        return view('booking.form', compact(['booking', 'units', 'unitTypes', 'booking_times', 'kategoris', 'tipe_zoom', 'isAdmin']));
    }

    // Save an edited booking
    function saveEditBooking($tipe_zoom, EditBookingRequest $request)
    {
        // BookingController::abortIfMeetingMahasiswa($tipe_zoom);
        $booking = Booking::findOrFail($request['id']);
        $booking->abortIfVerified();
        $booking->saveFromRequest($tipe_zoom, $request);

        $id = $request['id'];
        return redirect()->route('booking.view', compact(['tipe_zoom', 'id']));
    }

    // View a booking
    function viewBooking($tipe_zoom, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->setOrgFields($booking['unit_id']);
        if (Auth::check()) {
            $isAdmin = User::findOrLogout(Auth::id())->isAdmin();
            $isOwner = $booking->isOwner(Auth::id());
            $admins = User::getAdminDPTSIDropdownOptions();
            if ($isAdmin || $isOwner) {
                $booking->setUserFields($booking['user_id']);
                $booking->setAdminFields($booking['admin_id']);
            }
        } else {
            $isAdmin = false;
            $isOwner = false;
            $admins = null;
        }
        $booking_times = $booking->getTimes();
        return view('booking.view',
            compact(['booking', 'booking_times', 'admins', 'tipe_zoom', 'isOwner', 'isAdmin'])
        );
    }

    // verify a booking as admin
    function verifyBooking($tipe_zoom, VerifyBookingRequest $request)
    {
        $booking = Booking::findorfail($request->id);
        $booking->verifyBooking($request->verify);
        $returnMessage = "empty";
        if ($booking->checkApproved()) {
            $booking->disetujui = true;
            $booking->deskripsi_disetujui = "";

            $book_times = BookingTime::where('booking_id', $booking->id)
                ->orderBy('waktu_mulai')
                ->get();

            try {
                ZoomAPIHelper::createFromBookingTimes($book_times, $booking);
            } catch (Exception $err) {
                return redirect()->back()->withErrors([
                    $err->getMessage()
                ]);
            }

            $returnMessage = 'Booking berhasil dibuat dan sudah diemailkan ke user';
        } else {
            $booking->disetujui = false;
            $booking->deskripsi_disetujui = $request->alasan;
            $email = $booking->user->email;

            try {
                EmailHelper::notifyBookingDenied($booking, $email, $tipe_zoom);
            } catch (\Throwable $th) {
                return redirect()->back()->withErrors([
                    "Booking ditolak, tetapi email ke user tidak terkirim!"
                ]);
            }

            $returnMessage = 'Booking ditolak! User sudah diberitahu lewat email';
        }
		$booking->admin_id = $request->adminDPTSI?:null;
        $booking->save();
        return redirect()->back()->with('message', $returnMessage);
    }

    // Get booking data owned by user
    function listBookingData($tipe_zoom)
    {
        // BookingController::abortIfMeetingMahasiswa($tipe_zoom);
        $model = Booking::viewBookingList($tipe_zoom, Auth::id())
            ->newQuery();

        return DataTables::eloquent($model)
            ->filterColumn('sub.disetujui', function ($query, $keyword) {
                $keyword = strtolower($keyword);
                if ($keyword == 'disetujui') {
                    $query->where('sub.disetujui', true);
                } else if ($keyword == 'ditolak') {
                    $query->where('sub.disetujui', false);
                } else if ($keyword == 'menunggu konfirmasi') {
                    $query->whereNull('sub.disetujui');
                }
            })
            ->toJson();
    }

    // Show booking table owned by user
    function waitingListBooking($tipe_zoom)
    {
        // BookingController::abortIfMeetingMahasiswa($tipe_zoom);
        return view('booking.table', compact(['tipe_zoom']));
    }

    // Delete a given booking
    function deleteBooking($tipe_zoom, Request $request)
    {
        // BookingController::abortIfMeetingMahasiswa($tipe_zoom);
        $id = $request['id'];
        Booking::destroy($id);

        return redirect()->route('booking.list', compact(['tipe_zoom']));
    }

    // Delete a given booking as admin
    function adminDeleteBooking($tipe_zoom, Request $request)
    {
        $id = $request['id'];
        Booking::destroy($id);

        return redirect()->route('admin.list', compact(['tipe_zoom']));
    }

    // Get booking list data as Admin
    function adminListBookingData($tipe_zoom)
    {
        $model = Booking::viewBookingList($tipe_zoom)
            ->newQuery();

        return DataTables::eloquent($model)
            ->filterColumn('sub.disetujui', function ($query, $keyword) {
                $keyword = strtolower($keyword);
                if ($keyword == 'disetujui') {
                    $query->where('sub.disetujui', true);
                } else if ($keyword == 'ditolak') {
                    $query->where('sub.disetujui', false);
                } else if ($keyword == 'menunggu konfirmasi') {
                    $query->whereNull('sub.disetujui');
                }
            })
            ->toJson();
    }

    // Show booking list table as Admin
    function adminListBooking($tipe_zoom)
    {
        return view('admin.table', compact(['tipe_zoom']));
    }

    function adminAproveBookingData($tipe_zoom)
    {
        $model = Booking::viewBookingList($tipe_zoom)
            ->where('disetujui', true)
            ->newQuery();

        return DataTables::eloquent($model)
            ->toJson();
    }

    function aproveBooking($tipe_zoom)
    {
        return view('admin.aprove', compact(['tipe_zoom']));
    }

    // Get booking data for calendar event
    function getEvents(Request $request) {
        $bookings = Booking::join('booking_times as bt', 'bt.booking_id', '=', 'bookings.id')
            ->where('bookings.disetujui', true)
            ->where('bt.waktu_mulai', '>=', $request->start)
            ->where('bt.waktu_mulai', '<=', $request->end)
            ->get([
                'bookings.id',
                'nama_acara as title',
                'bt.waktu_mulai as start',
                'bt.waktu_akhir as end',
                'gladi',
                'bt.tipe_zoom',
            ]);
        foreach ($bookings as $booking) {
            $tipe_zoom = $booking['tipe_zoom'];
            $id = $booking['id'];
            if ($tipe_zoom == 'webinar') {
                $booking['color'] = "#fae9e8";
                $booking['textColor'] = "#000000";
                $booking['url'] = route('booking.view', compact(['tipe_zoom', 'id']));
            } else if ($tipe_zoom == 'meeting') {
                $booking['color'] = "#e0fffe";
                $booking['textColor'] = "#000000";
                $booking['url'] = route('booking.view', compact(['tipe_zoom', 'id']));
            }
            if ($booking['gladi']) $booking['title'] = "Gladi: {$booking['title']}";
            // Force timezone to Asia/Jakarta
            $booking['start'] = Carbon::parse($booking['start'])->addHours(7);
            $booking['end'] = Carbon::parse($booking['end'])->addHours(7);
        }

        return json_encode($bookings);
    }
}
