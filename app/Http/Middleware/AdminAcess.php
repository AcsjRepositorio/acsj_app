<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAcess
{
    
     /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->user_type === \App\Models\User::TYPE_ADMIN) {
            return $next($request);
        }

        return redirect('/')->with('error', 'Access Denied!');
    }
       
}
