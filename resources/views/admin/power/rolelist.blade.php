@extends('admin.base')
@section('content')
    <div class="layui-main">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>角色列表</legend>
        </fieldset>
        <form class="layui-form">
            <div class="layui-form-item">

                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <a class="layui-btn" href="{{ url('adm/power/addrole') }}" >添加角色</a>
                    </div>
                </div>
            </div>
        </form>
        <table class="layui-table tab-ths">
            <thead>
            <tr>
                <th>编号</th>
                <th>角色名称</th>
                <th>角色描述</th>
                <th>创建时间</th>
                <th>修改时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @if(isset($list) && $list)
                @foreach($list as $k=>$v)
                    <tr>
                        <td class="text-center">{{$v['role_id']}}</td>
                        <td class="text-center">{{$v['role_name']}}</td>
                        <td class="text-center">{{$v['role_desc']}}</td>
                        <td class="text-center">{{$v['created_at']}}</td>
                        <td class="text-center">{{$v['updated_at']}}</td>
                        <td class="text-center">
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
