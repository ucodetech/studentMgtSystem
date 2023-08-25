<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsLockedOut
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::guard('admin')->check()){
            if(auth('admin')->user()->locked_out == 1){
                return redirect()->route('locked.out')->with('fail', 'Locked Out');
            }
            return $next($request);
        }else if(Auth::guard('lecturer')->check()){
            if(auth('lecturer')->user()->locked_out == 1){
                return redirect()->route('locked.out')->with('fail', 'Locked Out');
            }
            return $next($request);
        }else{
            if(auth()->user()->locked_out == 1){
                return redirect()->route('locked.out')->with('fail', 'Locked Out');
            }
            return $next($request);
        }
        
    }
}
