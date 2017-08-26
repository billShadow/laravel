@extends('admin.base')
@section('content')
    <div class="layui-main">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>功能列表</legend>
        </fieldset>
        <form class="layui-form">
            <div class="layui-form-item">

                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <a class="layui-btn" href="{{ url('adm/power/addaction') }}" >添加功能块</a>
                    </div>
                </div>
            </div>
        </form>
        <table class="layui-table tab-ths">
            <thead>
            <tr>
                <th>编号</th>
                <th>功能名称</th>
                <th>功能描述</th>
                <th>创建时间</th>
                <th>修改时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @if(isset($list) && $list)
                @foreach($list as $k=>$v)
                    <tr>
                        <td class="text-center">{{$v['action_id']}}</td>
                        <td class="text-center">{{$v['action_name']}}</td>
                        <td class="text-center">{{$v['action_desc']}}</td>
                        <td class="text-center">{{$v['created_at']}}</td>
                        <td class="text-center">{{$v['updated_at']}}</td>
                        <td class="text-center">
                            <button class="layui-btn" onClick='location.href="{{ url('/adm/user/edituser?id='.$v['role_id']) }}"'>编辑</button>
                            <button class="layui-btn dels" uid="{{$v['role_id']}}">删除</button>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
        <div class="text-center layui-box layui-laypage layui-laypage-default my_bill_1">
            {{ $list->render() }}
        </div>
    </div>
<script>
    $('.dels').click(function(){
        var uid = $(this).attr('uid');
        if (confirm('确认删除么？') == false){
            return false;
        }else{
            $.ajax({
                url : '/adm/user/deluser?id='+uid,
                type : 'get',
                dateType : 'json',
                success : function(msg){
                    if (msg.result == 1) {
                        layer.msg('删除成功', {'icon':6});
                        setTimeout(function(){
                            window.location.reload();
                        }, 1000);
                    } else {
                        alert(msg.msg);
                        return false;
                    }
                },
                error : function(msg){
                    console.log(msg);
                }
            })
        }
    });
</script>
@endsection
