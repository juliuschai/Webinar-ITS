<?php

namespace App\Http\Middleware;

use App\Booking;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class AdminOrOwnerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $booking = Booking::findOrFail($request->bookingId);
        $user = User::findOrLogout(Auth::id());
        if(!($user->isAdmin() || $user->isOwner($booking->user_id))) {abort(403);}
        $request->merge(compact('booking'));
        return $next($request);
    }
}
