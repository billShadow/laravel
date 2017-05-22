<?php
use App\Libs\ErrorCode;
/**
 * 全局自定义函数
 */

 if (! function_exists('get_time')) {
    /**
     * Flatten a multi-dimensional array into a single level.
     *
     * @param  array  $array
     * @param  int  $depth
     * @return array
     */
    function get_time()
    {
        echo time();
    }
}

if (! function_exists('mkDirs')) {
    /**
     * 递归创建目录
     */
    function mkDirs($dir)
    {
        if ( !is_dir($dir) ) {
            if ( !mkDirs(dirname($dir)) ) {
                return false;
            }
            if ( !mkdir($dir,0777) ) {
                return false;
            }
        }
        return true;
    }
}

if (! function_exists('fun_respon')) {

    /**
     *  return json maxed
     */
    function fun_respon($success, $res = [], $code = 200)
    {
        $result['result'] = $success;

        if ($success == 400) {
            $result['result'] = 400;
            $result['msg'] = $res;
            $result['code'] = $code;
        } elseif ($success == 1) {
            $result['result'] = 1;
            $result['msg'] = '操作成功';
            $result['data'] = $res;
            $result['code'] = 200;
        } else {
            $res = ErrorCode::msg($code);
            $result['result'] = 0;
            $result['msg'] = $res;
            $result['code'] = $code;
        }
        //header("Content-Type: application/json; charset=UTF-8");
        exit(json_encode($result));
    }
}

if (! function_exists('ajax_respon')) {
    /**
     *  return json maxed
     */
    function ajax_respon($success, $res = [], $code = 200)
    {
        $result['result'] = $success;

        if ($success == 400) {
            $result['result'] = 400;
            $result['msg'] = $res;
            $result['code'] = $code;
        } elseif ($success == 1) {
            $result['result'] = 1;
            $result['msg'] = '操作成功';
            $result['data'] = $res;
            $result['code'] = 200;
        } else {
            $result['result'] = 0;
            $result['msg'] = $res;
            $result['code'] = $code;
        }
        header("Content-Type: application/json; charset=UTF-8");
        exit(json_encode($result));
    }
}

if (!function_exists('fun_img')) {
    /**
     * 拼接系统内图片url
     * @param $img
     * @return string
     */
    function fun_img($img)
    {
        if (empty($img)) {
            return '';
        } elseif(strpos(strtolower($img), 'http://') === 0 || strpos(strtolower($img), 'https://') === 0 ) {
            return $img;
        } else {
            //return env('CDN_URL').$img;
            return 'https://minappcdn.mcdonalds.com.cn'.$img;
        }
    }
}

if (! function_exists('fun_respon_head')) {
    /**
     *  return json maxed
     */
    function fun_respon_head($success, $res = [], $code = 200)
    {
        $result['result'] = $success;

        if ($success) {
            $result['result'] = 1;
            $result['msg'] = '操作成功';
            $result['data'] = $res;
            $result['code'] = 200;
        } else {
            $result['result'] = 0;
            $result['msg'] = $res;
            $result['code'] = $code;
        }
        header("Content-Type: application/json; charset=UTF-8");
        exit(json_encode($result));
    }
}

if (!function_exists('fun_curl')) {
    /**
     * json curl request
     * @param $url
     * @param $data
     * @param $token
     * @return mixed
     */
    function fun_curl($url,$data){
        $ch = curl_init();
        //print_r($ch);
        curl_setopt( $ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60); //设置超时

        if(0 === strpos(strtolower($url), 'https'))
        {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); //对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //从证书中检查SSL加密算法是否存在
        }
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        //curl_setopt( $ch, CURLOPT_HTTPHEADER, ['Content-Type:application/x-www-form-urlencoded'] );
        $return = curl_exec ( $ch );
        curl_close ( $ch );
        return $return;
    }

}

if (!function_exists('fun_curl_get')) {
    /**
     * json curl request
     * @param $url
     * @param $data
     * @param $token
     * @return mixed
     */
    function fun_curl_get($url){
        $ch = curl_init();
        //print_r($ch);
        curl_setopt( $ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60); //设置超时

        if(0 === strpos(strtolower($url), 'https'))
        {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); //对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //从证书中检查SSL加密算法是否存在
        }
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        $return = curl_exec ( $ch );
        curl_close ( $ch );
        return $return;
    }

}

if (!function_exists('fun_error_view')) {
    /**
     * json curl request
     * @param $url
     * @param $data
     * @param $token
     * @return mixed
     */
    function fun_error_view($code, $content, $url){
        if ($code == 0) {
            return view('admin.error')->with('info', ['error'=>$content, 'url'=>$url]);
        } else {
            return view('admin.error')->with('info', ['success'=>$content, 'url'=>$url]);
        }
    }

}

