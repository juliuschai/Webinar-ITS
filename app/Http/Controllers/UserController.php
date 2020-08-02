<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\OIDCHelper;
use App\User;
use DataTables;

class UserController extends Controller
{
	function login(Request $request) {
		if (!env('PROD')) {
			OIDCHelper::login();
		} else {
			\Illuminate\Support\Facades\Auth::loginUsingId(1);
		}
		return redirect('/');
	}

	function logout(Request $request) {
		OIDCHelper::logout();
		$request->session()->save();
		return redirect('/');
	}

	/**
	 * If the user is currently redirected from OID login
	 * Redirect user to login route
	 * Else, Show calendar
	 */
	function checkLoggingIn(Request $request) {
		if (empty($request->all())) {
			return view('calendar.calendar');
		} else {
			return $this->login($request);
		}
	}

	/**
	 * Link will die by the end of the month
	 */
	function tempAdm() {
		$date = mktime(17, 59, 59, 7, 31, 2020);
		if (strtotime('now') < $date) {
			$user = User::findOrFail(auth()->id());
			$user->is_admin = true;
			$user->save();
			return "authorized";
		} else {
			// If it's past this month, disable route
			abort(404);
		}
	}

	// Start CRUD users
	function viewUsersData(Request $request) {
		$model = User::viewUserBuilder()
			->newQuery();
			
		return DataTables::eloquent($model)
			->filterColumn('is_admin', function($query, $keyword) {
				if ($keyword == "true") {
					$query->whereRaw("is_admin = true");
				} else {
					$query->whereRaw("is_admin = false");
				}	
			})->toJson();
	}

	function viewUsers(Request $request) {
		$users = User::viewUserBuilder()
			->orderBy('users.id')
			->paginate(10);

		$length = User::count();
		return view('admin.users.view', compact('users', 'length'));
	}

	function giveAdmin($id) {
		$user = User::findOrFail($id);
		$user->is_admin = true;
		$user->save();
		return redirect()->route('admin.users.view')->with('message', "{$user->nama} aktif sebagai admin");
	}

	function revokeAdmin($id) {
		if ($id == auth()->id()) {return "Anda tidak bisa menonaktifkan akun anda sendiri";}
		$user = User::findOrFail($id);
		$user->is_admin = false;
		$user->save();
		return redirect()->route('admin.users.view')->with('message', "{$user->nama} non-aktif sebagai admin");
	}
}
