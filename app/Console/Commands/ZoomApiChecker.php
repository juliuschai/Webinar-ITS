<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\BookingTime;
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
            $response = Http::withToken($this->generate_token())
                ->put("https://api.zoom.us/v2/webinars/{$booking_time->webinar_id}/status", ['action' => 'end']);
            // Try at next iteration
            if ($response->failed()) continue;
            // Make new password
            $password = $this->generate_password();
            // Reset password
            $response = Http::withToken($this->generate_token())
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
            $response = Http::withToken($this->generate_token())
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
                'password' => $password
            ];
            Mail::send('emails.host_credential', $data, function ($message) use ($email) {
                $message->to($email);
                $message->subject('WEBINAR ITS');
            });
            // Update booking status
            $booking_time->status = 'started';
            $booking_time->save();
        }

        return 0;
    }

    function generate_token()
    {
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
        return $token;
    }

    function generate_password()
    {
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        return 'W3b' . substr(str_shuffle($str_result), 0, 13);
    }
}
