<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

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
            $user_role = Auth::user()->user_role;
            switch ($user_role) {
                case 1:
                    return redirect('/admin');
                  break;
                case 2:
                    return redirect('/principal');
                  break;
                case 3:
                    return redirect('/adviser');
                  break;
                case 4:
                    return redirect('/teacher');
                  break;
                case 5:
                    return redirect('/student');
                  break;
            }
        }

        return $next($request);
    }
}
