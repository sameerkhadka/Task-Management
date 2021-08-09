<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyIfAdmin
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
        if(!$request->user()->isAdmin()){
            abort(403, 'Permission Denied!!! You do not have administrative access.');
        }
        return $next($request);
    }
}
