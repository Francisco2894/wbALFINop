<?php

namespace wbALFINop\Http\Middleware;

use Closure;
use Auth;

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
        if (auth()->check()){
            if (auth()->user()->status == 0) {
                Auth::logout();
                return redirect('/');
            }
            return $next($request);
        }
        return redirect('/');
    }
}
