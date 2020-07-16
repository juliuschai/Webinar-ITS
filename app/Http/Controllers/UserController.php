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
		return redirect('/auth/check');
	}

	function logout(Request $request) {
		OIDCHelper::logout();
		$request->session()->save();
		return redirect('/');
	}

	function checkLoggingIn(Request $request) {
		if (empty($request->all())) {
			return view('welcome');
		} else {
			// redirect and forward parameters to OIDCLogin
			$retRoute = redirect()->route('OIDCLogin');
			foreach($request->all() as $key => $value) {
				$retRoute = $retRoute->with($key, $value);
			}
			return $retRoute;
		}
	}
}
