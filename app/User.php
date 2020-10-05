<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // 'name', 'email', 'password',
        'group_id', 'email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // 'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime',
    ];

    static function viewUserBuilder() {
        return User::join('groups', 'group_id', '=', 'groups.id')
            ->select([
                'users.id', 'email', 'users.nama',
                'integra', 'groups.nama as sivitas', 'is_admin', 'verifier'
            ]);
    }

    static function getAdminDPTSIDropdownOptions() {
        return User::where('verifier', true)
            ->get();
    }

    function checkId($id) {
        return $this->id == $id;
    }
    
    function isAdmin() {
        return $this['is_admin'];
    }

    function isOwner($id) {
        return $this['id'] == $id;
    }

    /**
     * This is to handle if there's an error with 
     * the currently logged in user credentials
     */
    static function findOrLogout($id) {
        $user = User::find($id);
        if ($user == null) {
            return redirect()->route('logout');
        } else {
            return $user;
        }
    }
}
