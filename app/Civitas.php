<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Civitas extends Model
{
    public $timestamps = false;
    /**
     * Get names of all civitas from database
     */
    static function getCivitasList() {
        return Civitas::select('nama')->get();
    }

    /**
     * Get associative array of civitas where key is
     * civitas.nama and value is civitas.id
     */
    static function getCivitasLookup() {
        return Civitas::pluck('id', 'nama');
    }
}
