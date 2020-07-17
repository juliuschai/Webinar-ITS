<?php

namespace App\Http\Controllers;

use App\Organisasi;
use App\OrgType;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganisasiController extends Controller
{
    function viewOrganisasi() {
        User::find(Auth::id())->abortButAdmin();
        $organisasis = Organisasi::join('org_types as t', 't.id', '=', 'organisasis.org_type_id')
            ->select(['organisasis.id', 'organisasis.nama', 't.nama as org_type'])
            ->orderBy('t.id')
            ->paginate('20');
        $types = OrgType::get();
        return view('organisasi.view', compact('organisasis', 'types'));
    }

    function addOrganisasi(Request $request) {
        User::find(Auth::id())->abortButAdmin();
        $this->validate($request, [
            'orgNama' => 'required|string|max:254',
            'orgType' => 'required|numeric',
        ]);
        $org = new Organisasi();
        $org->nama = $request->orgNama;
        $org->org_type_id = $request->orgType;
        $org->save();
        $org_type = OrgType::findOrFail($request->orgType)->nama;

        return redirect()->route('organisasi.view')->with('message', "added \"{$org->nama}\" with type \"{$org_type}\"");
    }

    function delOrganisasi($id) {
        User::find(Auth::id())->abortButAdmin();
        $org = Organisasi::findOrFail($id);
        $org_nama = $org->nama;
        $org_type = OrgType::findOrFail($org->org_type_id)->nama;
        $org->delete();
        return redirect()->route('organisasi.view')->with('message', "deleted \"{$org_nama}\" with type \"{$org_type}\"");
    }
}
