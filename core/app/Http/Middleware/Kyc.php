<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Models\Settings;
use App\Models\Compliance;

use Auth;
class Kyc
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
        $com = Compliance::whereuser_id(Auth::guard('user')->user()->id)->first();
        if($set->kyc_restriction==1){
            if($com->status==0 || $com->status==1 || $com->status==3)
            {
                return redirect()->route('user.no-kyc');
            }else{
                return $next($request);
            }
        }else{
            return $next($request);
        }
    }
}
