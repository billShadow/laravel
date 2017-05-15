<?php

namespace App\Http\Controllers\Api;

use App\Libs\pay;
use App\Libs\SendCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use OSS\OssClient;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
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

    // 上传文件到本地 例如图片
    public function uploadfile(Request $request)
    {
        $img_url = $request->file('images');
        $ext = strtolower($img_url->getClientOriginalExtension());     // 扩展名
        if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
            fun_respon(0, '上传图片格式错误', 'addbanner');
        }
        if (isset($_FILES['images']['size']) && $_FILES['images']['size'] >= 8*1024*1024) {
            fun_respon(0, '上传图片大小不得超过8M', 'addbanner');
        }
        $video_tem_img = 'image_'.str_random(16) . '.'.$ext;
        $put_result = Storage::disk('uploadimg')->put(
            $video_tem_img,
            file_get_contents($img_url->getRealPath()),
            'public'
        );
        var_dump($put_result);
    }

    // 创建二维码
    public function creatercode()
    {
        $filename = 'qrcode_'.str_random(20).'.png';

        $res = QrCode::format('png')->merge(storage_path('app/images/').'0.jpg', .25, true)->size(400)->margin(1)->errorCorrection('M')->generate('Make me into a QrCode!', storage_path('app/images/').$filename);
        var_dump($res);
    }

    // 发送验证码  阿里云短信发送
    public function sendcode()
    {
        $smscode = new SendCode();
        $res = $smscode->phoneCode('15101048253', '6666');
        var_dump($res);
    }

    // 简单的接口签名认证
    public function apiSign()
    {
        $timestamp = time();
        $random = str_random(10);
        $key = 'mcds!@#$%^';
        $sign = md5(md5($key).md5($timestamp.$random));
        self::apiCheckSign($timestamp, $random, $sign);
    }

    public function apiCheckSign($timestamp, $random, $sign)
    {
        // 可以先根据时间戳判断签名是否过期  自己定义即可
        if (time() > ($timestamp+300)) { // 签名五分钟失效
            fun_respon(0, '签名已过期');
        }
        $local_sign = md5(md5('mcds!@#$%^').md5($timestamp.$random));
        if ($local_sign != $sign) {
            fun_respon(0, '签名错误');
        }
        fun_respon(1, "认证通过");
    }






}
