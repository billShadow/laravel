<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckApiToken
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
        // 获取jwt对象
        $token_request = JWTAuth::setRequest($request);

        // 获取header头中的 Authorization值
        $token = JWTAuth::getToken();
        if (empty($token)) {
            fun_respon(0, '缺少授权');
        }
        // 解码token
        try{
            $claims = $token_request->parseToken()->getPayload()->toArray();
        } catch (\Exception $ex) {
            fun_respon(0, '授权无效');
        }
        if (isset($claims['exp']) && $claims['exp'] < time()) {
            fun_respon(0, '授权已过期');
        }

        if (isset($claims['uid']) && $claims['uid']) {
            $request->offsetSet('validate_id', $claims['uid']);
            $request->offsetSet('validate_phone', $claims['phone']);
        } else {
            fun_respon(0, '授权无效');
        }
        return $next($request);
    }
}
