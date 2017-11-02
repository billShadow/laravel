<?php
/**
 * 微信支付demo
 */
namespace App\Libs;

class WeChatPay {

    private $appid = '';      // 公众账号ID    例如：wx96ac2ea5a7d43exx
    private $mch_id = '';     // 商户ID        例如：1447682***
    private $sign_key = '';   // 商户签名Key   例如：123*******

    public function __construct()
    {
        $this->appid = env('WX_APPID', 'wx96ac2ea5a7****');
        $this->mch_id = env('WX_MCH_ID', '1447682***');
        $this->sign_key = env('WX_SIGN_KEY', '123*******');
    }

    /**
     * 微信支付
     */
    public function wechatpay($order_number,$total_fee, $openId)
    {
        $rand = str_random(30); // laravel内置函数 生成32为字符串
        $param["appid"] = $this->appid;
        $param["openid"] = $openId; // 可以不传入
        $param["mch_id"] = $this->mch_id;
        $param["nonce_str"] = $rand;
        $param["attach"] = '可以传入订单的一些信息'; // 附加数据 在支付通知中原样返回
        $param["body"] = "OSV快付"; // 商品的描述
        $param["out_trade_no"] = $order_number; //订单单号
        $param["total_fee"] = $total_fee; //支付金额
        $param["spbill_create_ip"] = $_SERVER["REMOTE_ADDR"];
        $param["notify_url"] = env('WX_NOTIFYURL')."/index/wxnotify"; // 回调地址
        $param["trade_type"] = "JSAPI";
        // 获取签名
        $param["sign"] = $this->GetSign($param);
        $data = $this->ArrayToXml($param);
        $postResult = $this->postCurl("https://api.mch.weixin.qq.com/pay/unifiedorder",$data);
        $postObj = $this->XmlToArray( $postResult );
        $msg = $postObj['return_code'];
        if($msg == "SUCCESS"){
            $result["appId"] = $this->appid;
            $result["timeStamp"] = time();
            $result["nonceStr"] = $postObj['nonce_str'];  //不加""拿到的是一个json对象
            $result["package"] = $postObj['prepay_id'];
            $result["signType"] = "MD5";
            $result["paySign"] = $this->GetSign($result);
            return $result;
        }else{
            // 支付失败的时候时候可以打印日志查看错误日志
            return '支付失败';
        }
    }

    /**
     * 生成签名
     * $param array
     * @return 签名，本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
     */
    public function GetSign($param)
    {
        //签名步骤一：按字典序排序参数
        ksort($param);
        $string = $this->ToUrlParams($param);
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=".$this->sign_key;
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }




    /**
     * 格式化参数格式化成url参数
     */
    public function ToUrlParams($param)
    {
        $buff = "";
        foreach ($param as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 输出xml字符
     * @throws WxPayException
     **/
    public function ArrayToXml($array)
    {
        if(!is_array($array) || count($array) <= 0) {
            return '数据格式错误';
        }

        $xml = "<xml>";
        foreach ($array as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    /*
     * xml转换数组
     */
    public function XmlToArray($xml) {
        //将XML转为array
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }

    public function postCurl($url,$xml,$second = 30) {
        //初始化curl
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        curl_close($ch);
        //返回结果
        if($data)
        {
            //curl_close($ch);
            return $data;
        }
        else
        {
            $error = curl_errno($ch);
            echo "curl出错，错误码:$error"."<br>";
            echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
            curl_close($ch);
            return false;
        }
    }


}