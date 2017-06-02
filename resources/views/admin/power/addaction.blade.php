@extends('admin.base')
@section('content')
    <style>
    </style>
    <div class="layui-main">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>权限管理</legend>
        </fieldset>
        <blockquote class="layui-elem-quote">添加功能</blockquote>
        <form class="layui-form" method="post" action="{{url('/adm/power/doaddaction')}}"  id="form_data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <fieldset class="layui-elem-field">
                <legend>填写信息</legend>
                <div class="layui-field-box">
                    <div class="layui-form-item">
                        <label class="layui-form-label">角色名称：</label>
                        <div class="layui-input-block">
                            <input type="text" name="role_name" placeholder="请填写角色名称" value="" class="layui-input" >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">角色描述：</label>
                        <div class="layui-input-block">
                            <input type="text" name="role_desc" placeholder="请填写角色描述" value="" class="layui-input" >
                        </div>
                    </div>
                </div>
            </fieldset>
            <div class="layui-form-item text-center" >
                <button class="layui-btn">确认添加</button>
            </div>
        </form>
    </div>
    <script>
        $(function () {

            $('#form_data').submit(function(){
                var $role_name = $('input[name=role_name]').val();
                var $role_desc = $('input[name=role_desc]').val();
                if (!$role_name) {
                    layer.msg('角色名称不能为空', {icon: 5, time:1500});
                    return false;
                }
                if (!$role_desc) {
                    layer.msg('角色描述不能为空', {icon: 5, time:1500});
                    return false;
                }
                /*$.ajax({
                    url : '/adm/video/doaddbanner',
                    type : 'post',
                    dateType : 'json',
                    data : $('#form_data').serialize(),
                    success : function(msg){
                        layer.msg(msg.msg, {icon: 6});
                        console.log(msg);
                        return false;
                        window.location.href = '{{url("adm/video/bannerlist")}}';
                    },
                    error : function(msg){
                        console.log(msg);
                    }
                });*/

            })
        })

    </script>
@endsection

