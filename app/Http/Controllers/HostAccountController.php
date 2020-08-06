<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HostAccount;

class HostAccountController extends Controller
{
    function getAccounts() {
        $hosts = HostAccount::get()->toArray();
        return $hosts;
    }
}
