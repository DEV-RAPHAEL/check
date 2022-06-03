<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Models\Settings;
use App\Models\Countrysupported;
use Session;

use Auth;
class Ban
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
        $set=Settings::find(1);
        $cc=Countrysupported::whereid(Auth::guard('user')->user()->pay_support)->wherestatus(1)->count();
        if($cc==0){
            Auth::guard('user')->logout();
            return redirect()->route('login')->with('alert', 'Your country is not supported');
        }else{
            return $next($request);
        }
    }
}
