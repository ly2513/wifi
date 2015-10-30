<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo C('sitename');?>--会员中心</title>
<meta name="keywords" content="<?php echo C('keyword');?>"/>
<meta name="description" content="<?php echo C('content');?>"/>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="<?php echo ($Theme['P']['root']); ?>/matrix/css/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo ($Theme['P']['root']); ?>/matrix/css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="<?php echo ($Theme['P']['root']); ?>/matrix/css/matrix-style.css" />
<link rel="stylesheet" href="<?php echo ($Theme['P']['root']); ?>/matrix/css/matrix-media.css" />
<link href="<?php echo ($Theme['P']['root']); ?>/matrix/font-awesome/css/font-awesome.css" rel="stylesheet" />
<link href="<?php echo ($Theme['P']['root']); ?>/font/googlefont.css" rel="stylesheet" />
<link rel="stylesheet" href="<?php echo ($Theme['P']['root']); ?>/matrix/css/datepicker.css" />

</head>
<body>
   <!--Header-part-->
<div id="header">
  <h1><a href="#"></a></h1>
</div>
<!--close-Header-part--> 
<!--top-Header-menu-->

<div id="user-nav" class="navbar navbar-inverse">

  <ul class="nav">
    <li  class="dropdown" id="profile-messages" >
      <a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle">
      <i class="icon icon-user"></i>  
      <span class="text">欢迎光临</span>
      <b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li>
          <a href="<?php echo U('User/account');?>"><i class="icon-user"></i> 修改密码</a>
        </li>
        <li class="divider"></li>
        <li>
          <a href="<?php echo U('User/logout');?>"><i class="icon-key"></i>退出</a>
        </li>
      </ul>
    </li>
	
    <li class="">
      <a title="" href="<?php echo U('User/logout');?>"><i class="icon icon-share-alt"></i> <span class="text">退出</span></a>
    </li>
    <li class="">
      <a title="" href="javascript:void(0);">服务热线：<?php echo C('linktel');?></a>
    </li>
    <li>
      <a title="" href="javascript:void(0);">商务合作：</a>
    </li>
    <li class="">
      <a title="" href="javascript:void(0);">QQ:<?php echo C('qq');?></a>
    </li>
    <li>
      <a title="" href="javascript:void(0);">技术支持：<?php echo C('sitename');?></a>
    </li>
  </ul>
     
</div>

<!--close-top-Header-menu-->

   <!--sidebar-menu-->
<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
  <ul>
    <li class="<?php if(($a == 'index')): ?>active"<?php endif; ?>" ><a href="<?php echo U('User/index');?>"><i class="icon icon-home"></i> <span>首页</span></a> </li>
     <li class="<?php if(($a == 'info')): ?>active"<?php endif; ?>"> <a href="<?php echo U('User/info');?>"><i class="icon icon-group"></i> <span>商户信息</span></a> </li>
    <li class="<?php if(($a == 'application')): ?>active"<?php endif; ?>"> <a href="<?php echo U('User/application');?>"><i class="icon icon-cogs"></i> <span>应用设置</span></a> </li>
    <li class="<?php if(($a == 'three_p')): ?>active"<?php endif; ?>"> <a href="<?php echo U('User/three_p');?>"><i class="icon icon-cogs"></i> <span>微信动态认证</span></a> </li>
           <li class="<?php if(($a == 'routemap')): ?>active"<?php endif; ?>"> <a href="<?php echo U('User/routemap');?>"><i class="icon icon-group"></i> <span>路由管理</span></a> </li>
      <li class="<?php if(($a == 'authtplset')): ?>active"<?php endif; ?>"> <a href="<?php echo U('Authset/tplset');?>"><i class="icon icon-th"></i> <span>认证页面</span></a> </li>
     
     
     <li class="submenu <?php if(($a == 'adv')): ?>active"<?php endif; ?>"> <a href="#" id="adv"><i class="icon icon-cloud"></i> <span>广告管理</span></a>
   	 <ul>
        <li><a href="<?php echo U('User/adv');?>">广告管理</a></li>
        
         <li><a href="<?php echo U('User/advrpt');?>">广告统计</a></li>
		
      </ul>
   </li>
   
           <li class="submenu <?php if(($a == 'web3g')): ?>active"<?php endif; ?>"> <a href="#" id="web3g"><i class="icon icon-th-large"></i> <span>小微官网</span></a>
      <ul>
        <li><a href="<?php echo U('index/web/index');?>">网站设置</a></li>
        
         <li><a href="<?php echo U('web/catelog');?>">网站分类</a></li>
		 <li><a href="<?php echo U('web/arts');?>">文章管理</a></li>
		  <li><a href="<?php echo U('web/tempset');?>">模板管理</a></li>
      </ul>
    </li>
     <li class="<?php if(($a == 'comment')): ?>active"<?php endif; ?>"> <a href="<?php echo U('User/comment');?>"><i class="icon icon-envelope-alt"></i> <span>客户留言</span></a> </li>
         <li class="submenu <?php if(($a == 'report')): ?>active"<?php endif; ?>" > <a href="#" id="urpt"><i class="icon icon-user"></i> <span>用户统计</span></a>
      <ul>
        <li><a href="<?php echo U('User/userchart');?>">统计报表</a></li>
        <li><a href="<?php echo U('User/report');?>">用户列表</a></li>
       

      </ul>
    </li>
    <li class="<?php if(($a == 'online')): ?>active"<?php endif; ?>"> <a href="<?php echo U('User/rpt');?>"><i class="icon icon-signal"></i> <span>上网统计</span></a> </li>
   <li class="submenu <?php if(($a == 'advfun')): ?>active"<?php endif; ?>" > <a href="#" id="sale"><i class="icon icon-dashboard"></i> <span>营销管理</span></a>
      <ul>
        <li><a href="<?php echo U('index/Adv/phonelist');?>">手机号码管理</a></li>
         <li><a href="<?php echo U('adv/set');?>">短信帐号管理</a></li>
        <li><a href="<?php echo U('adv/sms');?>">短信群发</a></li>
      	<li><a href="<?php echo U('adv/smslist');?>">短信发送列表</a></li>
      </ul>
    </li>
  
  </ul>
</div>
<!--sidebar-menu-->
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="<?php echo U('user/index');?>" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" class="current">上网统计</a> </div>
    <h1>上网统计</h1>
 
  </div>
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
    	<div class="alert alert-block span8 hide" id="msgbox"> 
              <h4 class="alert-heading">提示信息</h4>
             <div id="alertmsg"></div>
              </div>
    <div class="controls controls-row span8">
 	<input type="hidden" name="btnkey" id="btnkey" value="today" sdate="" edate="">
    <label class="control-label span1">开始日期</label>
   <input type="text" id="sdate" value="<?php echo date("Y-m-01") ?>" data-date-format="yyyy-mm-dd" class="span2 datepicker" readonly="readonly">
     <label class="control-label span1">结束日期</label>
   <input type="text" id="edate" value="<?php echo date("Y-m-d") ?>" data-date-format="yyyy-mm-dd" class="span2 datepicker" readonly="readonly">
    	<a class="btn btn-success span1" id="query">查询</a>&nbsp;
    	  <a href="javascript:void(0);" onclick="downjump();" class="btn btn-primary span1">导出</a>
    </div>
      <div class="span10">
            	<a class="btn btn-info" id="today">今日数据统计</a>&nbsp;
            	<a class="btn btn-info" id="yestoday">昨日统计</a>&nbsp;
            	<a class="btn btn-info" id="week">最近七天</a>&nbsp;
            	<a class="btn btn-info" id="month">本月统计</a>
            </div>
    </div>
    <div class="row-fluid">
      <div class="span12">
          
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-th"></i></span>
            <h5>统计列表</h5>
          </div>
          
          <div class="widget-content">
          
          	<div id="placeholder" ></div>
         
          
          </div>
        
        </div>
        
      
        
        
      </div>
    </div>
  </div>
</div>

<!--Footer-part-->
<div class="row-fluid">
  <div id="footer" class="span12"> 2015-2018 &copy; cuckoowifo <a href="<?php echo C('siteurl');?>"><?php echo C('shortsiteurl');?></a><br> <a href="http://www.miitbeian.gov.cn/">京ICP备14017729号</a></div>
</div>
<!--end-Footer-part--> 
<script>

</script>
<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/jquery.min.js"></script> 
<script src="<?php echo ($Theme['P']['js']); ?>/flot/jquery.flot.js"></script> 
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="<?php echo ($Theme['P']['js']); ?>/flot/excanvas.min.js"></script><![endif]-->  

<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/bootstrap.min.js"></script> 
<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/bootstrap-datepicker.js"></script> 
<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/matrix.js"></script> 
<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/common.js"></script> 
<script>
var daylist=[];
for(var i=1;i<=31;i++){
	if(i<10){
		daylist.push(["0"+i,i+"号"]);
	}else{
		daylist.push([i,i+"号"]);
	}
}
var hourlist=[];
for(var i=0;i<=24;i++){
	if(i<10){
		hourlist.push(["0"+i,i+"点"]);
	}else{
		hourlist.push([i,i+"点"]);
	}
}

function downjump(){
	var para="&mode="+$('#btnkey').val();
	para+="&sdate="+$('#btnkey').attr('sdate');
	para+="&edate="+$('#btnkey').attr('edate');
	location.href="index.php?g=index&m=user&a=downrpt"+para;
	
	
}

var lines;

$(function () {
	
	var stack = 0, bars = true, lines = false, steps = false;  
	 	$('.datepicker').datepicker();
	 	// 获得今日数据统计
  	  	$("#today").bind("click",function(){
		  	$.ajax({
			  	url: '<?php echo U('user/getrpt');?>',
		        type: "get",
				data:{'mode':'today'},
				dataType:'json',
				error:function(){
						AlertTips("服务器忙，请稍候再试",2000);
				},
				success:function(data){
						$('#btnkey').val('today');
						var bt=[];
						data=eval(data)  ;
						for(var vo in data){
							bt.push([data[vo].t,data[vo].ct]);
						}
						$.plot($("#placeholder"), [ bt ], {grid: { hoverable: true, clickable: true, borderColor:'#000',borderWidth:1},xaxis:{ticks:hourlist},series:{lines:{fill:true, show: true}, points:
						    { show: true,
						    	  }}});
						
				}
			});
  	  	});
  	  	// 获得昨日上网统计数据
  		$("#yestoday").bind("click",function(){
		  	$.ajax({
			  	url: '<?php echo U('user/getrpt');?>',
		        type: "get",
				data:{'mode':'yestoday'},
				dataType:'json',
				error:function(){
						AlertTips("服务器忙，请稍候再试",2000);
				},
				success:function(data){
						var bt=[];
						$('#btnkey').val('yestoday');
						data=eval(data);
						for(var vo in data){
							bt.push([data[vo].t,data[vo].ct]);
						}
						$.plot($("#placeholder"), [ bt ], {grid: { hoverable: true, clickable: true, borderColor:'#000',borderWidth:1},xaxis:{ticks:hourlist},series:{lines:{fill:true, show: true}, points:
						    { show: true,
						    	  }}});
						
				}
			});
	  	});
	  	// 获得最近七天的上网统计
  		$("#week").bind("click",function(){
		  	$.ajax({
			  	url: '<?php echo U('user/getrpt');?>',
		        type: "get",
				data:{'mode':'week'},
				dataType:'json',
				error:function(){
						AlertTips("服务器忙，请稍候再试",2000);
				},
				success:function(data){
					$('#btnkey').val('week');
					var bt=[];
					data=eval(data);
					var templist=[];
					for(var vo in data){
 						templist.push([data[vo].t,data[vo].td]);
						bt.push([data[vo].t,data[vo].ct]);
					}
						$.plot($("#placeholder"), [ bt ], {grid: { hoverable: true, clickable: true, borderColor:'#000',borderWidth:1},xaxis:{ticks:templist},series:{lines:{fill:true, show: true}, points:
						    { show: true,
						    	  }}});		
				}
			});
	  	});
	  	// 获得本月上网统计数据
  		$("#month").bind("click",function(){
  		  	$.ajax({
  			  	url: '<?php echo U('user/getrpt');?>',
  		        type: "get",
  				data:{'mode':'month'},
  				dataType:'json',
  				error:function(){
  						AlertTips("服务器忙，请稍候再试",2000);
  				},
  				success:function(data){
					$('#btnkey').val('month');
					var bt=[];
					data=eval(data)  ;
					for(var vo in data){
						bt.push([data[vo].t,data[vo].ct]);
					}
					 $.plot($("#placeholder"), [ bt ], {grid: { hoverable: true, clickable: true, borderColor:'#000',borderWidth:1},xaxis:{ticks:daylist},series:{lines:{fill:true, show: true}, points:
					    { show: true,
					    	  }}});
  				}
  			});
  	  	});

  		$("#query").bind("click",function(){
			var st=new Date($("#sdate").val());	
			var et=new Date($("#edate").val());	
			if(st.getTime()>et.getTime()){
				AlertTips("开始日期不能大于结束日期",2000);
					return;
			}

			$.ajax({
	  			url: '<?php echo U('user/getrpt');?>',
		        type: "get",
				data:{
					'mode':'query',
					'sdate':$("#sdate").val(),
					'edate':$("#edate").val(),
					},
				dataType:'json',
				error:function(){
						AlertTips("服务器忙，请稍候再试",2000);
						},
				success:function(data){
					$('#btnkey').val('query');
					$('#btnkey').attr('sdate',$("#sdate").val());
					$('#btnkey').attr('edate',$("#edate").val());
					var bt=[];
					var templist=[];
					
					data=eval(data)  ;
					for(var vo in data){
						templist.push([data[vo].t,data[vo].td]);
						bt.push([data[vo].t,data[vo].ct]);
					}
					 var plot= $.plot($("#placeholder"), [ bt ], {xaxis:{ticks:templist},  grid: { hoverable: true, clickable: true, borderColor:'#000',borderWidth:1}, series:{lines:{fill:true, show: true}, points:
					    { show: true,
					    	  }}});
				}
	  		});
  	  	});
  		$("#today").trigger("click");
});

var previousPoint = null;
	$("#placeholder").bind("plothover", function (event, pos, item) {
	
      if (item) {
          if (previousPoint != item.dataIndex) {
              previousPoint = item.dataIndex;
              
              $('#tooltip').fadeOut(200,function(){
					$(this).remove();
				});
              var x = item.datapoint[0].toFixed(2),
					y = item.datapoint[1].toFixed(2);
                  
              maruti.flot_tooltip(item.pageX, item.pageY, y+"人次");
          }
          
      } else {
			$('#tooltip').fadeOut(200,function(){
					$(this).remove();
				});
          previousPoint = null;           
      }   
  });	
maruti = {
		// === Tooltip for flot charts === //
		flot_tooltip: function(x, y, contents) {
			
			$('<div id="tooltip">' + contents + '</div>').css( {
				top: y + 5,
				left: x + 5
			}).appendTo("body").fadeIn(200);
		}
}
</script>
</body>
</html>