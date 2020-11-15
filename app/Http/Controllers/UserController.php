<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\OIDCHelper;
use App\User;
use DataTables;

class UserController extends Controller
{
	function login(Request $request) {
		OIDCHelper::login();
		return redirect('/');
	}

	function logout(Request $request) {
		OIDCHelper::logout();
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

	// Start CRUD users
	function viewUsersData(Request $request) {
		$model = User::viewUserBuilder()
			->newQuery();

		return DataTables::eloquent($model)
			->filterColumn('is_admin', function($query, $keyword) {
				if ($keyword == "true") {
					$query->where("is_admin", true);
				} else {
					$query->where("is_admin", false);
				}
			})->toJson();
	}

	function viewUsers() {
		return view('admin.users.view');
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

	function giveVerifier($id) {
		$user = User::findOrFail($id);
		$user->verifier = true;
		$user->save();
		return redirect()->route('admin.users.view')->with('message', "{$user->nama} set sebagai verifier");
	}

	function revokeVerifier($id) {
		if ($id == auth()->id()) {return "Anda tidak bisa menonaktifkan akun anda sendiri";}
		$user = User::findOrFail($id);
		$user->verifier = false;
		$user->save();
		return redirect()->route('admin.users.view')->with('message', "{$user->nama} set sebagai verifier");
	}
}
