<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CanAccessRecording
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
        $cmsRecording = $request->route('cmsRecording');
        if(auth()->user()->isAdmin() || auth()->user()->ownsRecording($cmsRecording)) {
            return $next($request);
        }
        return abort(401);
    }
}
