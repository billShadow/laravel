<?php
namespace App\Libs;
/**
 *
 * 阿里云短信类
 *
 */

class SendCode
{
    private $AppKey = '';
    private $AppSecret = '';
    private $AppFormat = 'json';
    private $AliTop;

    /**
     * 构造函数
     */
    public function __construct()
    {
    }

    public static function test()
    {
        $iClientProfile = DefaultProfile::getProfile("cn-hangzhou", "LTAI47sRFty7wgVd", "ANY2S2FijfA8DNiK3eMlr79fF30rnH");
        $client = new DefaultAcsClient($iClientProfile);
        $request = new Sms\SingleSendSmsRequest();
        $request->setSignName("测试项目");/*签名名称*/
        $request->setTemplateCode("SMS_45665113");/*模板code*/
        $request->setRecNum("15101048253");/*目标手机号*/
        $request->setParamString("{\"code\":\"1234\"}");/*模板变量，数字一定要转换为字符串*/
        try {
            $response = $client->getAcsResponse($request);
            print_r($response);
        }
        catch (ClientException  $e) {
            print_r($e->getErrorCode());
            print_r($e->getErrorMessage());
        }
        catch (ServerException  $e) {
            print_r($e->getErrorCode());
            print_r($e->getErrorMessage());
        }

    }

    /**
     * 阿里云 短信验证
     * @param $phone    手机号码
     * @param null $mobile_code     验证码
     * @param null $template_code   短信模版
     */
    public function phoneCode($phone,$mobile_code=null,$template_code='SMS_48610197'){
        if (!$mobile_code){$mobile_code = $this->random(6,1);}
        if(!$template_code){$template_code='SMS_48610197';}
        $target = "https://sms.aliyuncs.com/?";
        // 注意使用GMT时间
        date_default_timezone_set("GMT");
        $dateTimeFormat = 'Y-m-d\TH:i:s\Z'; // ISO8601规范
        $accessKeyId = 'LTAIuUXtDRmZVDN8';      // 这里填写您的Access Key ID
        $accessKeySecret = 'Tkqzn9J3UG9nnoavgHPXqWptL3eDOW';  // 这里填写您的Access Key Secret
        $ParamString="{\"code\":\"".strval($mobile_code)."\",\"time\":\"15\"}";
        $data = array(
            // 公共参数
            'SignName'=>'渠道圈',
            'Format' => 'XML',
            'Version' => '2016-09-27',
            'AccessKeyId' => $accessKeyId,
            'SignatureVersion' => '1.0',
            'SignatureMethod' => 'HMAC-SHA1',
            'SignatureNonce'=> uniqid(),
            'Timestamp' => date($dateTimeFormat),
            // 接口参数
            'Action' => 'SingleSendSms',
            'TemplateCode' => $template_code,
            'RecNum' => $phone,
            'ParamString' => $ParamString
        );
        // 计算签名并把签名结果加入请求参数
        //echo $data['Version']."<br>";
        //echo $data['Timestamp']."<br>";
        $data['Signature'] = $this->computeSignature($data, $accessKeySecret);
        // 发送请求
        return $result = $this->xml_to_array($this->https_request($target.http_build_query($data)));

    }
    public function https_request($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {return 'ERROR '.curl_error($curl);}
        curl_close($curl);
        return $data;
    }
    public function xml_to_array($xml){
        $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
        if(preg_match_all($reg, $xml, $matches)){
            $count = count($matches[0]);
            for($i = 0; $i < $count; $i++){
                $subxml= $matches[2][$i];
                $key = $matches[1][$i];
                if(preg_match( $reg, $subxml )){
                    $arr[$key] = $this->xml_to_array( $subxml );
                }else{
                    $arr[$key] = $subxml;
                }
            }
        }
        return @$arr;
    }
    public function random($length = 6 , $numeric = 0) {
        PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
        if($numeric) {
            $hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
        } else {
            $hash = '';
            /* $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';*/
            $chars = '0123456789';
            $max = strlen($chars) - 1;
            for($i = 0; $i < $length; $i++) {
                $hash .= $chars[mt_rand(0, $max)];
            }
        }
        return $hash;
    }
    public function percentEncode($str)
    {
        // 使用urlencode编码后，将"+","*","%7E"做替换即满足ECS API规定的编码规范
        $res = urlencode($str);
        $res = preg_replace('/\+/', '%20', $res);
        $res = preg_replace('/\*/', '%2A', $res);
        $res = preg_replace('/%7E/', '~', $res);
        return $res;
    }


    public function computeSignature($parameters, $accessKeySecret)
    {
        // 将参数Key按字典顺序排序
        ksort($parameters);
        // 生成规范化请求字符串
        $canonicalizedQueryString = '';
        foreach($parameters as $key => $value)
        {
            $canonicalizedQueryString .= '&' . $this->percentEncode($key)
                . '=' . $this->percentEncode($value);
        }
        // 生成用于计算签名的字符串 stringToSign
        $stringToSign = 'GET&%2F&' . $this->percentencode(substr($canonicalizedQueryString, 1));
        //echo "<br>".$stringToSign."<br>";
        // 计算签名，注意accessKeySecret后面要加上字符'&'
        $signature = base64_encode(hash_hmac('sha1', $stringToSign, $accessKeySecret . '&', true));
        return $signature;
    }
}