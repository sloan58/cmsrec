<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ComesFromLocalhost
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
        if(request()->ip() === '127.0.0.1') {
            return $next($request);
        }
        logger()->error('Received node-fs-watcher request from outside 127.0.0.1', [
            'from_address' => request()->ip()
        ]);
        return abort(401);
    }
}
