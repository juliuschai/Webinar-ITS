<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HostAccount;
use DataTables;

class HostAccountController extends Controller
{
    function getAccounts() {
        $hosts = HostAccount::orderBy('id')
            ->paginate(10);
            
        $length = HostAccount::count();

        return view('admin.host.view', compact(['hosts', 'length']));
    }


    function getData() {
        $model = HostAccount::viewHostList()
            ->toQuery();
			
        return DataTables::eloquent($model)
            ->toJson();
	}
}
