<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organisasi extends Model
{
    public $timestamps = false;

    public static function getTypeIdById($id) {
        return Organisasi::join('org_types as t', 't.id', '=', 'organisasis.org_type_id')
            ->where('organisasis.id', '=', $id)
            ->first('t.id')->id;
    }
}
