<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public $timestamps = false;
    /**
     * Get namas of all group from database
     */
    static function getGroupList() {
        return Group::select('nama')->get();
    }

    /**
     * Get associative array of group where key is
     * group.nama and value is group.id
     */
    static function getGroupLookup() {
        return Group::pluck('id', 'nama');
    }

    static function getIdFromNama($nama) {
        
        return Group::where('nama', '=', $nama)->firstOrFail()->id;
    }

    static function getNamaFromId($id) {
        return Group::findOrFail($id)->nama;
    }

    static function findOrCreateGetId($str) {
        $group = Group::firstOrCreate(['nama' => $str]);
        return $group['id'];
    }
}
