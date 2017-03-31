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
	<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=18E7F3FA5b3d576acdfafbee1f491217"></script>
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
<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="<?php echo U('user/index');?>" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a></div>
  <h1>网站设置</h1>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
  <hr>
  <div class="row-fluid">
    <div class="span8">
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>编辑</h5>
        </div>
        <div class="widget-content nopadding">
        <form action="<?php echo U('index/web/doset');?>" method="post" class="form-horizontal">

            <div class="control-group">
              <label class="control-label">网站名称 :</label>
              <div class="controls">
                <input type="text" class="span11" placeholder="网站名称"  name="shopname" id="shopname" value="<?php echo ($wapinfo["shopname"]); ?>" />
              	 <span class="help-block">微官网名称，显示在微官网顶部。</span> 
              </div>
            </div>
          
            <div class="control-group">
              <label class="control-label">联系电话 :</label>
              <div class="controls">
                <input type="text"  class="span11" placeholder="联系电话"  name="tel" id="tel" value="<?php echo ($wapinfo["tel"]); ?>" maxlength="15"/>
              </div>
            </div>

            <div class="control-group">
              <label class="control-label">联系地址 :</label>
              <div class="controls">
                <input type="text" class="span11" placeholder="店铺地址 " name="address" id="address" value="<?php echo ($wapinfo["address"]); ?>"/>
             
              </div>
            </div>
             <div class="control-group">
              <label class="control-label">地图标识 :</label>
              <div class="controls">
               <input type="text" class="span4" placeholder="输入地址查询 " name="mapsearch" id="mapsearch" value="<?php echo ($wapinfo["address"]); ?>"/><button type="button" class="btn btn-info" onclick="GetAdd();">查询</button></br></br>
                <div id="allmap" style="width:600px;height:500px;"></div>
              </div>
             </div>
              <div class="control-group">
              <label class="control-label">坐标 :</label>
              <div class="controls">
                <input type="text" class="span6" placeholder="坐标 " name="point" id="point" readonly="true" value="<?php echo ($wapinfo["point_x"]); ?>,<?php echo ($wapinfo["point_y"]); ?>"/>
              
              </div>
            </div>
            <div class="form-actions">
            <input type="hidden" name="point_x" id="point_x" readonly="true" value="<?php echo ($wapinfo["point_x"]); ?>"/>
                <input type="hidden"  name="point_y" id="point_y" readonly="true"  value="<?php echo ($wapinfo["point_y"]); ?>"/>
            
              <button type="submit" class="btn btn-success">保存</button>
            </div>
          </form>
        </div>
      </div>
      
      
    </div>
    
  </div>
  
</div>
</div>

<!--end-main-container-part-->

   <!--Footer-part-->
<div class="row-fluid">
  <div id="footer" class="span12"> 2015-2018 &copy; cuckoowifo <a href="<?php echo C('siteurl');?>"><?php echo C('shortsiteurl');?></a><br> <a href="http://www.miitbeian.gov.cn/">京ICP备14017729号</a></div>
</div>
<!--end-Footer-part--> 
<script>

</script>


<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/jquery.min.js"></script> 
<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/jquery.ui.custom.js"></script> 
<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/bootstrap.min.js"></script> 
<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/matrix.js"></script> 


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
<script>
var map = new BMap.Map("allmap");
var px=<?php if(empty($wapinfo["point_x"])): ?>116.403909<?php else: echo ($wapinfo["point_x"]); endif; ?>;
var py=<?php if(empty($wapinfo["point_y"])): ?>39.915156<?php else: echo ($wapinfo["point_y"]); endif; ?>;
var myGeo = new BMap.Geocoder();
map.addControl(new BMap.NavigationControl());  //添加默认缩放平移控件
map.centerAndZoom(new BMap.Point(px, py), 15);
var marker1 = new BMap.Marker(new BMap.Point(px, py));  // 创建标注
marker1.enableDragging();    //可拖拽
// 将标注添加到地图中
marker1.addEventListener("click", showInfo);
marker1.addEventListener("dragend",showInfo);
function showInfo(e){
 //alert(e.point.lng + ", " + e.point.lat);
 $("#point_x").val(e.point.lng);
 $("#point_y").val(e.point.lat);
 var s=""+e.point.lng+","+e.point.lat+"";
 $("#point").val(s);
}
map.addOverlay(marker1);   
map.addEventListener("click", function(e){
	  	marker1.setPosition(e.point);
	    $("#point_x").val(e.point.lng);
	    $("#point_y").val(e.point.lat);
	    var s=""+e.point.lng+","+e.point.lat+"";
	    $("#point").val(s);
});
function GetAdd(){
	add=$('#mapsearch').val();
	myGeo.getPoint(add, function(point){
		  if (point) {
		    map.centerAndZoom(point, 16);
		    marker1.setPosition(point);
		    $("#point_x").val(point.lng);
		    $("#point_y").val(point.lat);
		    var s=""+point.lng+","+point.lat+"";
		    $("#point").val(s);
		  }else{
			  alert('没有找到匹配的坐标信息,请检查搜索的地址是否存在');
		  }
	}, "全国");
}

$(document).ready(function(){
	
	$('#web3g').trigger('click');
});
</script>
</body>
</html>