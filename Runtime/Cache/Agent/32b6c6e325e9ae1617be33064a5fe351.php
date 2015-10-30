<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo C('sitename');?>--代理商平台</title>
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
<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/jquery.min.js"></script> 
</head>
<body>
 <link href="<?php echo ($Theme['P']['root']); ?>/css/qq/css/contact.css" rel="stylesheet" type="text/css" />

<!--Header-part-->
<div id="header">
  <h1><a href="#"></a></h1>
</div>
<!--close-Header-part--> 
<!--top-Header-menu-->

<div id="user-nav" class="navbar navbar-inverse">

  <ul class="nav">
    <li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-user"></i>登录帐号:(<?php echo (session('account')); ?>)</a>
      <ul class="dropdown-menu">
        <li><a href="<?php echo U('index/pwd');?>"><i class="icon-user"></i> 修改密码</a></li>
        <li class="divider"></li>
        <li><a href="<?php echo U('index/index/alogout');?>"><i class="icon-key"></i>退出</a></li>
      </ul>
    </li>
	
  <li class=""><a title="" href="<?php echo U('index/index/alogout');?>"><i class="icon icon-share-alt"></i> <span class="text">退出</span></a></li>
    </ul>
     
</div>

<!--close-top-Header-menu-->

<!--sidebar-menu-->
<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
  <ul>
    <li class="<?php if(($nav['a'] == 'index')): ?>active"<?php endif; ?>"><a href="<?php echo U('Index/index');?>"><i class="icon icon-home"></i> <span>首页</span></a> </li>
    <li class="<?php if(($nav['a'] == 'account')): ?>active"<?php endif; ?>"><a href="<?php echo U('Index/account');?>"><i class="icon icon-group"></i> <span>账户管理</span></a> </li>
    <li class="submenu <?php if(($nav['a'] == 'shop')): ?>active"<?php endif; ?>"><a href="#" id="shop"><i class="icon icon-user"></i> <span>商户管理</span></a>
   	  <ul> 
        <li><a href="<?php echo U('Index/shoplist');?>">商户列表</a></li>
        <li><a href="<?php echo U('Index/shopadd');?>">添加商户</a></li>
      </ul>
    </li>
    <li class="submenu <?php if(($nav['a'] == 'adman')): ?>active"<?php endif; ?>"><a href="#" id="adman"><i class="icon icon-cloud"></i> <span>广告管理</span></a>
      <ul>
        <li><a href="<?php echo U('Admanage/shopad');?>">广告列表</a></li>
        <li><a href="<?php echo U('Admanage/adrpt');?>">广告统计</a></li>
      </ul>
    </li>
    <li class="submenu <?php if(($nav['a'] == 'pushadv')): ?>active"<?php endif; ?>"><a href="#" id="pushadv"><i class="icon icon-th-large"></i> <span>广告推送管理</span></a>
      <ul>
        <li><a href="<?php echo U('pushadv/set');?>">推送设置</a></li>
        <li><a href="<?php echo U('pushadv/index');?>">广告列表</a></li>
        <li><a href="<?php echo U('pushadv/add');?>">投放广告</a></li>
        <li><a href="<?php echo U('pushadv/rpt');?>">投放统计</a></li>
      </ul>
    </li>
    <li class="<?php if(($nav['a'] == 'report')): ?>active"<?php endif; ?>"><a href="<?php echo U('Index/report');?>"><i class="icon icon-envelope-alt"></i> <span>资金报表</span></a> </li>
  </ul>
</div>
<!--sidebar-menu-->
<!--main-container-part-->
<div id="content">
  <!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="<?php echo U('index/index');?>" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a></div>
  </div>
  <!--End-breadcrumbs-->
  <div class="container-fluid" >
      <div class="widget-box widget-plain">
      <div class="center">
        <ul class="stat-boxes2">
          <li>
            <div class="left peity_bar_neutral"><span><span style="display: none;">2,4,9,7,12,10,12</span>
              <canvas width="50" height="24"></canvas>
              </span></div>
            <div class="right"> <strong id="sumauth">0</strong> 流量统计 </div>
          </li>
          <li>
            <div class="left peity_line_neutral"><span><span style="display: none;">10,15,8,14,13,10,10,15</span>
              <canvas width="50" height="24"></canvas>
              </span></div>
            <div class="right"> <strong  id="sumuser">0</strong>新增用户 </div>
          </li>
          <li>
            <div class="left peity_bar_bad"><span><span style="display: none;">3,5,6,16,8,10,6</span>
              <canvas width="50" height="24"></canvas>
              </span></div>
            <div class="right"> <strong id="sumshopad">0</strong> 商户广告统计</div>
          </li>
          <li>
            <div class="left peity_line_good"><span><span style="display: none;">12,6,9,23,14,10,17</span>
              <canvas width="50" height="24"></canvas>
              </span></div>
            <div class="right"> <strong id="sumpushad">0</strong> 投放广告统计</div>
          </li>
         
        </ul>
      </div>
    </div>
  <div class="row-fluid">
      <div class="span6">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-signal"></i> </span>
            <h5>流量统计</h5>
          </div>
          <div class="widget-content">
            <div class="chart" id="authchart"></div>
          </div>
        </div>
      </div>
      <div class="span6">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-signal"></i> </span>
            <h5>用户统计</h5>
          </div>
          <div class="widget-content">
            <div class="chart" id="userchart"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="row-fluid">
      <div class="span6">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-signal"></i> </span>
            <h5>商户广告统计</h5>
          </div>
          <div class="widget-content">
            <div class="chart" id="shopchart"></div>
          </div>
        </div>
      </div>
      <div class="span6">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-signal"></i> </span>
            <h5>推送广告统计</h5>
          </div>
          <div class="widget-content">
            <div class="chart" id="pushchart"></div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/jquery.ui.custom.js"></script> 
<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/bootstrap.min.js"></script> 
<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/matrix.js"></script> 
<script src="<?php echo ($Theme['P']['js']); ?>/flot/jquery.flot.js"></script> 
<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/jquery.peity.min.js"></script> 
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="<?php echo ($Theme['P']['js']); ?>/flot/excanvas.min.js"></script><![endif]-->  
<script>


maruti = {
		// === Peity charts === //
		peity: function(){		
			$.fn.peity.defaults.line = {
				strokeWidth: 1,
				delimeter: ",",
				height: 24,
				max: null,
				min: 0,
				width: 50
			};
			$.fn.peity.defaults.bar = {
				delimeter: ",",
				height: 24,
				max: null,
				min: 0,
				width: 50
			};
			$(".peity_line_good span").peity("line", {
				colour: "#57a532",
				strokeColour: "#459D1C"
			});
			$(".peity_line_bad span").peity("line", {
				colour: "#FFC4C7",
				strokeColour: "#BA1E20"
			});	
			$(".peity_line_neutral span").peity("line", {
				colour: "#CCCCCC",
				strokeColour: "#757575"
			});
			$(".peity_bar_good span").peity("bar", {
				colour: "#459D1C"
			});
			$(".peity_bar_bad span").peity("bar", {
				colour: "#BA1E20"
			});	
			$(".peity_bar_neutral span").peity("bar", {
				colour: "#4fb9f0"
			});
		},

		// === Tooltip for flot charts === //
		flot_tooltip: function(x, y, contents) {
			
			$('<div id="tooltip">' + contents + '</div>').css( {
				top: y + 5,
				left: x + 5
			}).appendTo("body").fadeIn(200);
		}
}
var hourlist=[];
for(var i=0;i<24;i++){
	if(i<10){
		hourlist.push(["0"+i,i+"点"]);
	}else{
		hourlist.push([i,i+"点"]);
	}
}
function ct_ad(data){
	var sum=0;
	for(var vo in data){
		sum+=parseFloat( data[vo].showup);
	}
	$("#sumpushad").text(sum);
}
function ct_shopad(data){
	var sum=0;
	for(var vo in data){
		sum+=parseFloat( data[vo].showup);
	}
	$("#sumshopad").text(sum);
}
function init_chart(data){
	var bt=[];
	var bt_reg=[];
	var bt_phone=[];
	var bt_log=[];
	var bt_key=[];
	data=eval(data)  ;
	for(var vo in data){
		bt.push([data[vo].t,data[vo].ct]);
		bt_reg.push([data[vo].t,data[vo].ct_reg]);
		bt_phone.push([data[vo].t,data[vo].ct_phone]);
		bt_key.push([data[vo].t,data[vo].ct_key]);
		bt_log.push([data[vo].t,data[vo].ct_log]);
	}
	var ds=[{label:"总数",data:bt},{label:"注册认证",data:bt_reg},{label:"手机认证",data:bt_phone},{label:"一键上网",data:bt_key},{label:"帐号登录",data:bt_log},];
	$.plot($("#authchart"), ds, {grid: { hoverable: true, clickable: true, borderColor:'#000',borderWidth:1},xaxis:{ticks:hourlist},series:{lines:{fill:true, show: true}, points:{show: true,}}});
	ct_auth(data);
}

function ct_auth(data){
	var sum=0;
	for(var vo in data){
		sum+=parseFloat( data[vo].ct);
	}
	$("#sumauth").text(sum);
}
function ct_user(data){
	var sum=0;
	for(var vo in data){
		sum+=parseFloat( data[vo].totalcount);
	}
	$("#sumuser").text(sum);
}

function authchart(authurl){
		  $.ajax({
			  url: authurl,
		        type: "get",
				data:{
					'mode':'today'
					},
				dataType:'json',
				success:function(data){
					init_chart(data);
				}
			  });
	  	  
}

function shopad(authurl){
	$.ajax({
			  url: authurl,
		        type: "get",
				data:{
					'mode':'today'
					},
				dataType:'json',
				success:function(data){
						var bt=[];
						var bt_hit=[];
						data=eval(data);
						for(var vo in data){
							
							bt.push([data[vo].t,data[vo].showup]);
							bt_hit.push([data[vo].t,data[vo].hit]);
						}
						var dataset=[{label:"广告展示",data:bt},{label:"广告点击",data:bt_hit}];
						 $.plot($("#shopchart"), dataset, {grid: { hoverable: true, clickable: true, borderColor:'#000',borderWidth:1},xaxis:{ticks:hourlist},series:{lines:{fill:true, show: true}, points:  { show: true,}}});
							ct_shopad(data);
				}
			  });
}
function adchar(charurl){
	$.ajax({ url: charurl,
			        type: "get",
					data:{
						'mode':'today'
						
						},
					dataType:'json',
				
					success:function(data){
							var bt=[];
							var bt_hit=[];
							data=eval(data);
							for(var vo in data){
								
								bt.push([data[vo].t,data[vo].showup]);
								bt_hit.push([data[vo].t,data[vo].hit]);
							}
							var dataset=[{label:"广告展示",data:bt},{label:"广告点击",data:bt_hit}];
							 $.plot($("#pushchart"), dataset, {grid: { hoverable: true, clickable: true, borderColor:'#000',borderWidth:1},xaxis:{ticks:hourlist},series:{lines:{fill:true, show: true}, points:{ show: true,}}});
							
								ct_ad(data);
					}
				  });
}
function mchart(authurl){
	$.ajax({
		  url: authurl,
	        type: "get",
			data:{
				'mode':'today'
				
				},
			dataType:'json',
			success:function(data){
					data=eval(data);
					var bt_total=[];
					var bt_reg=[];
					var bt_phone=[];
					data=eval(data);
					for(var vo in data){
						bt_total.push([data[vo].t,data[vo].totalcount]);
						bt_reg.push([data[vo].t,data[vo].regcount]);
						bt_phone.push([data[vo].t,data[vo].phonecount]);
					}
					var dataset=[{label:"注册总人数",data:bt_total},{label:"帐号注册",data:bt_reg},{label:"手机注册",data:bt_phone}];
					$.plot($("#userchart"), dataset, {grid: { hoverable: true, clickable: true, borderColor:'#000',borderWidth:1},xaxis:{ticks:hourlist},series:{lines:{fill:true, show: true}, points:{ show: true,}}});
				ct_user(data);	
			}
		  });
}
$(function(){
	
	
		authchart('<?php echo U('index/getauthrpt');?>');
		shopad('<?php echo U('admanage/adrpt');?>');
		mchart('<?php echo U('index/getuserchart');?>');
	
		adchar('<?php echo U('pushadv/rpt');?>');
		
	
	//
	maruti.peity();

});
</script>

</body>
</html>