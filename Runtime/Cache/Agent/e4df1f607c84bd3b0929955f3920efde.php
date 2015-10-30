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
        <div id="breadcrumb">
            <a href="<?php echo U('index/index');?>" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="#" class="current">商户广告统计</a>
        </div>
        <h1>商户广告统计</h1>
    </div>
    <!--End-breadcrumbs-->
    <div class="container-fluid" >
        <hr>
        <div class="row-fluid">
            <div class="alert alert-block span8 hide" id="msgbox">
            	<h4 class="alert-heading">提示信息</h4>
             	<div id="alertmsg"></div>
            </div>
            <div class="controls controls-row span8">
            	<label class="control-label span1">开始日期</label>
   				<input type="text" id="sdate" value="<?php echo date("Y-m-01") ?>" data-date-format="yyyy-mm-dd" class="span2 datepicker" readonly="readonly">
     			<label class="control-label span1">结束日期</label>
   				<input type="text" id="edate" value="<?php echo date("Y-m-d") ?>" data-date-format="yyyy-mm-dd" class="span2 datepicker" readonly="readonly">
    			<a class="btn btn-success span1" id="query">查询</a>&nbsp;
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
            			<h5>统计图表</h5>
            		</div>
            		<div class="widget-content">
          				<div id="placeholder" ></div>
          			</div>
        		</div>
        	</div>
        </div>
        <div class="row-fluid">
     		<div class="span12">
       			<div class="widget-box">
       				<div class="widget-title"> <span class="icon"> <i class="icon-th"></i></span>
            			<h5>统计列表</h5>
          			</div>
          			<div class="widget-content nopadding">
          				<table class="table table-bordered table-striped">
              				<thead>
                				<tr>
			                		<th>统计日期</th>
				        			<th>广告展示次数</th>
				        			<th>点击次数</th>
				        			<th>点击率</th>
                				</tr>
              				</thead>
              				<tbody id="gridbox">
              				</tbody>
             			</table>
          			</div>
          			<div class="">
	            		<?php echo ($page); ?>
	            	</div>
       			</div>
       		</div>
       	</div>
    </div>
</div>

<script type="text/javascript">
  // This function is called from the pop-up menus to transfer to
  // a different page. Ignore if the value returned is a null string:
  function goPage (newURL) {

      // if url is empty, skip the menu dividers and reset the menu selection to default
      if (newURL != "") {
      
          // if url is "-", it is this page -- reset the menu:
          if (newURL == "-" ) {
              resetMenu();            
          } 
          // else, send page to designated URL            
          else {  
            document.location.href = newURL;
          }
      }
  }

// resets the menu selection upon entry to this page:
function resetMenu() {
   document.gomenu.selector.selectedIndex = 2;
}

function shownotice(t,obj){
    $('#notice-title').text(t);
    $('#notice-info').html($(obj).parent().next().html());
    $('#noticebox').modal({backdrop:false,show:true});
}
</script>




 <div style="display: none">
     
      </div>
	<!-- scripts -->
<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/jquery.min.js"></script> 
 <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/bootstrap.min.js"></script>

    <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/bootstrap.datepicker.js"></script> 
    <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/theme.js"></script>
     <script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/matrix.js"></script> 
  <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/common.js"></script>
<script src="<?php echo ($Theme['P']['js']); ?>/flot/jquery.flot.js"></script> 
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="<?php echo ($Theme['P']['js']); ?>/flot/excanvas.min.js"></script><![endif]-->  
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


var lines;

$(function () {
	
	var stack = 0, bars = true, lines = false, steps = false;
	
	    
	 $('.datepicker').datepicker();
  	  $("#today").bind("click",function(){
		  $.ajax({
			  url: '<?php echo U('admanage/adrpt');?>',
		        type: "get",
				data:{
					'mode':'today',
					
					},
				dataType:'json',
				error:function(){
						AlertTips("服务器忙，请稍候再试",2000);
						},
				success:function(data){
						var bt=[];
						var bt_hit=[];
						data=eval(data);
						for(var vo in data){
							
							bt.push([data[vo].t,data[vo].showup]);
							bt_hit.push([data[vo].t,data[vo].hit]);
						}
						var dataset=[{label:"广告展示",data:bt},{label:"广告点击",data:bt_hit}]
						 $.plot($("#placeholder"), dataset, {grid: { hoverable: true, clickable: true, borderColor:'#000',borderWidth:1},xaxis:{ticks:hourlist},series:{lines:{fill:true, show: true}, points:
						    { show: true,
						    	  }}});
						rendertable(data);
						
				}
			  });
  	  	  });

  	 $("#yestoday").bind("click",function(){
		  $.ajax({
			  url: '<?php echo U('admanage/adrpt');?>',
		        type: "get",
				data:{
					'mode':'yestoday',
					
					},
				dataType:'json',
				error:function(){
						AlertTips("服务器忙，请稍候再试",2000);
						},
				success:function(data){
						var bt=[];
						var bt_hit=[];
						data=eval(data);
						for(var vo in data){
							
							bt.push([data[vo].t,data[vo].showup]);
							bt_hit.push([data[vo].t,data[vo].hit]);
						}
						var dataset=[{label:"广告展示",data:bt},{label:"广告点击",data:bt_hit}];
						 $.plot($("#placeholder"), dataset, {grid: { hoverable: true, clickable: true, borderColor:'#000',borderWidth:1},xaxis:{ticks:hourlist},series:{lines:{fill:true, show: true}, points:
						    { show: true,
						    	  }}});
						 rendertable(data);
						
				}
			  });
 	  	  });
	  	  
  		$("#week").bind("click",function(){
		  $.ajax({
			  url: '<?php echo U('admanage/adrpt');?>',
		        type: "get",
				data:{
					'mode':'week',
					
					},
				dataType:'json',
				error:function(){
						AlertTips("服务器忙，请稍候再试",2000);
						},
				success:function(data){
						var bt=[];
						data=eval(data)  ;
						var templist=[];
						var bt_hit=[];
						for(var vo in data){
 							templist.push([data[vo].t,data[vo].td]);
							bt.push([data[vo].t,data[vo].showup]);
							bt_hit.push([data[vo].t,data[vo].hit]);
						}
						var dataset=[{label:"广告展示",data:bt},{label:"广告点击",data:bt_hit}];
						 $.plot($("#placeholder"), dataset, {grid: { hoverable: true, clickable: true, borderColor:'#000',borderWidth:1},xaxis:{ticks:templist},series:{lines:{fill:true, show: true}, points:
						    { show: true,
						    	  }}});
						 rendertable(data);
				}
			  });
	  	  });
  		$("#month").bind("click",function(){
  		  $.ajax({
  			  url: '<?php echo U('admanage/adrpt');?>',
  		        type: "get",
  				data:{
  					'mode':'month',
  					
  					},
  				dataType:'json',
  				error:function(){
  						AlertTips("服务器忙，请稍候再试",2000);
  						},
  				success:function(data){
  						var bt=[];
  						var bt_hit=[];
  						data=eval(data)  ;
  						for(var vo in data){
  							
  							bt.push([data[vo].t,data[vo].showup]);
  							bt_hit.push([data[vo].t,data[vo].hit]);
  						}
  						var dataset=[{label:"广告展示",data:bt},{label:"广告点击",data:bt_hit}];
  						 $.plot($("#placeholder"), dataset, {grid: { hoverable: true, clickable: true, borderColor:'#000',borderWidth:1},xaxis:{ticks:daylist},series:{lines:{fill:true, show: true}, points:
  						    { show: true,
  						    	  }}});
  						rendertable(data);
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
	  			  url: '<?php echo U('admanage/adrpt');?>',
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
	  						var bt=[];
	  						var templist=[];
	  						var bt_hit=[];
	  						data=eval(data)  ;
	  						for(var vo in data){
	  							templist.push([data[vo].t,data[vo].td]);
	  							bt.push([data[vo].t,data[vo].showup]);
	  							bt_hit.push([data[vo].t,data[vo].hit]);
	  						}
	  						
	  						var dataset=[{label:"广告展示",data:bt},{label:"广告点击",data:bt_hit}];
	  						 var plot= $.plot($("#placeholder"), dataset, {xaxis:{ticks:templist},  grid: { hoverable: true, clickable: true, borderColor:'#000',borderWidth:1}, series:{lines:{fill:true, show: true}, points:
	  						    { show: true,
	  						    	  }}});
	  					
	  						rendertable(data);
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
                  
              maruti.flot_tooltip(item.pageX, item.pageY, y+"次");
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

function rendertable(data){
	
	$("#gridbox").empty();
	var trHtml="";
	var sumshow=0;
	var sumhit=0;
	for(var vo in data){
		trHtml+="<tr>";
		trHtml+="<td>"+data[vo].showdate+"</td>";
		trHtml+="<td>"+data[vo].showup+"次</td>";
		trHtml+="<td>"+data[vo].hit+"次</td>";
		trHtml+="<td>"+data[vo].rt+"%</td>";
		trHtml+="</tr>";
		sumshow+=parseFloat( data[vo].showup);
		sumhit+=parseFloat(data[vo].hit);
	}
	trHtml+="<tr>";
	trHtml+="<td>合计：</td>";
	trHtml+="<td>"+sumshow+"次</td>";
	trHtml+="<td>"+sumhit+"次</td>";
	trHtml+="<td>"+(sumhit/sumshow)+"%</td>";
	trHtml+="</tr>";
	$("#gridbox").append(trHtml);
}
</script>
<script type="text/javascript"> 
$(document).ready(function(){
	$('#adman').trigger('click');
});
</script>
</body>
</html>