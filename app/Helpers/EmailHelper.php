<?php

namespace App\Helpers;

use App\Unit;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EmailHelper {

    static function notifyAdminNewBooking($booking, $request, $tipe_zoom) {
        $emails = User::where('verifier', true)->pluck('email')->toArray();

        $data = [
            'id' => $booking->id,
            'nama_acara' => $request->namaAcara,
            'nama_user' => User::findOrLogout(Auth::id())->nama,
            'unit' => Unit::findOrFail($request->penyelengaraAcara)->nama,
            'tipe_zoom' => $tipe_zoom,
        ];
        // Send email to admin
        try {
            Mail::send('emails.booking_created', $data, function ($message) use ($emails) {
                $message->to($emails);
                $message->subject('Webinar Baru');
            });
        } catch (\Throwable $th) {
            // Terdapat error dalam pengiriman email ke admin
            \Log::warning('Masalah dalam pengiriman email pemberitahuan ada booking baru ke admin');
            \Log::warning($th);
            throw $th;
        }
    }

    static function notifyBookingDenied($booking, $email, $tipe_zoom) {
        $data = [
            'topic' => $booking->nama_acara,
            'id' => $booking->id,
            'tipe_zoom' => $tipe_zoom,
        ];

        try {
            Mail::send('emails.booking_denied', $data, function ($message) use ($email) {
                $message->to($email);
                $message->subject('WEBINAR ITS - Belum Disetujui');
            });
        } catch (\Throwable $th) {
            \Log::warning('Masalah dalam pengiriman pemberitahuan kepada user bahwa booking telah ditolak');
            \Log::warning($th);
            throw $th;
        }
    }
}
