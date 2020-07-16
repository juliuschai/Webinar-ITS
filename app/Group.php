<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public $timestamps = false;
    /**
     * Get names of all group from database
     */
    static function getGroupList() {
        return Group::select('name')->get();
    }

    /**
     * Get associative array of group where key is
     * group.name and value is group.id
     */
    static function getGroupLookup() {
        return Group::pluck('id', 'name');
    }

    static function getIdFromName($name) {
        
        return Group::where('name', '=', $name)->firstOrFail()->id;
    }

    static function getNameFromId($id) {
        return Group::findOrFail($id)->name;
    }

    static function findOrCreateGetId($str) {
        $group = Group::firstOrCreate(['name' => $str]);
        return $group['id'];
    }
}
