<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo C('sitename');?>--会员中心</title>
<meta name="keywords" content="<?php echo C('keyword');?>" />
<meta name="description" content="<?php echo C('content');?>" />
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="<?php echo ($Theme['P']['root']); ?>/matrix/css/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo ($Theme['P']['root']); ?>/matrix/css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="<?php echo ($Theme['P']['root']); ?>/matrix/css/matrix-style.css" />
<link rel="stylesheet" href="<?php echo ($Theme['P']['root']); ?>/matrix/css/matrix-media.css" />
<link href="<?php echo ($Theme['P']['root']); ?>/matrix/font-awesome/css/font-awesome.css" rel="stylesheet" />
<link href="<?php echo ($Theme['P']['root']); ?>/font/googlefont.css" rel="stylesheet" />
<link rel="stylesheet"
	href="<?php echo ($Theme['P']['root']); ?>/matrix/css/uniform.css" />
<link rel="stylesheet"
	href="<?php echo ($Theme['P']['root']); ?>/matrix/css/select2.css" />
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
		<div id="breadcrumb">
			<a href="<?php echo U('user/index');?>" title="返回首页" class="tip-bottom">
				<i class="icon-home"></i>首页
			</a> 
			<a href="#" class="current">应用设置</a>
		</div>
		<h1>应用设置</h1>
	</div>
	<div class="container-fluid">
		<hr>
		<div class="row-fluid">
			<div class="span8">
				<div class="widget-box">
					<div class="widget-title">
					<span class="icon"> 
						<i class="icon-align-justify"></i>
					</span>
					<h5>编辑</h5>
				</div>
				<div class="widget-content nopadding">
				<form name='form' action="<?php echo U('index/User/doapp');?>" method="post" class="form-horizontal" enctype="multipart/form-data">
					<div class="control-group">
						<div class="span1"></div>
						<div class="alert alert-block span10 hide" id="msgbox">
							<h4 class="alert-heading">提示信息</h4>
							<div id="alertmsg"></div>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" style="padding-top:10px">认证模式:</label>
						<div class="controls">
							<?php if(is_array($authmode)): $i = 0; $__LIST__ = $authmode;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><label style="float:left; line-height:20px;padding-right:10px;">
									<input id="check_<?php echo ($vo["key"]); ?>" onclick="getcheck(this)" type="checkbox" value="<?php echo ($vo["key"]); ?>"name="authmode[]"<?php echo showauthcheck($vo['key'],$info['authmode']);?>/><?php echo ($vo["txt"]); ?>
								</label><?php endforeach; endif; else: echo "" ;endif; ?>
						</div>
					</div>
					<!-- 手机认证 -->
					<div class="control-group" id="phoneauth">
						<label class="control-label">手机认证方式选择:</label>
						<div class="controls" >
							<label style="float:left;padding-right:10px;"> 		<input type="radio" value="0" name="smsstatus"<?php if(($info['smsstatus']) == "0"): ?>checked<?php endif; ?>>虚拟信息认证 
							</label> 
							<label> <input type="radio" value="1" name="smsstatus"<?php if(($info['smsstatus']) == "1"): ?>checked<?php endif; ?>>短信认证 
							</label> 
							<span class="help-block">请选择手机信息验证方式</span>
						</div>
						<label  class="control-label"> 企业签名:</label>
						<div class="controls">
							<input type="text" class="span11"placeholder="请输入企业签名 " name="companyname" id=" companyname"value="<?php echo ($info['companyname']); ?>" />
						</div>
						<label  class="control-label"> 短信认证内容模板:</label>
						<div class="controls">
							<input type="text" class="span11"placeholder="请输入企业签名 " name="authmsg" id=" authmsg"value="<?php echo ($info['authmsg']); ?>" />
							<span class="help-block">【*】代表认证码</span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">微信号:</label>
						<div class="controls">
							<input type="text" class="span11"  placeholder="微信号" name="wx" id="wx" value="<?php echo ($info['wx']); ?>" /> 
							<span class="help-block">请输入您想让用户关注的微信名称</span>
						</div>
						<label class="control-label">微信关注链接:</label>
						<div class="controls">
							<input type="text" class="span11"  placeholder="微信关注链接" name="wxurl" id="wxurl" value="<?php echo ($info['wxurl']); ?>" /> 
							<span class="help-block">请输入您想让用户关注的微信号链接；例如：http://www.baidu.com；注意：微信链接必须以<span style="color:#c40000">" http:// "</span>开头</span>
						</div>
						<label class="control-label">上传微信二维码图片:</label>
						<div class="controls">
							<input type="file" class="span11"  placeholder="上传微信二维码图片" name="img" id="img" value="<?php echo ($info['codeimg']); ?>" /> 
							<img src="<?php echo ($info['codeimg']); ?>" alt="微信二维码图片" style="width:50px">
							<span class="help-block">请上传你想让用户关注的微信的二维码图片
							<span style="color:#000000">注：上传图片大小(<3M)，允许上传图片类型 " jpg、gif、png、jpeg "</span>
							</span>
						</div>
					</div>
				<!-- <div class="control-group" id="wxauth">
					<label class="control-label">微信认证密码 :</label>
					<div class="controls">
						<input type="text" class="span11"placeholder="微信认证密码 " name="wxauthpwd" id="wxauthpwd"value="<?php echo echojsonkey(showauthdata('3',$info['authmode']),'pwd');?>" />
						<span class="help-block">输入微信认证上网的认证密码</span>
					</div>
				</div> -->
					<div class="control-group">
						<label class="control-label">上网时段控制:</label>
						<div class="controls">
							<select name="sh" id="sh" class="span3">
								<?php $__FOR_START_1494413756__=0;$__FOR_END_1494413756__=24;for($i=$__FOR_START_1494413756__;$i < $__FOR_END_1494413756__;$i+=1){ ?><option value="<?php echo ($i); ?>"<?php if(($info['sh']) == $i): ?>selected<?php endif; ?>><?php echo ($i); ?>:00点</option><?php } ?>
							</select> 
							<label class="span1" style="text-align:center;margin-bottom:0px;line-height:30px">到</label> 
							<select name="eh" id="eh" class="span3">
								<?php $__FOR_START_1414865538__=1;$__FOR_END_1414865538__=24;for($i=$__FOR_START_1414865538__;$i < $__FOR_END_1414865538__;$i+=1){ ?><option value="<?php echo ($i); ?>"<?php if(($info['eh']) == $i): ?>selected<?php endif; ?>><?php echo ($i); ?>:00点</option><?php } ?>
							</select>
							<div class="span5"></div>
							<span class="span12 help-block" style="margin-left:0px;line-height:30px">允许用户上网的时间范围。注:比如 7:00~20:00点</span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" style="padding-top:10px">上网限制:</label>
						<div class="controls">
							<label style="float:left;padding-right:10px"> <input type="radio" value="1" name="countflag"<?php if(($info['countflag']) == "1"): ?>checked<?php endif; ?>>启用 
							</label> 
							<label style="float:left;padding-right:10px"> <input type="radio" value="0" name="countflag"<?php if(($info['countflag']) == "0"): ?>checked<?php endif; ?>>停用 
							</label> 
							<span class="help-block">上网限制,可有效防止员工长时间占用无线网络</span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">上网允许认证次数:</label>
						<div class="controls">
							<input type="text" class="span11" placeholder="请输入认证次数" name="maxcount" id="countmax" value="<?php echo ($info['maxcount']); ?>" /> 
							<span class="help-block">
						本日允许使用wifi的次数（在启用上网限制功能后有效）
							</span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">上网时间限制:</label>
						<div class="controls">
						<input type="text" class="span11"placeholder="请输入时间(单位:分钟)" name="timelimit" id="timelimit" value="<?php echo ($info['timelimit']); ?>" /> 
						<span class="help-block">允许用户上网的时间(单位:分钟)。<span style="color:#000000">注:不限制时间请填:0；上网时间最短设置为10分钟</span></span>
						<span id="spanmsg" style="color:#c40000;"></span>
					</div>
				</div>
					<div class="control-group">
						<label class="control-label" style="padding-top:10px">认证后行为:</label>
						<div class="controls">
							<label style="float:left;padding-right:10px;"> 
							<input type="radio" value="1" name="authaction"<?php if(($info['authaction']) == "1"): ?>checked<?php endif; ?>>跳转指定网页 
							</label> 
							<label style="float:left;padding-right:10px;"> 
								<input type="radio" value="0" name="authaction"<?php if(($info['authaction']) == "0"): ?>checked<?php endif; ?>>不跳转
							</label> 
							<label style="float:left;padding-right:10px;"> 
								<input type="radio" value="2" name="authaction"<?php if(($info['authaction']) == "2"): ?>checked<?php endif; ?>>跳转请求网页 
							</label> 
							<label style="float:left;padding-right:10px;"> 
								<input type="radio" value="3" name="authaction"<?php if(($info['authaction']) == "3"): ?>checked<?php endif; ?>>跳转到微官网 
							</label> 
							<span class="help-block">用户通过认证后引导用户行为选择。</span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">指定跳转URL:</label>
						<div class="controls">
							<input type="text" class="span11" placeholder="跳转网页地址 " name="jumpurl" id="jumpurl" value="<?php echo ($info['jumpurl']); ?>" />
						</div>
					</div>
					<div class="control-group" id="notice">
						<label class="control-label">公告:</label>
						<div class="controls">
							<input type="text" class="span11" placeholder="公告" name="notice" id="bgnnotice" value="<?php echo ($info['notice']); ?>" />
							<span class="help-block">在认证页滚动显示</span>
						</div>
					</div>
					<div class="form-actions">
						<input type="submit" class="btn btn-success" name="sub" value="保存" />
					</div>
				</form>
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
<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/jquery.ui.custom.js"></script>
<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/bootstrap.min.js"></script>
<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/matrix.js"></script>
<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/jquery.uniform.js"></script>
<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/select2.min.js"></script>
<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/common.js"></script>
<script>
	function getcheck(obj){
		//alert(key);
		var x=obj.value;
		if(x == 3 || x == 4){
		var checkedChoose = obj.parentNode.className;
		if(x == 3 && checkedChoose == ''){
			var checkedBox4 = document.getElementById("check_4");
			checkedBox4.parentNode.className = '';
			checkedBox4.checked = false;
			obj.checked = true;
		}else if(x == 4 && checkedChoose == ''){
			var checkedBox3 = document.getElementById("check_3");
			checkedBox3.parentNode.className = '';
			checkedBox3.checked = false;
			obj.checked = true;
		}
		if(checkedChoose == ''){
			checkedChoose = 'checked';
		}else{
			checkedChoose = '';
		}
		}
	}


$(document).ready(function(){
	$('input[type=checkbox],input[type=radio],input[type=file]').uniform();
	
	$('select').select2();

	$("input[name='authmode[]']").each(function(){
		if($(this).attr('checked')&&$(this).val()==1){
			$('#phoneauth').show();
			$('#wxacc').show();
		}
		$(this).bind('click',function(){
				
				if($(this).attr('checked')&&$(this).val()==1){
						$('#phoneauth').show();
						$('#wxacc').show();
				}else if(!$(this).attr('checked')&&$(this).val()==1){
						$('#phoneauth').hide();
						$('#wxacc').hide();
				}
		});

		if($(this).attr('checked')&&$(this).val()==3){
			$('#wxauth').show();
			$('#wxacc').show();
		}
		$(this).bind('click',function(){
				
				if($(this).attr('checked')&&$(this).val()==3){
						$('#wxauth').show();
						$('#wxacc').show();
				}else if(!$(this).attr('checked')&&$(this).val()==3){
						$('#wxauth').hide();
						$('#wxacc').hide();
				}
		});

	});

	$("input[name='sub']").bind('click',function(){
		var rs=true;
		$("input[name='authmode[]']").each(function(){
			// 手机认证
			if($(this).attr('checked')&&$(this).val()==1){
				var code=$('#code').val();
				if (code==""||code==0){
					AlertTips("请输入手机认证位数",2000);
					rs= false;
				}
			}
			// 微信认证
			if($(this).attr('checked')&&$(this).val()==3){
				
					 var v=$('#wxauthpwd').val();
						
					 if (v == "") {
						
						 AlertTips("请输入微信认证密码",2000);
					        rs= false;
					 }
					 if(!isaccount(v)){
						
						 AlertTips("请输入微信认证密码",2000);
					        rs= false;
					 }
					 var wx=$('#wx').val();
						
					 if (wx == "") {
						
						 AlertTips("请输入微信账号",2000);
					        rs= false;
					 }
				
				}
			// 微信关注认证 
			if($(this).attr('checked')&&$(this).val()==4){
				 var wx=$('#wx').val();
					
				 if (wx == "") {
					
					 AlertTips("请输入微信账号",2000);
				        rs= false;
				 }
			
			}


		});
			
		return rs;
	});
	$('input[name="timelimit"]').blur(function(event) {
		// 上网时间限制
		var timelimit=$(this).val();
			timelimit=timelimit*1;
			$('#spanmsg').html('');
		if(1<=timelimit && timelimit<10){
			$('#spanmsg').html('上网时间限制至少设置10分钟');
		}else{
			$('#spanmsg').html('');
		}
	});
	
});
</script>
</body>
</html>