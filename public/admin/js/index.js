/*
* @Author: Larry
* @Date:   2016-12-15 17:20:54
* @Last Modified by:   qinsh
* @Last Modified time: 2016-12-24 22:06:18
* +----------------------------------------------------------------------
* | LarryBlogCMS [ LarryCMS网站内容管理系统 ]
* | Copyright (c) 2016-2017 http://www.larrycms.com All rights reserved.
* | Licensed ( http://www.larrycms.com/licenses/ )
* | Author: qinshouwei <313492783@qq.com>
* +----------------------------------------------------------------------
*/
'use strict';
layui.use(['jquery','layer','element'],function(){
	window.jQuery = window.$ = layui.jquery;
	window.layer = layui.layer;
  var element = layui.element();
  
// larry-side-menu向左折叠
$('.larry-side-menu').click(function() {
  var sideWidth = $('#larry-side').width();
  if(sideWidth === 200) {
      $('#larry-body').animate({
        left: '0'
      }); //admin-footer
      $('#larry-footer').animate({
        left: '0'
      });
      $('#larry-side').animate({
        width: '0'
      });
  } else {
      $('#larry-body').animate({
        left: '200px'
      });
      $('#larry-footer').animate({
        left: '200px'
      });
      $('#larry-side').animate({
        width: '200px'
      });
  }
});

});
layui.use(['form', 'layedit', 'laydate'], function(){
  var form = layui.form()
  ,layer = layui.layer
  ,layedit = layui.layedit
  ,laydate = layui.laydate;
  
  //创建一个编辑器
  var editIndex = layedit.build('LAY_demo_editor');
 
  //自定义验证规则
  form.verify({
    title: function(value){
      if(value.length < 5){
        return '标题至少得5个字符啊';
      }
    }
    ,pass: [/(.+){6,12}$/, '密码必须6到12位']
    ,content: function(value){
      layedit.sync(editIndex);
    }
  });
  
  //监听指定开关
  form.on('switch(switchTest)', function(data){
    layer.msg('开关checked：'+ (this.checked ? 'true' : 'false'), {
      offset: '6px'
    });
    layer.tips('温馨提示：请注意开关状态的文字可以随意定义，而不仅仅是ON|OFF', data.othis)
  });
  
  //监听提交
  form.on('submit(demo1)', function(data){
    layer.alert(JSON.stringify(data.field), {
      title: '最终的提交信息'
    })
    return false;
  });
  
  
});
//分页
/**
layui.use(['laypage', 'layer'], function(){
  var laypage = layui.laypage
  ,layer = layui.layer;
  
  laypage({
    cont: 'demo1'
    ,pages: 100 //总页数
    ,groups: 5 //连续显示分页数
  });
  
});**/
layui.use('form', function(){
  var $ = layui.jquery, form = layui.form();
  
  //全选
  form.on('checkbox(allChoose)', function(data){
    var child = $(data.elem).parents('table').find('tbody input[type="checkbox"]');
    child.each(function(index, item){
      item.checked = data.elem.checked;
    });
    form.render('checkbox');
  });
  
});

layui.use(['form', 'layedit', 'laydate'], function(){
    var form = layui.form();


    form.on('select(selid)', function(data){
        if(data.value==''){
            layer.msg('参数不能为空');
        }
        $.ajax({
            url : '/adm/project/getIndustryTwo',
            type : 'post',
            data : {id:data.value},
            dateType : 'json',
            success : function(msg){
                if (msg.data == '') {
                    $("#industry_two").empty();
                    $("#industry_two").next().empty();
                } else {
                    $("#industry_two").empty();
                    $("#industry_two").append(msg.data);
                    form.render('select');
                }
            }
        })
    });

    form.on('select(prolid)', function(data){
        if(data.value==''){
            layer.msg('参数不能为空');
        }
        $.ajax({
            url : '/adm/project/getlocation',
            type : 'post',
            data : {id:data.value},
            dateType : 'json',
            success : function(data){
                if (data.data == '') {

                } else {
                    $("#city").empty();
                    $("#area").empty();
                    $("#area").next().empty();
                    $("#city").append(data.data);
                    form.render('select');
                }
            }

        })
    });

    form.on('select(citylid)', function(data){
        var $ = layui.jquery;
        if(data.value==''){
            layer.msg('参数不能为空');
        }
        console.log(data.value);
        $.ajax({
            url : '/adm/project/getlocation',
            type : 'post',
            data : {id:data.value},
            dateType : 'json',
            success : function(data){
                if(data.data!=''){
                    $("#area").empty();
                    $("#area").append(data.data);
                    form.render('select');
                }else{
                    $("#area").next().empty();
                }
            }
        })
    });

    
});
