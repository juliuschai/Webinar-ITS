<?php

namespace App\Http\Controllers;

use App\Unit;
use App\UnitType;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
{
    function viewUnit() {
        User::find(Auth::id())->abortButAdmin();
        $units = Unit::join('unit_types as t', 't.id', '=', 'units.unit_type_id')
            ->select(['units.id', 'units.nama', 't.nama as unit_type'])
            ->orderBy('t.id')
            ->orderBy('units.nama')
            ->paginate('20');
        $types = UnitType::get();
        return view('unit.view', compact('units', 'types'));
    }

    function addUnit(Request $request) {
        User::find(Auth::id())->abortButAdmin();
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

        return redirect()->route('unit.view')->with('message', "added \"{$unit_nama}\" with type \"{$unit_type}\"");
    }

    function delUnit($id) {
        User::find(Auth::id())->abortButAdmin();
        $unit = Unit::findOrFail($id);
        $unit_nama = $unit->nama;
        $unit_type = UnitType::findOrFail($unit->unit_type_id)->nama;
        $unit->delete();
        return redirect()->route('unit.view')->with('message', "deleted \"{$unit_nama}\" with type \"{$unit_type}\"");
    }

    function viewEditUnit($id) {
        User::find(Auth::id())->abortButAdmin();
        $unit = Unit::findOrFail($id);
        $types = UnitType::get();
        return view('unit.edit', compact('unit','types'));
    }

    function saveEditUnit($id, Request $request) {
        User::find(Auth::id())->abortButAdmin();
        $unit = Unit::findOrFail($id);
        $unit->nama = $request->unitNama;
        $unit_nama = $unit->nama;
        $unit->unit_type_id = $request->unitType;
        $unit->save();
        $unit_type = UnitType::findOrFail($request->unitType)->nama;
        return redirect()->route('unit.view')->with('message', "deleted \"{$unit_nama}\" with type \"{$unit_type}\"");
    }
}
