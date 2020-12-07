<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\OIDCHelper;
use App\User;
use DataTables;

class UserController extends Controller
{
    // Login menggunakan OIDC
	function login(Request $request) {
		OIDCHelper::login();
		return redirect('/');
	}

    // Logout menggunakan OIDC
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

    // View user table
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

    // View user table
	function viewUsers() {
		return view('admin.users.view');
	}

    // Set user as admin
	function giveAdmin($id) {
		$user = User::findOrFail($id);
		$user->is_admin = true;
		$user->save();
		return redirect()->route('admin.users.view')->with('message', "{$user->nama} aktif sebagai admin");
	}

    // Set admin as user
	function revokeAdmin($id) {
		if ($id == auth()->id()) {return "Anda tidak bisa menonaktifkan akun anda sendiri";}
		$user = User::findOrFail($id);
		$user->is_admin = false;
		$user->save();
		return redirect()->route('admin.users.view')->with('message', "{$user->nama} non-aktif sebagai admin");
	}

    // Set user as a verifier
	function giveVerifier($id) {
		$user = User::findOrFail($id);
		$user->verifier = true;
		$user->save();
		return redirect()->route('admin.users.view')->with('message', "{$user->nama} set sebagai verifier");
	}

    // remove user as a verifier
	function revokeVerifier($id) {
		if ($id == auth()->id()) {return "Anda tidak bisa menonaktifkan akun anda sendiri";}
		$user = User::findOrFail($id);
		$user->verifier = false;
		$user->save();
		return redirect()->route('admin.users.view')->with('message', "{$user->nama} set sebagai verifier");
	}
}
