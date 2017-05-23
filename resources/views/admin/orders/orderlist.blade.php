@extends('admin.base')
@section('content')
    <style type="text/css">
        .layui-form-item .layui-input-inline {
            float: left;
            width:120px;
            margin-right: 10px;
        }
    </style>
    <div class="layui-main">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>管理员列表</legend>
        </fieldset>
        <form class="layui-form">
            <div class="layui-form-item">

                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <a class="layui-btn" href="{{ url('adm/order/addorder') }}" >批量添加订单</a>

                    </div>
                    <div class="layui-input-inline" style="width:100px">
                        <a class="layui-btn" href="{{ url('adm/order/exportorder') }}" >导出订单</a>
                    </div>

                    <div class="layui-input-inline" style="width:100px">
                        <a class="layui-btn order-import" href="javascript:;" >导入订单</a>
                    </div>

                </div>
            </div>
        </form>
        <table class="layui-table tab-ths">
            <thead>
            <tr>
                <th>订单编号</th>
                <th>订单状态</th>
                <th>卡券</th>
                <th>积分</th>
                <th>创建时间</th>
                <th>修改时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @if(isset($list) && $list)
                @foreach($list as $k=>$v)
                    <tr>
                        <td class="text-center">{{$v['order_no']}}</td>
                        <td class="text-center">{{$v['order_status']}}</td>
                        <td class="text-center">{{$v['card_id']}}</td>
                        <td class="text-center">{{$v['integral']}}</td>
                        <td class="text-center">{{$v['created_at']}}</td>
                        <td class="text-center">{{$v['updated_at']}}</td>
                        <td class="text-center">
                            <button class="layui-btn" onClick='location.href="{{ url('/adm/user/edituser?id='.$v['id']) }}"'>编辑</button>
                            <button class="layui-btn dels" uid="{{$v['id']}}">删除</button>
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

    // 导入csv订单文件
    $('.order-import').click(function(){
        layer.open({
            title: '请导入文件',
            formType: 1,
            btn: ['取消'],
            area: ['350px', '250px'],
            skin: 'layui-layer-molv' //样式类名
            ,closeBtn: 0,
            content: /*'<div style="">' +
            '<input type="file" id="product_id" class="layui-input" name="name" value="">' +
            '<input type="submit" style="margin:0 auto" class="layui-btn" value="确认导入">' +
            '</div>',*/
                '<form class="layui-form" method="post" action="{{url('/adm/order/importorder')}}"  id="form_data" enctype="multipart/form-data">'+
                '<div class="layui-form-item" style="margin-left:30%">'+
                    '<div class="layui-box layui-upload-button">'+
                        '<input type="file" name="file" class="layui-upload-file" id="banner_two">'+
                        '<span class="layui-upload-icon"><i class="layui-icon"></i>导入文件</span>'+
                    '</div>'+
                    '<div id="content_banner_two" style="margin-left: 15%"></div>'+
                '</div>'+
                '<div class="layui-form-item"  style="margin-left:30%">'+
                    '<input type="submit" style="margin:0 auto" class="layui-btn" value="确认导入">' +
                '</div>'+
                '<input type="hidden" name="_token" value="{{ csrf_token() }}" />'+
                '</form>'
            ,
            yes: function(){
                layer.closeAll();
                var product_name = $('input[name=name]').val();
                /*if (!product_name) {
                    var ii = layer.tips('取消原因不能为空!', '#product_id');
                    //此处用setTimeout演示ajax的回调
                    setTimeout(function(){
                        layer.close(ii);
                    }, 1000);
                    return ;
                }*/
            },
            btn1: function(){
                layer.closeAll();
            }
        });

    });
</script>
@endsection
