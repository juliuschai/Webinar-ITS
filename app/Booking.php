<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    // I'm unsure if this is needed but I'm too afraid to test
    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_akhir' => 'datetime',
    ];

    /**
     * waktu_mulai accessor
     */
    public function getWaktuMulaiAttribute($value) {
        return Booking::getFormattedTimeOrNull($value);
    }

    /**
     * waktu_akhir accessor
     */
    public function getWaktuAkhirAttribute($value) {
        return Booking::getFormattedTimeOrNull($value);
    }

    static function getFormattedTimeOrNull($value) {
        if (!$value) {
            return null;
        } else {
            return date('Y-m-d\TH:i', strtotime($value));
        }
    }

    /**
     * Parses request based on booking.form view 
     * saves parsed data in current booking model instance  
     */
    public function saveFromRequest($request) {
        $civitas_id = Civitas::getIdFromNama($request->civitasAkademik);
        // True if checkbox checked, false not checked
        $relayITSTV = $request->has('relayITSTV'); 
        $peserta_banyak = $request['pesertaBanyak'] == 500 ? false:true;

        $this['nama_acara'] = $request['namaAcara'];
        $this['unit'] = $request['unitDepartemen'];
        $this['nama_booker'] = $request['namaAnda'];
        $this['email_its'] = $request['emailITS'];
        $this['user_integra'] = $request['userIntegra'];
        $this['waktu_mulai'] = $request['waktuMulai'];
        $this['waktu_akhir'] = $request['waktuSelesai'];
        $this['civitas_id'] = $civitas_id;
        $this['relay_ITSTV'] = $relayITSTV;
        $this['peserta_banyak'] = $peserta_banyak;
        $this->save();
    }

    /**
     * Aborts the current request if the booking is not 
     * owned by the current user 
     * @return void
     */ 
    public function abortButOwner($id) {
        if ($this->isBookingOwner($id)) {
            abort(403);
        }
    }

    /**
     * Check if user isn't owner of booking
     * @return boolean
     */ 
    public function isBookingOwner($id) {
        // TODO
        return true;
    }
}
