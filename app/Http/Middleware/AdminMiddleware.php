<?php

namespace App\Http\Middleware;


use Closure;
use Response;
class AdminMiddleware
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
        
        if ($request->user() && $request->user()->user_role != 2){
            // return response()->json(['error' => 'Unauthorized.'], 403);
            // return Response::view('hello')->header('Content-Type', $type);
            return Response(view('unauthorized')->with('role', 'PRINCIPAL'));
        }
        return $next($request);
    }
}
