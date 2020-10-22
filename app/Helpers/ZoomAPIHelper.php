<?php

namespace App\Helpers;

use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class ZoomAPIHelper
{
    static function createFromBookingTimes($book_times, $booking) {
        $email_datas = ['datas' => []];
        $gladiCount = 0;
        $webinarCount = 0;
        // Schedule a webinar for each booking time and send invitation email
        foreach ($book_times as $book_time) {
            if ($book_time->tipe_zoom == 'webinar') {
                $response = ZoomAPIHelper::createWebinar($booking->nama_acara, $book_time);
            } else if ($book_time->tipe_zoom == 'meeting') {
                $response = ZoomAPIHelper::createMeeting($booking->nama_acara, $book_time);
            }
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
                    'password' => $response->json()['password'],
                    'tipe_zoom' => $book_time->tipe_zoom,
                ];
            } else {
                \Log::warning('Masalah dalam pembookingan webinar');
                \Log::warning($response->json()['message']);
                throw new Exception($response->json()['message']);
            }
        }

        // Send email to user after Zoom API success
        $email = $booking->user->email;
        try {
            Mail::send('emails.booking_approved', $email_datas, function ($message) use ($email) {
                $message->to($email);
                $message->subject('WEBINAR ITS - Disetujui');
            });
        } catch (\Throwable $th) {
            \Log::warning('Masalah dalam pengiriman email link webinars yang baru dibuat ke user');
            \Log::warning($th);
            throw new Exception('Webinar sudah dibooking, tetapi email ke user tidak terkirim!');
        }
    }

    static function createWebinar($nama_acara, $book_time) {
        $token = ZoomAPIHelper::generate_token();
        // Schedule a webinar
        $durasi = floor((strtotime($book_time->waktu_akhir) - strtotime($book_time->waktu_mulai)) / 60);
        $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $webinar_pass = substr(str_shuffle($permitted_chars), 0, 8);
        $data = [
            'topic' => $nama_acara,
            'type' => 5,
            'start_time' => $book_time->waktu_mulai->format(DateTime::ATOM),
            'duration' => $durasi,
            'timezone' => 'UTC',
            'password' => $webinar_pass,
            'agenda' => $nama_acara,
            'settings' => [
                "host_video" => "true",
                "panelists_video" => "true",
                "practice_session" => "true",
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
        return $response;
    }

    static function createMeeting($nama_acara, $book_time) {
        $token = ZoomAPIHelper::generate_token();
        // Schedule a webinar
        $durasi = floor((strtotime($book_time->waktu_akhir) - strtotime($book_time->waktu_mulai)) / 60);
        $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $webinar_pass = substr(str_shuffle($permitted_chars), 0, 8);
        $data = [
            'topic' => $nama_acara,
            'type' => 2,
            'start_time' => $book_time->waktu_mulai->format(DateTime::ATOM),
            'duration' => $durasi,
            'timezone' => 'UTC',
            'password' => $webinar_pass,
            'agenda' => $nama_acara,
            'settings' => [
                "host_video" => "false",
                "participant_video" => "false",
                "mute_upon_entry" => "true",
                "watermark" => "false",
                "audio" => "both",
                "auto_recording" => "none",
            ]
        ];

        $response = Http::withToken($token)->post("https://api.zoom.us/v2/users/{$book_time->host->zoom_id}/meetings", $data);
        return $response;
    }

    static function generate_token()
    {
        // Generate new JSON Web Token
        $token_header = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9';
        $token_payload = [
            'iss' => env('ZOOM_API_KEY'),
            'exp' => (time() + 36000)
        ];
        $token_payload = base64_encode(json_encode($token_payload));
        $token_payload = str_replace(['+', '/', '='], ['-', '_', ''], $token_payload);
        $token_signature = hash_hmac('sha256', "$token_header.$token_payload", env('ZOOM_API_SECRET'), true);
        $token_signature = base64_encode($token_signature);
        $token_signature = str_replace(['+', '/', '='], ['-', '_', ''], $token_signature);
        $token = "$token_header.$token_payload.$token_signature";
        return $token;
    }
}
