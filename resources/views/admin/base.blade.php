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
	<link rel="icon" href="/admin/images/mcdl.jpg" type="image/x-icon" />{{--显示ICO图标--}}
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
		<a class="logo" href="{{url('adm/index')}}">
		  <img src="/admin/images/mcdl.png" alt="ChannelCircle">
		</a>
		<ul class="layui-nav rightlaynav" pc="">
		  <li class="layui-nav-item" pc="">
			<a href="{{ url('/adm/signout') }}">退出登录</a>
		  </li>
		<span class="layui-nav-bar"></span></ul>
	  </div>
	</div>
	<!-- 左侧侧边导航开始 -->
	<div class="layui-side layui-bg-black layui-larry-side" id="larry-side">
        <div class="layui-side-scroll" id="larry-nav-side" lay-filter="side">
		<!-- 左侧菜单 -->
		<ul class="layui-nav layui-nav-tree">
			<li class="layui-nav-item">
				<dd class="
					<?php if($_SERVER['REQUEST_URI'] == '/adm/index' ){ echo 'layui-this';} ?>"
				>
				<a href="{{url('/adm/index')}}" data-url="index.html">
				    <i class="iconfont icon-home1" data-icon='icon-home1'></i>
					<span>后台管理</span>
				</a>
			</li>
			<li class="layui-nav-item">
				<dd class="
					<?php if($_SERVER['REQUEST_URI'] == '/adm/user/userlist' ){ echo 'layui-this';} ?>"
				>
					<a href="{{url('adm/user/userlist')}}">
						<i class="iconfont icon-wenzhang1" ></i>
						<span>用户管理</span>
					</a>
			</li>
			<li class="layui-nav-item">
				<dd class="
					<?php if($_SERVER['REQUEST_URI'] == '/adm/order/orderlist' ){ echo 'layui-this';} ?>"
				>
					<a href="{{url('adm/order/orderlist')}}">
					   <i class="iconfont icon-wenzhang1" ></i>
					   <span>订单管理</span>
					</a>
		   </li>
			<li class="layui-nav-item">
				<dd class="
					<?php if($_SERVER['REQUEST_URI'] == '/adm/order/orderlist' ){ echo 'layui-this';} ?>"
				>
					<a href="{{url('adm/order/orderlist')}}">
						<i class="iconfont icon-wenzhang1" ></i>
						<span>视频管理</span>
					</a>
			</li>
		</ul>
	    </div>
	</div>

	<!-- 左侧侧边导航结束 -->
	<!-- 右侧主体内容 -->
	<div class="layui-tab layui-tab-brief" lay-filter="demoTitle">
		<div class="layui-body layui-tab-content site-demo site-demo-body">
			<div class="layui-main">
				@yield('content')
			</div>
        </div>
	</div>

</div>


</body>
</html>
