<?php

namespace App\Http\Controllers;

use App\Unit;
use App\UnitType;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    function viewUnit(Request $request) {
        $unit_nama = $request->unitNama??'';
        $type_id = $request->unitType??'';
        $query = Unit::from('units as u')
        ->join('unit_types as t', 't.id', '=', 'u.unit_type_id')
        ->where('u.nama', 'LIKE', '%'.$unit_nama.'%');
        if ($type_id != '') {
            $query = $query->where('t.id', '=', $type_id);
        }
        $units = $query->select(['u.id', 'u.nama', 't.nama as unit_type'])
            ->orderBy('t.id')
            ->orderBy('u.nama')
            ->paginate('10');

        $types = UnitType::get();
        return view('admin.unit.view', compact('units', 'types', 'unit_nama', 'type_id'));
    }

    function addUnit(Request $request) {
        $this->validate($request, [
            'unitNama' => 'required|string|max:254',
            'unitType' => 'required|numeric',
        ]);
        $unit = new Unit();
        $unit->nama = $request->unitNama;
        $unit_nama = $unit->nama;
        $unit->unit_type_id = $request->unitType;
        $unit->save();
        $unit_type = UnitType::findOrFail($request->unitType)->nama;

        return redirect()->route('admin.unit.view')->with('message', "added \"{$unit_nama}\" with type \"{$unit_type}\"");
    }

    function delUnit($id) {
        $unit = Unit::findOrFail($id);
        $unit_nama = $unit->nama;
        $unit_type = UnitType::findOrFail($unit->unit_type_id)->nama;
        $unit->delete();
        return redirect()->route('admin.unit.view')->with('message', "deleted \"{$unit_nama}\" with type \"{$unit_type}\"");
    }

    function viewEditUnit($id) {
        $unit = Unit::findOrFail($id);
        $types = UnitType::get();
        return view('admin.unit.edit', compact('unit','types'));
    }

    function saveEditUnit($id, Request $request) {
        $unit = Unit::findOrFail($id);
        $unit->nama = $request->unitNama;
        $unit_nama = $unit->nama;
        $unit->unit_type_id = $request->unitType;
        $unit->save();
        $unit_type = UnitType::findOrFail($request->unitType)->nama;
        return redirect()->route('admin.unit.view')->with('message', "deleted \"{$unit_nama}\" with type \"{$unit_type}\"");
    }
}
