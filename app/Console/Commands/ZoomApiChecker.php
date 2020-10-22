<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\BookingTime;
use App\Helpers\ZoomAPIHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class ZoomApiChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoom:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cek status booking dan tembak Zoom API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Completed bookings with 20 mins grace period
        $booking_times = BookingTime::where('waktu_akhir', '<=', Carbon::now()->subMinutes(20))
            ->where('status', 'started')
            ->orderBy('waktu_akhir', 'asc')
            ->get();

        foreach ($booking_times as $booking_time) {
            // End webinar
            if ($booking_time->tipe_zoom == 'webinar') {
                $response = Http::withToken(ZoomAPIHelper::generate_token())
                    ->put("https://api.zoom.us/v2/webinars/{$booking_time->webinar_id}/status", ['action' => 'end']);
            } else if ($booking_time->tipe_zoom == 'meeting') {
                $response = Http::withToken(ZoomAPIHelper::generate_token())
                    ->put("https://api.zoom.us/v2/meetings/{$booking_time->webinar_id}/status", ['action' => 'end']);
            }
            // Try at next iteration
            if ($response->failed()) continue;
            // Get share recording link to the webinar that has just finisehd.
            $response = Http::withToken(ZoomAPIHelper::generate_token())
                ->get("https://api.zoom.us/v2/meetings/{$booking_time->webinar_id}/recordings", ['action' => 'end']);
            // Don't do anything if getting recording fails, it might be because webinar doesn't exist or recordings doesn't exist
            if ($response->failed()) {
                \Log::warning("Failed to get recording");
                \Log::warning($response->json());
            } else {
                // Send an email to the PIC of the share_url
                $email = $booking_time->booking->user->email;
                $data = [
                    'topic' => $booking_time->booking->nama_acara,
                    'share_url' => $response->json()['share_url'],
                    'tipe_zoom' => $booking_time->tipe_zoom,
                ];
                try {
                    Mail::send('emails.booking_finished', $data, function ($message) use ($email) {
                        $message->to($email);
                        $message->subject('WEBINAR ITS - Selesai');
                    });
                } catch (\Throwable $th) {
                    \Log::warning('Masalah dalam pengiriman email share_url recording ke user');
                    \Log::warning($th);
                }
            }
            // Make new password
            $password = $this->generate_password();
            // Reset password
            $response = Http::withToken(ZoomAPIHelper::generate_token())
                ->put("https://api.zoom.us/v2/users/{$booking_time->host->zoom_id}/password", ['password' => $password]);
            // Try at next iteration
            if ($response->failed()) continue;
            // Update host account password
            $booking_time->host->pass = $password;
            $booking_time->host->save();
            // Update booking status
            $booking_time->status = 'completed';
            $booking_time->save();
        }

        // Bookings that will start soon
        $booking_times = BookingTime::where('booking_times.waktu_mulai', '<=', Carbon::now()->addMinutes(37))
            ->join('bookings', 'bookings.id', '=', 'booking_times.booking_id')
            ->where('bookings.disetujui', true)
            ->where('booking_times.disetujui', true)
            ->where('booking_times.status', 'pending')
            ->orderBy('booking_times.waktu_mulai', 'asc')
            ->get('booking_times.*');

        foreach ($booking_times as $booking_time) {
            // Make new password
            $password = $this->generate_password();
            // Reset password
            $response = Http::withToken(ZoomAPIHelper::generate_token())
                ->put("https://api.zoom.us/v2/users/{$booking_time->host->zoom_id}/password", ['password' => $password]);
            // Try at next iteration
            if ($response->failed()) continue;
            // Update host account password
            $booking_time->host->pass = $password;
            $booking_time->host->save();
            // Send email
            $email = $booking_time->booking->user->email;
            $data = [
                'email' => $booking_time->host->zoom_email,
                'password' => $password,
                'tipe_zoom' => $booking_time->tipe_zoom,
            ];
            try {
                Mail::send('emails.host_credential', $data, function ($message) use ($email) {
                    $message->to($email);
                    $message->subject('WEBINAR ITS - Akses');
                });
            } catch (\Throwable $th) {
                \Log::warning('Masalah dalam pengiriman host_credentials ke user 40 menit sebelum webinar/meeting mulai');
                \Log::warning($th);
            }
            // Update booking status
            $booking_time->status = 'started';
            $booking_time->save();
        }

        return 0;
    }

    function generate_password()
    {
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        return 'W3b' . substr(str_shuffle($str_result), 0, 13);
    }
}
