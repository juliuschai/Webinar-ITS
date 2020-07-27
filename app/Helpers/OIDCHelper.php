<?php

namespace App\Helpers;

use App\Group;
use App\User;
use Illuminate\Support\Facades\Auth;
use Its\Sso\OpenIDConnectClient;
use Its\Sso\OpenIDConnectClientException;

class OIDCHelper extends OpenIDConnectClient {
	static function OIDLogin() {
		try {
			$oidc = new OpenIDConnectClient(
				env('OIDC_PROVIDER'), // authorization_endpoint
				env('OIDC_CLIENT_ID'), // Client ID
				env('OIDC_CLIENT_SECRET') // Client Secret
			);
			$oidc->setRedirectURL(env('OIDC_LOGIN_REDIRECT')); // must be the same as you registered
			$oidc->addScope(env('OIDC_SCOPE')); //must be the same as you registered

			// PROD: Remove
			$oidc->setVerifyHost(false);
			$oidc->setVerifyPeer(false);

			$oidc->authenticate(); //call the main function of myITS SSO login
			session(['id_token' => $oidc->getIdToken()]);
			session()->save();

			return $oidc;
		} catch (OpenIDConnectClientException $e) {
			\Log::error('OIDC login err: '.$e->getMessage());
		}

		return null;
	}

	static function login() {
		// Only run if user is not logged in
		$oidc = OIDCHelper::OIDLogin();
		$attr = $oidc->requestUserInfo();

		$user = User::firstOrNew([
			/* // ToDelete: if email of user is already in database, edit the current user (add 
			sub and no_wa to current user) This only needs to run until all users in db has sub id
			(Because email field was used an identifer before "OpenID sub field update")
			 */
			'email' => $attr->email,
		], [
			// Find user by sub field from OpenID
			'sub' => $attr->sub,
		]);
		// ToDelete:
		$user->sub = $attr->sub;
		
		if (!$attr->email) {
			abort(403, 'Primary Email harus diisi, Update Primary Email dari menu Settings myITS SSO');
		}
		$user->email = $attr->email;
		$user->nama = $attr->name;
		$user->integra = $attr->reg_id;
		if (!$attr->phone) {
			abort(403, 'No. WA harus diisi, Update No. WA dari menu Settings myITS SSO');
		}
		$user->no_wa = $attr->phone;
		$groupStr = OIDCHelper::groupToString($attr->group);
		$user->group_id = Group::findOrCreateGetId($groupStr);
		$user->save();

		Auth::login($user);
	}

	static function logout() {
		try {
			if (session()->has('id_token')) {
				Auth::logout();
				$accessToken = session('id_token');
				session()->forget('id_token');
				session()->save();

				$oidc = new OpenIDConnectClient(
					env('OIDC_PROVIDER'), // authorization_endpoint
					env('OIDC_CLIENT_ID'), // Client ID
					env('OIDC_CLIENT_SECRET') // Client Secret
				);
		
				// PROD: Remove
				$oidc->setVerifyHost(false);
				$oidc->setVerifyPeer(false);

				// Ask if user also wants to quit from myitssso
				$oidc->signOut($accessToken, env('OIDC_LOGOUT_REDIRECT'));
			}

			header("Location: " . env('OIDC_LOGOUT_REDIRECT'));
		} catch (OpenIDConnectClientException $e) {
			\Log::error('OIDC logout err: '.$e->getMessage());
		}
	}

	/**
	 * Function to parse group from requestUserInfo into a
	 * comma seperated imploded string
	 */
	static function groupToString($groups) {
		$group_names = [];
		foreach ($groups as $group) {
			if ($group->group_name == 'Everyone') {
				continue;
			} else {
				$group_names[] = $group->group_name;
			}
		}
		sort($group_names);
		$ret = implode(",", $group_names);
		return $ret;
	}
}
