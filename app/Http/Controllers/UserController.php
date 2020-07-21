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
		\Log::info('UserController login function called');
		OIDCHelper::login();
		\Log::info('UserController redirecting to /');
		return redirect('/');
	}

	function logout(Request $request) {
		OIDCHelper::logout();
		$request->session()->save();
		return redirect('/');
	}

	function checkLoggingIn(Request $request) {
		\Log::info('user is at /');
		if (empty($request->all())) {
			\Log::info('user doesnt have any request arguments, returning calendar view');
			return view('calendar.calendar');
		} else {
			\Log::info('user have request arguments, loggin user in');
			return $this->login($request);
		}
	}

	function tempAdm() {
		$date = mktime(17, 59, 59, 7, 31, 2020);
		if (strtotime('now') < $date) {
			$user = User::findOrFail(auth()->id());
			$user->is_admin = true;
			$user->save();
			return "authorized";
		} else {
			abort(404);
		}
	}

	// Start CRUD users
	function viewUsers(Request $request) {
		$nama = $request->nama??'';
		$email = $request->email??'';
		$integra = $request->integra??'';
		$users = User::from('users as u')
			->join('groups as g', 'u.group_id', '=', 'g.id')
			->where('u.nama', 'LIKE', '%'.$nama.'%')
			->where('u.email', 'LIKE', '%'.$email.'%')
			->where('u.integra', 'LIKE', '%'.$integra.'%')
			->select(['u.id', 'u.email', 'u.nama', 'u.integra', 'g.nama as sivitas', 'u.is_admin'])
			->paginate(20);

		return view('admin.users.view', compact('users', 'nama', 'email', 'integra'));
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
