<?php
namespace App\Libs;
//定义返回问题的类
class ErrorCode{

	static public function msg( $key ){
		$array = array(
			'200'	=>	'请求成功',
			'11001'	=>	'用户id不能为空',
			'11002'	=>	'请重新输入验证码！',
			'11003'	=>	'验证码错误,请重新获取',
			'11004'	=>	'该手机号已注册，请重新注册',
			'11005'	=>	'注册成功!',
			'11006'	=>	'注册失败!',
			'11007'	=>	'验证成功',
			'11008'	=>	'手机号码验证失败',
			'11009'	=>	'验证失败',
			'11010'	=>	'密码必须大于8位',
			'11011'	=>	'两次输入的密码不一致,请重新输入',
		    '11012'  => '验证的手机与当前手机号码不一致',
		    '11013'  => '验证发送成功',
		    '11014'  => '验证发送失败',
		    '11015'  => '请输入有效的手机号',
		    '11016'	=>	'密码不能小于8位',
		    '11017'	=>	'密码不能为空'
			);
		return $array[$key];
	}	

}


