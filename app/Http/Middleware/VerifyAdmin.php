<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class VerifyAdmin
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
        if(Auth::user()){

            if ((Auth::user()->role == 1) && (Auth::user()->status == 1)) {
              
                return $next($request);

            } elseif ((Auth::user()->role > 1 && Auth::user()->role <= 4 ) && (Auth::user()->status == 1)) {
              
                return redirect('/')->with('error','You are not authorized!!');
            }

            return redirect()->guest('/admin/login');

            // if (Auth::user()->role == '1' && Auth::user()->status == '1') {
            //     return $next($request);
            // }

            // Auth::logout();
            // return redirect('/admin/login');

        }else{
            return redirect('/admin/login');
        }

    }
}
