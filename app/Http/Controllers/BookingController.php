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
    function viewNewBooking(Request $request)
    {
        $booking = new Booking();
        $booking->setUserFields(Auth::id());
        $units = Unit::getDefault();
        $unitTypes = UnitType::get();
        $booking_times = null;
        $kategoris = Kategori::get();
        $isAdmin = User::findOrLogout(Auth::id())->isAdmin();

        return view('booking.form', compact(['booking', 'units', 'unitTypes', 'booking_times', 'kategoris', 'isAdmin']));
    }

    function saveNewBooking(NewBookingRequest $request)
    {
        $booking = new Booking();
        $booking->setUserId(Auth::id());
        $booking->saveFromRequest($request);

        try {
            EmailHelper::notifyAdminNewBooking($booking, $request);
        } catch (\Throwable $th) {
        }

        return redirect()->route('booking.view', ['id' => $booking['id']]);
    }

    function viewEditBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->abortIfVerified();
        $booking->setUserFields($booking->user_id);
        $booking->unit_type_id = Unit::getTypeIdById($booking->unit_id);
        $units = Unit::getDefault();
        $unitTypes = UnitType::get();
        $booking_times = $booking->getTimes();
        $kategoris = Kategori::get();
        $isAdmin = User::findOrLogout(Auth::id())->isAdmin();

        return view('booking.form', compact(['booking', 'units', 'unitTypes', 'booking_times', 'kategoris', 'isAdmin']));
    }

    function saveEditBooking(EditBookingRequest $request)
    {
        $booking = Booking::findOrFail($request['id']);
        $booking->abortIfVerified();
        $booking->saveFromRequest($request);

        return redirect()->route('booking.view', ['id' => $request['id']]);
    }

    function viewBooking($id)
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
        return view(
            'booking.view',
            compact(['booking', 'isOwner', 'isAdmin', 'booking_times', 'admins'])
        );
    }

    function verifyBooking(VerifyBookingRequest $request)
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
                EmailHelper::notifyBookingDenied($booking, $email);
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

    function listBookingData()
    {
        $model = Booking::viewBookingList(Auth::id())
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

    function waitingListBooking()
    {
        return view('booking.table');
    }

    function deleteBooking(Request $request)
    {
        $id = $request['id'];
        Booking::destroy($id);

        return redirect()->route('booking.list');
    }

    function adminDeleteBooking(Request $request)
    {
        $id = $request['id'];
        Booking::destroy($id);

        return redirect()->route('admin.list');
    }

    //Admin
    function adminListBookingData()
    {
        $model = Booking::viewBookingList()
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

    function adminListBooking()
    {
        return view('admin.table');
    }

    function adminAproveBookingData()
    {
        $model = Booking::viewBookingList()
            ->where('disetujui', true)
            ->newQuery();

        return DataTables::eloquent($model)
            ->toJson();
    }

    function aproveBooking()
    {
        return view('admin.aprove');
    }

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
                'gladi'
            ]);
        foreach ($bookings as $booking) {
            $booking['color'] = "#fae9e8";
            if ($booking['gladi']) $booking['title'] = "Gladi: {$booking['title']}";
            $booking['start'] = Carbon::parse($booking['start']);
            $booking['end'] = Carbon::parse($booking['end']);
        }

        return json_encode($bookings);
    }
}
