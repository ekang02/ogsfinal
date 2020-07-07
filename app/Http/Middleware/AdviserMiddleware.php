<?php

namespace App\Http\Middleware;

use Closure;

class AdviserMiddleware
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
        if ($request->user() && $request->user()->user_role != 3){
            return new Response(view('unauthorized')->with('role', 'ADVISER'));
        }
        return $next($request);
    }
}
