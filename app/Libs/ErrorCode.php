<?php
namespace App\Libs;
//定义返回问题的类
class ErrorCode{

	static public function msg( $key ){
		$array = array(
			'200'	=>	'请求成功',
			'11001'	=>	'账号不能为空',
			'11002'	=>	'密码不能为空',
			'11003'	=>	'账号不存在',
			'11004'	=>	'密码错误',
			'11005'	=>	'缺少参数',
			'11006'	=>	'删除用户成功',
			);
		return $array[$key];
	}	

}


