<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Models\Settings;
use App\Models\Countrysupported;
use Session;

use Auth;
class Country
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
        if(Auth::guard('user')->user()->pay_support==null){
            return redirect()->route('update.support.country');
        }else{
            return $next($request);
        }
    }
}
