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
        // True if checkbox checked, false not checked
        $relayITSTV = $request->has('relayITSTV'); 
        $peserta_banyak = $request->pesertaBanyak == 500 ? false:true;

        $this->no_wa = $request->noWa;
        $this->nama_acara = $request->namaAcara;
        $this->unit_id = $request->penyelengaraAcara;
        $this->waktu_mulai = $request->waktuMulai;
        $this->waktu_akhir = $request->waktuSelesai;
        $this->relay_ITSTV = $relayITSTV;
        $this->peserta_banyak = $peserta_banyak;
        $this->save();
    }

    public function verifyRequest($request) {
        if ($request->verify == 'setuju') {
            $this->disetujui = true;
            $this->api_host_nama = $request->hostNama;
            $this->api_host_email = $request->hostEmail;
        } else if ($request->verify == 'tolak') {
            $this->disetujui = false;
            $this->api_host_nama = null;
            $this->api_host_email = null;
        }

        $this->deskripsi_disetujui = $request->alasan;
        $this->save();
    }

    public function setUserFields($id) {
        $user = User::findOrFail($id);
        $this->integra_pic = $user->nama;
        $this->nama_pic = $user->integra;
        $this->email_pic = $user->email;
        $this->sivitas = Group::getNamaFromId($user->group_id);
    }

    public function setOrgFields($id) {
        $unit = Unit::join('unit_types as t', 't.id', '=', 'units.unit_type_id')
            ->where('units.id', '=', $id)
            ->first(['units.nama as nama', 't.nama as type']);
        $this->unit_type = $unit->type;
        $this->unit = $unit->nama;
    }

    public function setUserId($id) {
        $this->user_id = $id;
    }

    public function abortIfVerified() {
        if ($this->disetujui != null) {
            abort(403);
        }
    }

    /**
     * Aborts the current request if the booking is not 
     * owned by the current user 
     * @return void
     */ 
    public function abortButOwner($id) {
        if (!$this->isOwner($id)) {
            abort(403);
        }
    }

    /**
     * Check if user is owner of booking
     * @return boolean
     */ 
    public function isOwner($id) {
        return $this->user_id == $id;
    }
}