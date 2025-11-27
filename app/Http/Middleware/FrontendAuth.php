<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FrontendAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('token')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        return $next($request);
    }
}

