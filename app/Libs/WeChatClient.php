<?php
/**
 * 微信小程序接口类
 *  app id    wx51fa2f9eabf66605
 *  secert:   1d3e10ce3ea7da269e10f7805564d2c9
 */

namespace App\Libs;

use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Redis;
class WeChatClient {
    private $appid = '';        // 公众号appid
    private $secrect = '';      // 公众号appsecret
    private $accessToken;       // 微信接口调用凭证 token

    private  $WX_PAY_URL = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
    private  $WX_TRANSFER_URL = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers'; //企业付款接口（提现用到）
    private  $WX_REFUND_URL = 'https://api.mch.weixin.qq.com/secapi/pay/refund'; // 退款接口
    private  $WX_ORDER_QUERY = 'https://api.mch.weixin.qq.com/pay/orderquery'; // 订单查询接口
    private  $TURN_MCH_APPID = '';  // 退款公众号  --  【如果是同一个公众就是和上面的appid是相同的】
    private  $TURN_MCHID = '';      // 退款的商户号
    private  $TURN_KEY = '';        // 商户号的apikey



    public function __construct()
    {
        $this->appid = env('WX_APPID');
        $this->secrect= env('WX_APPSECRET');
        $this->TURN_MCH_APPID = env('WX_APPID');
        $this->TURN_MCHID =  env('WX_MCHID');
        $this->TURN_KEY = env('WX_KEY');
        $this->accessToken = $this->getToken();

    }

    /**
     * @param $appid
     * @param $appsecret
     * @return mixed
     * 获取token
     */
    public function getToken()
    {
        $access_token = Redis::get('mole_access_token');
        if (!$access_token) {
            // 如果是企业号用以下URL获取access_token
            //$url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appid&secret=$this->secrect";
            $res = json_decode($this->httpGet($url));
            $access_token = $res->access_token;
            if ($access_token) {
                Redis::setex('mole_access_token', 7000, $access_token);
            }
        } else {
            return $access_token;
        }
        return $access_token;
    }


    /**
     * 获取JSSDK-web界面的包
     * @return array
     */
    public function getSignPackage()
    {
        $jsapiTicket = $this->getJsApiTicket();
        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId"     => $this->appid,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    /**
     *
     * @param $touser
     * @param $template_id
     * @param $url
     * @param $data
     * @param string $topcolor
     * @return bool|mixed
     */
    public function doSend($touser, $template_id,$url, $data, $topcolor = '#173177')
    {
        $template = array(
            'touser' => $touser,
            'template_id' => $template_id,
            'url' => $url,
            'topcolor' => $topcolor,
            'data' => $data
        );
        $json_template = json_encode($template);
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $this->LMaccessToken;
        $dataRes = $this->httpRequest($url, urldecode($json_template));
        $dataRes = json_decode($dataRes,true);
        return $dataRes;
    }

    /**
     * 推送消息
     * @param $touser  openid
     * @param $template_id  模板id
     * @param string $page  点击模板跳转地址
     * @param $data         模板数据
     * @param $formId
     * @param string $color 字体颜色
     * @return mixed
     */
    public function sendTemplate($touser,$template_id,$page = 'pages/integrals/integrals',$data,$formId,$color = '#173177')
    {
        $template = array(
            'touser' => $touser,
            'template_id' => $template_id,
            'page' => $page,
            'form_id' => $formId,
            'data' => $data,
        );
        $json_template = json_encode($template);
        $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=" . $this->accessToken;
        $dataRes = Curl::to($url)
            ->withData(urldecode($json_template))
            ->post();
        $dataRes = json_decode($dataRes,true);
        return $dataRes;
    }

    /**
     * 获取jsapi_ticket
     * @return mixed
     * 获取ticket
     */
    private function getJsApiTicket()
    {
        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        $ticketstr = Redis::get('mole_jsapi_ticket');
        if (empty($ticketstr))
        {
            $accessToken = $this->accessToken;
            // 如果是企业号用以下 URL 获取 ticket
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
            $res = json_decode($this->httpGet($url));
            $ticket = $res->ticket;
            if ($ticket) {
                Redis::setex('mole_jsapi_ticket',7200,$ticket);
            }
            return $ticket;
        } else {
            return $ticketstr;
        }
    }

    /**
     * 微信支付封装
     * @param $openid
     * @param $attach 商品属性
     * @param $body_cont 支付显示body
     * @param $order 订单信息
     * @param $route 回调路由
     * @return mixed
     */
    public function WxPay($openid, $attach, $body_cont, $order, $route)
    {
        $param["appid"] = $this->appid;
        $param["openid"] = $openid;
        $param["mch_id"] = $this->TURN_MCHID;
        $param["nonce_str"] = $this->createNonceStr(16);
        $param["attach"] = json_encode($attach);
        $param["body"] = $body_cont;
        $param["out_trade_no"] = $order['order_number']; //订单单号
        $param["total_fee"] = $order['pay_money']*100;//支付金额
        $param["spbill_create_ip"] = $_SERVER["REMOTE_ADDR"];
        $param["notify_url"] = $_SERVER['HTTP_HOST'].$route;//回调
        $param["trade_type"] = "JSAPI";
        ksort($param);
        $signStr = $this->getWxSign($param);
        $param["sign"] = strtoupper(MD5($signStr));
        $data = $this->arrayToXml($param);
        $postResult = $this->httpRequest($this->WX_PAY_URL,$data);
        $postObj = $this->xmlToArray( $postResult );
        $msg = $postObj['return_code'];
        if($msg == "SUCCESS"){
            $result["timeStamp"] = time();
            $result["nonceStr"] = $postObj['nonce_str'];  //不加""拿到的是一个json对象
            $result["package"] = "prepay_id=".$postObj['prepay_id'];
            $result["signType"] = "MD5";
            $result['appId'] = env('WX_APPID');
            ksort($result);
            $paySignStr = $this->getWxSign($result);
            $result["paySign"] = strtoupper(MD5($paySignStr));
            return ['code'=>200, 'msg'=>$result];
        } else {
            return ['code'=>400, 'msg'=>$result];
        }
    }




    /**
     * 企业付款，微信转账接口
     * @param $data
     * @return mixed
     */
    public function WxTransfer($data)
    {
        $data['mch_appid'] = $this->TURN_MCH_APPID;
        $data['mchid'] = $this->TURN_MCHID;
        $data['spbill_create_ip'] = Util::get_client_ip();
        ksort($data);
        $signStr = $this->getWxSign( $data );
        $data["sign"] = strtoupper(MD5($signStr));
        $xml = $this->arrayToXml($data);
        $res = $this->weachatPostPemCurl($this->WX_TRANSFER_URL,$xml);
        $res = $this->xmlToArray($res);
        return $res;
    }

    /**
     * 获取退款的时候需要的签名
     * @param $arr
     * @return string
     */
    public function getWxSign($arr)
    {
        $buff = "";
        foreach ($arr as $k => $v)
        {
            if($k != "sign"){
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        $str = $buff."&key=$this->TURN_KEY";
        return $str;
    }


    /**
     * @param $arr array() 商户系统内部的退款单号--订单金额--退款金额
     * 微信退款
     */
    public function refund( $arr = array() )
    {
        if( !isset($arr['orderNumber']) || empty( trim( $arr['orderNumber'] ) ) )
        {
            return false;
        }
        if( !isset($arr['total_fee']) || empty( trim( $arr['total_fee'] ) ) )
        {
            return false;
        }
        $data = array();
        $rand = md5(time() . mt_rand(0,1000));
        $data['appid'] = $this->TURN_MCH_APPID;
        $data['mch_id'] =  $this->TURN_MCHID;
        $data['nonce_str'] = "$rand";
        $data['out_trade_no'] = $arr['orderNumber'] ;
        $data['out_refund_no'] = $arr['orderNumber'] ;
        $data['total_fee'] = $arr['total_fee']*100;
        $data['refund_fee'] = isset( $arr['refund_fee'] ) && $arr['refund_fee'] > 0 ? $arr['refund_fee']*100 : $arr['total_fee']*100;
        ksort($data);
        $signStr = $this->getWxSign( $data );
        $data["sign"] = strtoupper(MD5($signStr));
        $xml = $this->arrayToXml($data);
        $res = $this->weachatPostPemCurl($this->WX_REFUND_URL,$xml);
        $res = $this->xmlToArray($res);
        if( isset($res['result_code']) && $res['result_code'] == 'SUCCESS' )
        {
            return 1;
        }
        return false;
    }

    /**
     * @param array $arr
     * 微信订单查询
     * 参数商户自行生成的唯一订单或微信回掉订单
     */
    public function orderQuery( $arr = array() )
    {
        if( !isset($arr['orderNumber']) || empty( trim( $arr['orderNumber'] ) ) )
        {
            return false;
        }
        $data = array();
        $rand = md5(time() . mt_rand(0,1000));
        $data['appid'] = $this->TURN_MCH_APPID;
        $data['mch_id'] =  $this->TURN_MCHID;
        $data['nonce_str'] = "$rand";
        //$data['out_trade_no'] = $arr['orderNumber'] ;
        $data['transaction_id'] = $arr['orderNumber'] ;
        ksort($data);
        $signStr = $this->getWxSign( $data );
        $data["sign"] = strtoupper(MD5($signStr));
        $xml = $this->arrayToXml($data);
        $res = $this->weachatPostPemCurl($this->WX_ORDER_QUERY,$xml);
        $res = $this->xmlToArray($res);
        return $res;
    }

    /**
     *
     * 通过跳转获取用户信息，跳转流程如下：
     * 1、设置自己需要调回的url及其其他参数，跳转到微信服务器https://open.weixin.qq.com/connect/oauth2/authorize
     * 2、微信服务处理完成之后会跳转回用户redirect_uri地址，此时会带上一些参数，如：code
     *
     * @return 用户的openid
     */
    public function getUserInfo()
    {
        //通过code获得openid
        if (!isset($_GET['code'])){
            //触发微信返回code码
            $baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING']);
            $url = $this->__CreateOauthUrlForCode($baseUrl);
            Header("Location: $url");
            exit();
        } else {
            //获取code码，以获取openid
            $code = $_GET['code'];
            $url = $this->__CreateOauthUrlForOpenid($code);
            $res = $this->__GetCurl($url);
            return $res;
        }
    }

    /**
     *
     * 通过code从工作平台获取openid机器access_token
     * @param string $url 请求的url
     * @param int $curl_timeout 超时时间
     *
     * @return openid
     */
    public function __GetCurl($url, $curl_timeout=60)
    {
        //初始化curl
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $curl_timeout);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //运行curl，结果以jason形式返回
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }



    /**
     *
     * 构造获取code的url连接
     * @param string $redirectUrl 微信服务器回跳的url，需要url编码
     *
     * @return 返回构造好的url
     */
    private function __CreateOauthUrlForCode($redirectUrl)
    {
        $urlObj["appid"] = $this->appid;
        $urlObj["redirect_uri"] = "$redirectUrl";
        $urlObj["response_type"] = "code";
        $urlObj["scope"] = "snsapi_base";
        $urlObj["state"] = "STATE"."#wechat_redirect";
        $bizString = $this->ToUrlParams($urlObj);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
    }

    /**
     *
     * 构造获取open和access_toke的url地址
     * @param string $code，微信跳转带回的code
     *
     * @return 请求的url
     */
    private function __CreateOauthUrlForOpenid($code)
    {
        $urlObj["appid"] = $this->appid;
        $urlObj["secret"] = $this->appsecret;
        $urlObj["code"] = $code;
        $urlObj["grant_type"] = "authorization_code";
        $bizString = $this->ToUrlParams($urlObj);
        return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
    }

    /**
     *
     * 拼接签名字符串
     * @param array $urlObj
     *
     * @return 返回已经拼接好的字符串
     */
    private function ToUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v)
        {
            if($k != "sign"){
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }


    /**
     * 转账或退款需要证书的时候调用的接口
     * @param $url
     * @param $vars
     * @return mixed
     */
    public function weachatPostPemCurl($url, $vars)
    {
        $rs = Curl::to( $url )
            ->withOption('SSL_VERIFYPEER', false)
            ->withOption('SSL_VERIFYHOST', false)
            ->withOption('SSLCERT',app_path('/lib/WeChat/Transfer/cert/apiclient_cert.pem'))
            ->withOption('SSLKEY',app_path('/lib/WeChat/Transfer/cert/apiclient_key.pem'))
            ->withData( $vars )
            ->post();
        return $rs;

    }

    /**
     * 发送get请求
     * @param string $url
     * @return bool|mixed
     */
    public function httpGet($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }
    /**
     * 发送post请求
     * @param string $url
     * @param string $param
     * @return bool|mixed
     */
    public function httpRequest($url, $post, $header = array(), $timeout = 60)
    {
        $ch = curl_init();
        if (strpos($url, 'https://') !== false) {	// HTTPS
            //curl_setopt($ch, CURLOPT_SSLVERSION, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        // 发起链接的超时时间
        //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);

        //使用证书：cert 与 key 分别属于两个.pem文件
        /*curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLCERT, WxPayConfig::SSLCERT_PATH);
        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLKEY, WxPayConfig::SSLKEY_PATH);*/

        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * 数组转XML
     */
    public function arrayToXml($arr) {
        $xml = "<xml>";
        foreach ($arr as $key=>$val) {
            if (is_numeric($val)) {
                $xml.="<".$key.">".$val."</".$key.">";
            } else {
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    /**
     * XML转数组
     */
    public function xmlToArray($xml) {
        //将XML转为array
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }


    /**
     * 生成随机指定长度字符串
     * @param int $length
     * @return string
     */
    public function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

}