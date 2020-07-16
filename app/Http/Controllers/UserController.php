<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\OIDCHelper;

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
}
