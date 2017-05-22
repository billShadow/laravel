<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>麦当劳小程序后台管理</title>
	<meta name="renderer" content="webkit">	
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">	
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">	
	<meta name="apple-mobile-web-app-status-bar-style" content="black">	
	<meta name="apple-mobile-web-app-capable" content="yes">	
	<meta name="format-detection" content="telephone=no">
	<link rel="icon" href="/images/mcdl.jpg" type="image/x-icon" />{{--显示ICO图标--}}
	<!-- load css -->
	<link rel="stylesheet" type="text/css" href="/admin/common/layui/css/layui.css" media="all">
	<link rel="stylesheet" type="text/css" href="/admin/common/global.css" media="all">
	<link rel="stylesheet" type="text/css" href="/admin/css/style.css" media="all">
	<script type="text/javascript" src="/admin/js/jquery-1.10.1.min.js"></script>
	<!-- 加载js文件-->
	<script type="text/javascript" src="/admin/common/layui/layui.js"></script>
	<script type="text/javascript" src="/admin/js/index.js"></script>
</head>
<body>


<div class="layui-layout layui-layout-admin" id="layui_layout">
	<!-- 顶部区域 -->
	<div class="layui-header header header-demo">
		<div class="layui-main">
			<a class="logo" href="#">
				<img src="/images/mcdl.jpg" alt="ChanelCircle">
			</a>
			<span class="layui-nav-bar"></span></ul>
		</div>
	</div>

	<!-- 左侧侧边导航结束 -->
	<!-- 右侧主体内容 -->
	<div class="layui-tab layui-tab-brief" lay-filter="demoTitle">
		<div class="layui-main">
			<div class="main-tab-container text-center" style="width:20%;height:100px;margin:10% auto;background-color: #c9e2b3">
				<meta http-equiv="refresh" content="2; url={{isset($info['url']) ? $info['url'] : ''}}">
					<div class="" style="text-align: center;padding-top: 10%">
						<p style="color:red;">{{isset($info['error']) ? $info['error'] : ''}}</p>
						<p style="color: #3c763d">{{isset($info['success']) ? $info['success'] : ''}}</p>
						<p>页面正在跳转，如果没有转向请<a href="{{isset($info['url']) ? $info['url'] : ''}}">点击此处</a></p>
					</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
