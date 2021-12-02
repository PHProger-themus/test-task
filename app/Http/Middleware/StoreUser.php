<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $is_admin = Auth::check() && Auth::user()->role == 1;
        if (!Auth::check() || $is_admin) {
            if ($is_admin) {
                define("IS_ADMIN", true);
            }
            return $next($request);
        } else {
            return back();
        }
    }
}
