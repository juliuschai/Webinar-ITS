<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    public $timestamps = false;

    static function viewUnitBuilder() {
        return Unit::join('unit_types', 'unit_types.id', '=', 'units.unit_type_id')
            ->select(['units.id', 'units.nama', 'unit_types.nama as unit_type']);
    }

    static function getTypeIdById($id) {
        return Unit::join('unit_types as t', 't.id', '=', 'units.unit_type_id')
            ->where('units.id', '=', $id)
            ->first('t.id')->id;
    }

    /**
     * Get Unit list with ascending names
     */
    static function getDefault() {
        return Unit::orderBy('nama')->get();
    }
}
