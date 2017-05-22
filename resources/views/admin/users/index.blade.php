@extends('admin.base')
@section('content')
    <fieldset class="layui-elem-field layui-field-title">
        <legend>后台管理</legend>
        <blockquote class="layui-elem-quote layui-quote-nm">当前用户：{{(isset($info['nickname']) && $info['nickname']) ? $info['nickname'] : $info['account']}}<br>现在时间为：<span id="theClock" style="font-weight: bold; width: 300px;"><?php echo date('Y-m-d H:i:s', time());?></span></blockquote>
    </fieldset>
    <blockquote class="layui-elem-quote">修改密码</blockquote>
    <form class="layui-form" method="post" action="{{url('/adm/edituserpass')}}"  id="form_data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <fieldset class="layui-elem-field">
            <legend>修改密码</legend>
            <div class="layui-field-box">
                <div class="layui-form-item">
                    <label class="layui-form-label">原密码：</label>
                    <div class="layui-input-block">
                        <input type="password" name="old_pass" placeholder="请填写原密码" value="" class="layui-input" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">新密码：</label>
                    <div class="layui-input-block">
                        <input type="password" name="new_pass" placeholder="请填写新密码" value="" class="layui-input" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">确认新密码：</label>
                    <div class="layui-input-block">
                        <input type="password" name="new_pass_ok" placeholder="请再次确认新密码" value="" class="layui-input" >
                    </div>
                </div>
            </div>
        </fieldset>
        <div class="layui-form-item text-center" >
            <button class="layui-btn">确认修改</button>
        </div>
    </form>
    <script>
        $(function () {

            $('#form_data').submit(function(){
                var $old_pass = $('input[name=old_pass]').val();
                var $new_pass = $('input[name=new_pass]').val();
                var $new_pass_ok = $('input[name=new_pass_ok]').val();
                if (!$old_pass) {
                    layer.msg('原密码不能为空', {icon: 5, time:1500});
                    return false;
                }
                if (!$new_pass) {
                    layer.msg('新密码不能为空', {icon: 5, time:1500});
                    return false;
                }
                if (!$new_pass_ok) {
                    layer.msg('确认新密码不能为空', {icon: 5, time:1500});
                    return false;
                }
                $preg = /^[0-9A-Za-z\-_\.\!\@\#\%\^\&\*]{6,16}$/;
                if (!$preg.test($pass)) {
                    layer.msg('密码格式错误，账号只能由6-16位的字母、数字、下划线、中划线、点等字符组成', {icon: 5, time:5000, btn:['明白啦']});
                    return false;
                }

            })
        })

    </script>
@endsection