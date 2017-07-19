<?php

namespace App\Http\Middleware;

use Closure;

class CheckHost
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
        $host = ['172.28.2.228','stip.omwteam.com'];
        if (env('APP_ENV') === 'production' && !in_array($request->getHost(),$host)) {
            return redirect(route('prompt'))->with(['message'=>'没有权限操作相关资源！','url' =>'/', 'jumpTime'=>2,'status'=>false]);
        }
        return $next($request);
    }
}
