<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Its\Sso\OpenIDConnectClient;
use Its\Sso\OpenIDConnectClientException;

class UserController extends Controller
{
    function testGet(Request $request) {
        $oidc = new OpenIDConnectClient(
            'https://dev-my.its.ac.id', // authorization_endpoint
            '09907113-2F43-414E-89C4-7EB763ED6A8B', // Client ID
            '09907113-2F43-414E-89C4-7EB763ED6A8B' // Client Secret
        );

        $oidc->setRedirectURL('http://webinar.itslocal.com'); // must be the same as you registered
        $oidc->addScope('openid code phone profile'); //must be the same as you registered

        // remove this if in production mode
        $oidc->setVerifyHost(false);
        $oidc->setVerifyPeer(false);

        $oidc->authenticate(); //call the main function of myITS SSO login

        $_SESSION['id_token'] = $oidc->getIdToken(); // must be save for check session dan logout proccess
    }
}
