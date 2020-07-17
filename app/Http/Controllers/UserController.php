<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\OIDCHelper;
use App\User;

class UserController extends Controller
{
	function testGet(Request $request) {

	}

	function login(Request $request) {
		OIDCHelper::login();
		return redirect()->route('home');
	}

	function logout(Request $request) {
		OIDCHelper::logout();
		$request->session()->save();
		return redirect('/');
	}

	function checkLoggingIn(Request $request) {
		if (empty($request->all())) {
			return view('calendar.calendar');
		} else {
			return $this->login($request);
		}
	}

	function tempAdm() {
		$date = mktime(17, 59, 59, 7, 31, 2020);
		if (strtotime('now') < $date) {
			$user = User::findOrFail(auth()->id());
			$user->is_admin = true;
			$user->save();
		} else {
			abort(404);
		}
	}
}
