<?php

namespace App\Http\Controllers\Admin;

use App\Models\adm_user;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redis;

class LoginController extends Controller
{
    public function login()
    {
        return view('admin/login');
    }

    public function dologin(Request $request)
    {
        $account = trim($request->account);
        $pass = trim($request->passwd);
        if (empty($account)) {
            ajax_respon(0, '账号不能为空', 11001);
        }
        if (empty($pass)) {
            ajax_respon(0, '密码不能为空', 11002);
        }
        $userinfo = adm_user::getOne(['account'=>$account]);
        if (!$userinfo) {
            ajax_respon(0, '账号不存在', 11003);
        }
        if ($pass != $userinfo['pass']) {
            //return fun_error_view(0, '密码错误', 'dologin');
            ajax_respon(0, '密码错误', 11004);
        }
        // 请求成功，将用户数据加入，存入cookie及redis
        $data = [
            'uid' => $userinfo['id'],
            'account' => $userinfo['account'],
            'nickname' => $userinfo['nickname'],
        ];
        $adm_token = Crypt::encrypt(json_encode($data));
        setcookie('adm_token', $adm_token,time()+60*60*4,'/');
        Redis::setex('adm_token_'.$adm_token, 60*60*4, 1);
        ajax_respon(1, '请求成功');
    }

    public function signout()
    {
        //清除用户的数据之后跳转到登录页面
        if (isset($_COOKIE['adm_token']) && $_COOKIE['adm_token']) {
            setcookie('adm_token','',time()-86400,'/');
            Redis::del('adm_token_'.$_COOKIE['adm_token']);
            return redirect('adm/login');
        } else {
            return redirect('adm/login');
        }
    }

    /**
     * 统一解码方法
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     */
    public static function decryptToken()
    {
        $cook = isset($_COOKIE['adm_token']) ? $_COOKIE['adm_token'] : '';
        if (!$cook) {
            return redirect('admin/login');
        }
        return json_decode(Crypt::decrypt($cook), true);
    }
}
