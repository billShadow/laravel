<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>i麦当劳后台管理</title>
	<meta name="renderer" content="webkit">	
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">	
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">	
	<meta name="apple-mobile-web-app-status-bar-style" content="black">	
	<meta name="apple-mobile-web-app-capable" content="yes">	
	<meta name="format-detection" content="telephone=no">
	<link rel="icon" href="/admin/images/mcdl.jpg" type="image/x-icon" />
	<!-- load css -->
	<link rel="stylesheet" type="text/css" href="/admin/common/layui/css/layui.css" media="all">
	<link rel="stylesheet" type="text/css" href="/admin/common/global.css" media="all">
	<link rel="stylesheet" type="text/css" href="/admin/css/style.css" media="all">
</head>
<body>
<div class="layui-layout layui-layout-admin" id="layui_layout">
	<!-- 顶部区域 -->
	<div class="layui-header header header-demo">
	  <div class="layui-main">
		<a class="logo" href="#">
		  <img src="/admin/images/mcdl.png" alt="ChanelCircle">
		</a>
		<span class="layui-nav-bar"></span></ul>
	  </div>
	</div>

	<!-- 左侧侧边导航结束 -->
	<!-- 右侧主体内容 -->
	<div class="layui-tab layui-tab-brief" lay-filter="demoTitle">
			<div class="layui-main">
				<div class="layui-tab layui-tab-brief main-tab-container text-center" style="margin-left: 35%;margin-top: 10%">
					<form class="layui-form" action="javascript:;"  id="form_data" method="post">
						<div class="layui-form-item">
							<label class="layui-form-label">账号</label>
							<div class="layui-input-inline">
								<input id="account" type="text" name="account" lay-verify="pass" value="" placeholder="请输入账号" autocomplete="off" class="layui-input">
							</div>
							<div class="layui-form-mid layui-word-aux">请填账号</div>
						</div>
						<div class="layui-form-item">
							<label class="layui-form-label">密码</label>
							<div class="layui-input-inline">
								<input id="passwd" type="password" name="passwd" lay-verify="pass" value="" placeholder="请输入密码" autocomplete="off" class="layui-input">
							</div>
							<div class="layui-form-mid layui-word-aux">请填写6到12位密码</div>
						</div>
						<input type="hidden" name="_token" value="{{ csrf_token() }}" />
						<div class="layui-form-item">
							<div class="layui-input-block">
								<button class="layui-btn"  lay-filter="demo1">立即提交</button>
								<button type="reset" class="layui-btn layui-btn-primary">重置</button>
							</div>
						</div>
					</form>
			</div>
        </div>
	</div>
</div>
<!-- 加载js文件-->
<script type="text/javascript" src="/admin/common/layui/layui.js"></script>
<script type="text/javascript" src="/admin/common/layui/laydate/laydate.js"></script>
<script type="text/javascript" src="/admin/js/larry.js"></script>
<script type="text/javascript" src="/admin/js/index.js"></script>
<script type="text/javascript" src="/admin/js/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="/admin/js/layer/layer.js"></script>
<script>
	$(function(){
		$('#form_data').submit(function(){
			var user_name = $('#account').val();
			var pass_word = $('#passwd').val();
			if(!user_name){
				layer.msg('请填写登陆账号', {icon: 5});
				return false;
			}
			if(!pass_word){
				layer.msg('请填写登陆密码', {icon: 5});
				return false;
			}
			$.ajax({
				url : '/adm/dologin',
				type : 'post',
				dateType : 'json',
				data : $(this).serialize(),
				success : function(msg){
					if(msg.result == 1){
						window.location.href = '{{url("adm/index")}}';
					} else {
						layer.msg(msg.msg, {icon: 5});
						return false;
					}
				},
				error : function(msg){
					console.log('error');
				}
			})
		});
	});
</script>
</body>
</html>
