<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingTime extends Model
{

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_akhir' => 'datetime',
        'gladi' => 'boolean',
        'relayITSTV' => 'boolean',
    ];

    static function saveFromRequest($request, $bookingId)
    {
        $inDb = BookingTime::where('booking_id', '=', $bookingId)
            ->pluck('disetujui', 'id');
        $retTimes = [];

        $length = count($request->bookingTimes);
        for ($i = 0; $i < $length; $i++) {
            $inBookTime = $request->bookingTimes[$i];
            // if id isn't in db, create new
            if ($inBookTime['id'] && isset($inDb[$inBookTime['id']])) {
                // if id is disetujui in db, drop changes
                if ($inDb[$inBookTime['id']] == true) {
                    continue;
                } else {
                    $curBookTime = BookingTime::find($inBookTime['id']);
                }
            } else {
                $curBookTime = new BookingTime();
            }
            if (!isset($retTimes['waktu_mulai'])) {
                $retTimes['waktu_mulai'] = $inBookTime['waktuMulai'];
            }
            // get the latest waktu_akhir
            $retTimes['waktu_akhir'] = $inBookTime['waktuSelesai'];

            $relayITSTV = $inBookTime['relayITSTV'] == "true";
            $peserta_banyak = $inBookTime['pesertaBanyak'] == 1000;
            $gladi = $inBookTime['gladi'] == "true";

            $curBookTime->booking_id = $bookingId;
            $curBookTime->waktu_mulai = $inBookTime['waktuMulai'];
            $curBookTime->waktu_akhir = $inBookTime['waktuSelesai'];
            $curBookTime->relay_ITSTV = $relayITSTV;
            $curBookTime->peserta_banyak = $peserta_banyak;
            $curBookTime->gladi = $gladi;
            $curBookTime->save();
        }
        if (!isset($retTimes['waktu_mulai'])) {
            abort(403, 'Tidak ada sesi booking yang valid!
				Silahkan mengecheck kembali sesi-sesi booking anda.');
        }

        return $retTimes;
    }

    function setHostAccount()
    {
        $this->host_accounts = HostAccount::getValidAccounts($this->waktu_mulai, $this->waktu_akhir, $this->id, $this->peserta_banyak);
    }

    function verifyBookTime($reqVerify)
    {
        if ($reqVerify['status'] == "accept") {
            $status = true;
        } else if ($reqVerify['status'] == "deny") {
            $status = false;
        } else {
            $status = null;
        }

        $this->disetujui = $status;
        if ($status) {
            if (HostAccount::checkAvailability(
                $this->waktu_mulai,
                $this->waktu_akhir,
                $this->id,
                $this->peserta_banyak,
                $reqVerify['hostAccount']
            )) {
                $this->host_account_id = $reqVerify['hostAccount'];
            } else {
                abort(403, "Terjadi masalah dalam assigning host account, silahkan coba lagi!
                    (Mungkin terjadi karena terdapat perubahan availabilitas host account)");
            }
        } else {
            $this->host_account_id = null;
        }
        $this->save();
    }

    public function booking()
    {
        return $this->hasOne('App\Booking', 'id', 'booking_id');
    }

    public function host()
    {
        return $this->hasOne('App\HostAccount', 'id', 'host_account_id');
    }
}
