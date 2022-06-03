<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;

use Auth;
class Blocked
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

        if(Auth::guard('user')->user()->status == 0)
        {
            return $next($request);
        }else{
            return redirect()->route('user.blocked');
        }

    }
}
