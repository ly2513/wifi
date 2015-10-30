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
<link rel="stylesheet" href="<?php echo ($Theme['P']['root']); ?>/matrix/css/uniform.css" />

<link  href="<?php echo ($Theme['P']['root']); ?>/kindeditor/themes/default/default.css" type="text/css" rel="stylesheet">

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
  <div id="breadcrumb"> <a href="<?php echo U('user/index');?>" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" class="current">广告编辑</a> </div>
  <h1>广告编辑</h1>
</div>
<div class="container-fluid">
  <hr>
  <div class="row-fluid">
    <div class="span8">
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>编辑</h5>
        </div>
        <div class="widget-content nopadding">

 		<form class="form-horizontal" name='form' action="<?php echo U('user/doeditad');?>" method="post" enctype="multipart/form-data">
        	 
       		<div class="control-group">
       		<div class="span1"></div>
       			<div class="alert alert-block span10 hide" id="msgbox"> 
              <h4 class="alert-heading">提示信息</h4>
             <div id="alertmsg"></div>
              </div>
       		</div>
		
            <div class="control-group">
              <label class="control-label"> 广告类别：</label>
              <div class="controls">
               
              
                <label>
                 <input type="radio" name="mode" value="0" <?php if(($info['mode']) == "0"): ?>checked<?php endif; ?>>图片广告
                 </label>
                            <label>
                   <input type="radio" name="mode" value="1" <?php if(($info['mode']) == "1"): ?>checked<?php endif; ?>>图文广告
                 </label>          
                 <span class="help-block">图片广告:仅向客户展示广告图片。图文广告:客户点击广告图片，会显示改广告相应的文章内容。</span> 
               
              </div>
            </div>
            <div class="control-group"  id="wxacc">
              <label class="control-label">图文标题 :</label>
              <div class="controls">
                <input type="text" class="span5" placeholder="图文标题 "  name="title" id="title" value="<?php echo ($info["title"]); ?>" />
              	 <span class="help-block">选择图文广告是，请输入相应的文章标题和内容。</span> 
              </div>
            </div>
            <div class="control-group"  id="wxacc">
              <label class="control-label">图文内容 :</label>
              <div class="controls">
                <textarea class="textarea_editor span11" rows="20" placeholder="输入内容 ..." name="info"><?php echo (htmlspecialchars_decode($info["info"])); ?></textarea>
              </div>
            </div>
             <div class="control-group"  id="wxacc">
              <label class="control-label">广告图片:</label>
              <div class="controls">
                <img src="<?php echo ($info['ad_thumb']); ?>" style="width:200px;"/>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">上传图片:</label>
              <div class="controls">
                 <input type="file"  name="img">
                
              </div>
              <div class="controls">
              <span class="help-block blue span8">建议上传图片规格:720*480</span>
                
              </div>
              
            </div>
            <div class="control-group" id="wxauth" >
              <label class="control-label">排序 :</label>
              <div class="controls">
                <input type="text" class="span2" placeholder="数值越小越靠前 " name="ad_sort" id="ad_sort" value="<?php echo ($info["ad_sort"]); ?>"/>
              </div>
            </div>
            
       
            <div class="form-actions">
             <input type="hidden" name="id" value="<?php echo ($info["id"]); ?>" />
                   <input type='hidden' name='adthu' value="<?php echo ($info["ad_thumb"]); ?>" />
              <input type="submit" class="btn btn-success" name="sub" value="保存"/>&nbsp;&nbsp;
              <a href="<?php echo U('User/adv');?>" class="btn" id="btn-back">返回</a>
            </div>
          </form>
        </div>
      </div>
      
      
    </div>
    
  </div>
  
</div></div>
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
<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/jquery.uniform.js"></script> 
<script src="<?php echo ($Theme['P']['root']); ?>/kindeditor/kindeditor-min.js" type="text/javascript"></script>
	<script src="<?php echo ($Theme['P']['root']); ?>/kindeditor/lang/zh_CN.js" type="text/javascript"></script>
<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/common.js"></script> 
<script>
KindEditor.ready(function(K) {
	K.create('textarea[name="info"]', {
		autoHeightMode : true,
		items:['source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template',  'cut', 'copy', 'paste',
		        'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
		        'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
		        'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
		        'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
		        'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 
		        'flash', 'media', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
		        'anchor', 'link', 'unlink', '|', 'about'],
		        afterUpload:false,
		        allowFlashUpload:false,
		        allowFileUpload:false,
		        allowImageUpload:false,
		        allowMediaUpload:false,
	});
});

$(document).ready(function(){
	$('input[type=checkbox],input[type=radio],input[type=file]').uniform();
});
</script>
</body>
</html>