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

        $this->nama_pic = $request->namaPic;
        $this->integra_pic = $request->integraPic;
        $this->email_pic = $request->emailPic;
        $this->sivitas = $request->sivitas;
        $this->unit = $request->departemenUnit;
        $this->no_wa = $request->noWa;
        $this->nama_acara = $request->namaAcara;
        $this->org_id = $request->penyelengaraAcara;
        $this->waktu_mulai = $request->waktuMulai;
        $this->waktu_akhir = $request->waktuSelesai;
        $this->relay_ITSTV = $relayITSTV;
        $this->peserta_banyak = $peserta_banyak;
        $this->save();
    }

    public function verifyRequest($request) {
        if ($request->verify == 'accept') {
            $this->disetujui = true;
            $this->api_host_nama = $request->hostNama;
            $this->api_host_email = $request->hostEmail;
        } else if ($request->verify == 'deny') {
            $this->disetujui = false;
            $this->api_host_nama = null;
            $this->api_host_email = null;
        }

        $this->deskripsi_disetujui = now().': '.$request->alasan;
        $this->save();
    }

    public function setUserFields($id) {
        $user = User::findOrFail($id);
        $this->reg_email = $user->email;
        $this->reg_nama = $user->nama;
        $this->reg_integra = $user->integra;
        $this->group = Group::getNamaFromId($user->group_id);
    }

    public function setOrgFields($id) {
        $org = Organisasi::join('org_types as t', 't.id', '=', 'organisasis.org_type_id')
        ->where('organisasis.id', '=', $id)
        ->first(['organisasis.nama as nama', 't.nama as type']);
        $this->org_type = $org->type;
        $this->org_nama = $org->nama;
    }

    public function setUserId($id) {
        $this->user_id = $id;
    }

    public function abortIfApproved() {
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