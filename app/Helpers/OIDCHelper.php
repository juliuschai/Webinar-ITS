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
				'https://dev-my.its.ac.id', // authorization_endpoint
				env('OIDC_CLIENT_ID'), // Client ID
				env('OIDC_CLIENT_SECRET') // Client Secret
			);
			$oidc->setRedirectURL(env('OIDC_LOGIN_REDIRECT')); // must be the same as you registered
			$oidc->addScope('email group profile role openid'); //must be the same as you registered

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
		if (!session()->has('id_token')) {
			$oidc = OIDCHelper::OIDLogin();
			$attr = $oidc->requestUserInfo();

			// check if there is a user with role that's not empty array
			if ($attr->role != []) { 
				\Log::info('new Attribute role: '.$attr->role);
			}

			$user = User::firstOrNew([
				'email' => $attr->email,
			]);
			$user->nama = $attr->name;
			$user->integra = $attr->reg_id;
			$groupStr = OIDCHelper::groupToString($attr->group);
			$user->group_id = Group::findOrCreateGetId($groupStr);
			$user->save();

			Auth::login($user);
		}
	}

	static function logout() {
		try {
			if (session()->has('id_token')) {
				Auth::logout();
				$accessToken = session('id_token');
				session()->forget('id_token');
				session()->save();

				$oidc = new OpenIDConnectClient(
					'https://dev-my.its.ac.id', // authorization_endpoint
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
