<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class AdmminToken
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
        if (isset($_COOKIE['adm_token']) && $_COOKIE['adm_token'] && Redis::get('adm_token_'.$_COOKIE['adm_token'])) {
            return $next($request);
        } else {
            return redirect('adm/login');
        }
        return $next($request);
    }
}
