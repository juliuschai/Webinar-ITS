<?php

namespace App\Http\Controllers;

use App\Unit;
use App\UnitType;
use Illuminate\Http\Request;
use DataTables;

class UnitController extends Controller
{
    // Get unit data
    function viewUnitData() {
        $model = Unit::viewUnitBuilder()->newQuery();

        return DataTables::eloquent($model)
            ->filterColumn('unit_types.id', function($query, $keyword) {
                $query->whereRaw("unit_types.id = ?", [$keyword]);
            })->toJson();
    }

    // View unit table
    function viewUnit(Request $request) {
        $units = Unit::viewUnitBuilder()
            ->orderBy('units.id')
            ->paginate('10');

        $types = UnitType::get();
        $length = Unit::count();
        return view('admin.unit.view', compact('units', 'types', 'length'));
    }

    // Save new unit
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

    // Delete unit
    function delUnit($id) {
        $unit = Unit::findOrFail($id);
        $unit_nama = $unit->nama;
        $unit_type = UnitType::findOrFail($unit->unit_type_id)->nama;
        $unit->delete();
        return redirect()->route('admin.unit.view')->with('message', "deleted \"{$unit_nama}\" with type \"{$unit_type}\"");
    }

    // View unit booking form
    function viewEditUnit($id) {
        $unit = Unit::findOrFail($id);
        $types = UnitType::get();
        return view('admin.unit.edit', compact('unit','types'));
    }

    // Save edited unit
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
