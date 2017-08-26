@extends('admin.base')
@section('content')
    <style>
    </style>
    <div class="layui-main">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>权限管理</legend>
        </fieldset>
        <blockquote class="layui-elem-quote">添加角色</blockquote>
        <form class="layui-form" method="post" action="{{url('/adm/power/doaddrole')}}"  id="form_data">
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
                    <div class="layui-form-item">
                        <label class="layui-form-label">banner图：</label>
                        <div class="layui-box layui-upload-button">
                            <input type="file" name="images" class="layui-upload-file" id="banner_one">
                            <span class="layui-upload-icon"><i class="layui-icon"></i>上传图片</span>
                        </div>
                        <span style="color: red">建议：图片文件大小在1M以内，以保证用户浏览时的不会出现加载过慢的情况</span>
                        <div id="content_banner_one" style="margin-left: 15%"></div>
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
            $('#banner_one').change(function(){
                var inputElement = document.getElementById('banner_one');
                var fileList = this.files;
                var reader = new FileReader();
                reader.readAsDataURL(fileList[0]);
                console.log(fileList[0].type);
                reader.onload = function(e) {
                    var image = new Image();
                    image.src = e.target.result;
                    image.onload=function(){
                        console.log(image.width);
                        console.log(image.height);
                        console.log();
                        var bili = image.width/image.height;
                        var www = 120/bili;
                        $('#content_banner_one').html("<img style='float:left;margin:10px 0px 10px 180px;' src='"+image.src+"' width='120px' height='"+www+"px'/>");
                    }
                };
            });

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

