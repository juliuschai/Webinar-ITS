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

        // Ids from input
        $inReqIds = [];
        $length = count($request->bookingTimes);
        for ($i = 0; $i < $length; $i++) {
            $inBookTime = $request->bookingTimes[$i];
            // if id isn't in db, create new
            if ($inBookTime['id']) {
                $reqId = $inBookTime['id'];
                $inReqIds[] = $reqId;
                if(isset($inDb[$reqId])) {
                    // if id is disetujui in db, drop changes
                    if ($inDb[$reqId] == true) {
                        continue;
                    } else {
                        $curBookTime = BookingTime::find($reqId);
                    }
                }
            } else {
                $curBookTime = new BookingTime();
            }

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

        // if indb is not disetujui and not in inReqIds, delete
        foreach ($inDb as $id => $disetujui) {
            if (!$disetujui && !in_array($id, $inReqIds)) {
                BookingTime::find($id)->delete();
            }
        }

        $retTimes = [];
        $book_times = BookingTime::where('booking_id', '=', $bookingId)
            ->get(['waktu_mulai', 'waktu_akhir']);
        $retTimes['waktu_mulai'] = $book_times->min('waktu_mulai');
        $retTimes['waktu_akhir'] = $book_times->max('waktu_akhir');

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
