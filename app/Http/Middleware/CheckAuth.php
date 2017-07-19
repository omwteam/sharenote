<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAuth
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
        if (!Auth::check()) {
            return redirect(route('login'));
        }
        if (Auth::user()->auth != 2 ) {
            return redirect(route('prompt'))->with(['message'=>'没有权限进入！','url' =>'/', 'jumpTime'=>2,'status'=>false]);
        }
        return $next($request);
    }
}
