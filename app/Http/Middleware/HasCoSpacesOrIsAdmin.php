<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasCoSpacesOrIsAdmin
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
        if(auth()->user()->isAdmin() || auth()->user()->cmsCoSpaces()->count()) {
            return $next($request);
        }

        auth()->logout();
        return redirect()->route('home')->with('error','Permission Denied!!! You do not have administrative access.');
    }
}
