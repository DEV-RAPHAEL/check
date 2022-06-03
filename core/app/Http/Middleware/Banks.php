<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Models\Bank;

use Auth;
class Banks
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
        $bank=Bank::whereuser_id(Auth::guard('user')->user()->id)->get();
        if(count($bank)>0)
        {
            return $next($request);
        }else{
            return redirect()->route('user.nobank');
        }

    }
}
