<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if (Auth::user()->userTypeId === 1) {
                $user = User::with('store')->get()->find(Auth::user()->id);
                return response()->json(["user"=>$user], 200);
            } else {
                $user = User::with('userInfo')->get()->find(Auth::user()->id);
                return response()->json(["user"=>$user], 200);
            }
            
        }
        return $next($request);
    }
}
