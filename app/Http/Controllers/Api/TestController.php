<?php

namespace App\Http\Controllers\Api;

use App\Libs\pay;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use OSS\OssClient;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

class TestController extends Controller
{
    public function __construct()
    {
        // 指定对应的方法使用路由  也可以直接在路由中指定
        /*$this->middleware('check.apitoken', ['only'=>[
            'gettoken'
        ]]);*/
        //$this->rateLimit(1, 1, ['only' => ['index']]);
    }

    // 利用jwt创建token
    public function index()
    {
        $credentials = [
            'sub' => 'token', // 该键值必填
            'exp' => time()+60,
            'uid' => '123',
            'phone' => '15101048253',
        ];

        $payload = JWTFactory::make($credentials);
        $token = JWTAuth::encode($payload)->__toString();
        var_dump($token); // eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ0b2tlbiIsImV4cCI6MTQ5Njg4ODgwMywidW5pb25pZCI6IjEyMyIsIm5pY2tuYW1lIjoiYmlsbCIsImlzcyI6Imh0dHA6XC9cL2xvY2FsLmJpbGwtbGFyYXZlbC5jb21cL2FwaVwvdGVzdFwvaW5kZXgiLCJpYXQiOjE0OTQyMTA0MDMsIm5iZiI6MTQ5NDIxMDQwMywianRpIjoiRHFIM0VCWkk4aXk2SXJlOSJ9.EK9_Lx4bjqAfUnlwEb8mP3Wzm1st_iUt27QYifLztHU
    }

    // 获取token 并验证token
    public function gettoken(Request $request)
    {
        // 获取jwt对象
        $token_request = JWTAuth::setRequest($request);
        //var_dump($token_request);

        // 获取header头中的 Authorization值
        $token = JWTAuth::getToken();
        if (empty($token)) {
            fun_respon(0, '缺少参数');
        }
        $token = $token->__toString(); // 从对象中取得token值
        //var_dump($token);
        $claims = $token_request->parseToken()->getPayload()->toArray();
        var_dump($claims);
    }

    // 引入类文件
    public function includetest()
    {
        $pay = new pay();
        $res = $pay->index();
        var_dump($res);
    }

    // redis
    public function redistest()
    {
        Redis::set('token', '123');
        $res = Redis::get('token');
        var_dump($res);
    }

    // 七牛云存储文件
    public function savefile(Request $request)
    {
        //$file = $request->file('images_file');
        $accessKey = 'eXtNyeCN7F33eLnA5sTDfbSarYPNmPEPEwzD-Sdo';
        $secretKey = 'rp_ohvn98wrDUjs9J34SOPD3T9p1_uWv2FWrT7pP';
        $bucket = 'billimg';
        // 初始化签权对象
        $auth = new Auth($accessKey, $secretKey);

        $upToken = $auth->uploadToken($bucket);
        $file = $_FILES['images_file'];
        var_dump($_FILES);
        $key = date('Y/m/d/').'image_'.str_random(10).'.png';
        $uploadMgr = new UploadManager();
        $res = $uploadMgr->putFile($upToken, $key, $file['tmp_name']);
        var_dump($res);


    }

    public function test123()
    {
        echo 111;
        var_dump($_FILES);
    }

}
