<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if(!$request->expectsJson()){
            if($request->routeIs('admin.*')){
                return route('admin.ad.login');
            }elseif($request->routeIs('lecturer.*')){
                return route('lecturer.lect.login');
            }

        }
        return route('user.user.login');

        // return $request->expectsJson() ? null : route('login');
    }
}
