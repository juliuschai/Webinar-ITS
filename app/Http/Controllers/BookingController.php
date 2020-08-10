<?php

namespace App\Http\Controllers;

use App\Booking;
use App\BookingTime;
use App\Http\Requests\NewBookingRequest;
use App\Http\Requests\EditBookingRequest;
use App\Http\Requests\VerifyBookingRequest;
use App\Kategori;
use App\Unit;
use App\UnitType;
use App\User;
use Carbon\Carbon;
use DateTime;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

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
        return view('booking.form', compact(['booking', 'units', 'unitTypes', 'booking_times', 'kategoris']));
    }

    function saveNewBooking(NewBookingRequest $request)
    {
        // function saveNewBooking(Request $request) {
        $booking = new Booking();
        // dd($request->bookingTimes);
        $booking->setUserId(Auth::id());
        $booking->saveFromRequest($request);
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
        return view('booking.form', compact(['booking', 'units', 'unitTypes', 'booking_times', 'kategoris']));
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
            $admins = User::where('is_admin', true)
                ->where('nama', 'LIKE', '%Rizki Rinaldi%')
                ->orWhere('nama', 'LIKE', '%Ernis Desna%')
                ->get();
            if ($isAdmin || $isOwner) {
                $booking->setUserFields($booking['user_id']);
                $booking->setAdminFields($booking['admin_id']);
            }
        } else {
            $isAdmin = false;
            $isOwner = false;
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
            $email_datas = ['datas'=>[]];
            $gladiCount = 0;
            $webinarCount = 0;
            // Schedule a webinar for each booking time and send invitation email
            foreach ($book_times as $book_time) {
                // Generate new JSON Web Token
                $token_header = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9';
                $token_payload = [
                    'iss' => env('ZOOM_API_KEY'),
                    'exp' => (time() + 15)
                ];
                $token_payload = base64_encode(json_encode($token_payload));
                $token_payload = str_replace(['+', '/', '='], ['-', '_', ''], $token_payload);
                $token_signature = hash_hmac('sha256', "$token_header.$token_payload", env('ZOOM_API_SECRET'), true);
                $token_signature = base64_encode($token_signature);
                $token_signature = str_replace(['+', '/', '='], ['-', '_', ''], $token_signature);
                $token = "$token_header.$token_payload.$token_signature";
                // Schedule a webinar
                $durasi = floor((strtotime($book_time->waktu_akhir) - strtotime($book_time->waktu_mulai)) / 60);
                $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $webinar_pass = substr(str_shuffle($permitted_chars), 0, 8);
                $data = [
                    'topic' => $booking->nama_acara,
                    'type' => 5,
                    'start_time' => date("Y-m-d\TH:i:s\Z"),
                    'duration' => $durasi,
                    'timezone' => 'Asia/Jakarta',
                    'password' => $webinar_pass,
                    'agenda' => $booking->nama_acara,
                    'settings' => [
                        "host_video" => "true",
                        "panelists_video" => "true",
                        "practice_session" => "false",
                        "hd_video" => "true",
                        "approval_type" => 2,
                        "audio" => "both",
                        "auto_recording" => "none",
                        "enforce_login" => "false",
                        "close_registration" => "false",
                        "show_share_button" => "true",
                        "allow_multiple_devices" => "true"
                    ]
                ];
                $response = Http::withToken($token)->post("https://api.zoom.us/v2/users/{$book_time->host->zoom_id}/webinars", $data);
                if ($response->successful()) {
                    // Save webinar id
                    $book_time->webinar_id = $response->json()['id'];
                    $book_time->save();
                    // Save email data
                    if ($book_time->gladi) {
                        $gladiCount++;
                        $index = "Booking Gladi Bersih $gladiCount";
                    } else {
                        $webinarCount++;
                        $index = "Booking Webinar $webinarCount";
                    }
                    $email_datas['datas'][] = [
                        'index' => $index,
                        'join_url' => $response->json()['join_url'],
                        'topic' => $response->json()['topic'],
                        'start_time' => Carbon::parse($response->json()['start_time'])->setTimezone('Asia/Jakarta'),
                        'webinar_id' => $response->json()['id'],
                        'password' => $response->json()['password']
                    ];
                } else {
                    \Log::error($response->json()['message']);
                    return redirect()->back()->withErrors([
                        $response->json()['message'],
                    ]);
                }
            }
            // Send email to user after Zoom API success
            $email = $booking->user->email;
            try {
                Mail::send('emails.booking_approved', $email_datas, function ($message) use ($email) {
                    $message->to($email);
                    $message->subject('WEBINAR ITS');
                });
            } catch (\Throwable $th) {
                \Log::error($th);
                return redirect()->back()->withErrors([
                    "Webinar sudah dibooking, tetapi email ke user tidak terkirim!"
                ]);
            }
            $returnMessage = 'Booking berhasil dibuat dan sudah diemailkan ke user';
        } else {
            $booking->disetujui = false;
            $booking->deskripsi_disetujui = $request->alasan;
            // Send email to user after getting denied
            $email = $booking->user->email;
            $data = [
                'topic' => $booking->nama_acara,
                'id' => $booking->id,
            ];
            try {
                Mail::send('emails.booking_denied', $data, function ($message) use ($email) {
                    $message->to($email);
                    $message->subject('WEBINAR ITS');
                });
            } catch (\Throwable $th) {
                \Log::error($th);
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
        $model = Booking::viewBookingList()
            ->where('bookings.user_id', '=', Auth::id())
            ->newQuery();

        return DataTables::eloquent($model)
            ->filterColumn('disetujui', function ($query, $keyword) {
                if ($keyword == "true") {
                    $query->whereRaw("disetujui = true");
                } else if ($keyword == "false") {
                    $query->whereRaw("disetujui = false");
                } else if ($keyword == "none") {
                    $query->whereRaw("disetujui IS NULL");
                }
            })
            ->toJson();
    }

    public function waitingListBooking()
    {

        $bookings = Booking::viewBookingList()
            ->where('bookings.user_id', '=', Auth::id())
            ->paginate('10');
        $length = Booking::where('bookings.user_id', '=', Auth::id())
            ->count();
        return view('booking.table', compact(['bookings', 'length']));
    }

    public function deleteBooking(Request $request)
    {
        $id = $request['id'];
        Booking::destroy($id);

        return redirect()->route('booking.list');
    }

    public function adminDeleteBooking(Request $request)
    {
        $id = $request['id'];
        Booking::destroy($id);

        return redirect()->route('admin.list');
    }

    function adminAproveBookingData()
    {
        $model = Booking::viewBookingList()
            ->where('bookings.disetujui', '=', '1')
            ->newQuery();

        return DataTables::eloquent($model)
            ->toJson();
    }

    //Admin
    function adminListBookingData()
    {
        $model = Booking::viewBookingList()
            ->newQuery();

        return DataTables::eloquent($model)
            ->filterColumn('disetujui', function ($query, $keyword) {
                if ($keyword == "true") {
                    $query->whereRaw("disetujui = true");
                } else if ($keyword == "false") {
                    $query->whereRaw("disetujui = false");
                } else if ($keyword == "none") {
                    $query->whereRaw("disetujui IS NULL");
                }
            })
            ->toJson();
    }

    public function adminListBooking(Request $request)
    {
        $bookings = Booking::viewBookingList()
            ->paginate('10');

        $length = Booking::count();
        return view('admin.table', compact(['bookings', 'length']));
    }

    public function aproveBooking(Request $request)
    {
        $bookings = Booking::viewBookingList()
            ->where('bookings.disetujui', '=', '1')
            ->paginate('10');

        $length = Booking::where('bookings.disetujui', '=', '1')
            ->count();
        return view('admin.aprove', compact(['bookings', 'length']));
    }

    function getEvents(Request $request) {
        $bookings = Booking::join('booking_times as bt', 'bt.booking_id', '=', 'bookings.id')
            ->where('bookings.disetujui', true)
            ->where('bt.gladi', false)
            ->where('bt.waktu_mulai', '>=', $request->start)
            ->where('bt.waktu_akhir', '<=', $request->end)
            ->get([
                'bookings.id',
                'nama_acara as title',
                'bt.waktu_mulai as start',
                'bt.waktu_akhir as end'
            ]);
        foreach ($bookings as $booking) {
            $booking['color'] = "#fae9e8";
            // $booking['start'] = Carbon::parse($booking['start'])->timezone('Asia/Jakarta')->toIso8601String();
            // $booking['end'] = Carbon::parse($booking['end'])->timezone('Asia/Jakarta')->toIso8601String();
            $booking['start'] = Carbon::parse($booking['start']);
            $booking['end'] = Carbon::parse($booking['end']);
        }

        return json_encode($bookings);
    }
}
