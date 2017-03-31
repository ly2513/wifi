<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo C('sitename');?>--用户注册</title>
<meta name="keywords" content="<?php echo C('keyword');?>"/>
<meta name="description" content="<?php echo C('content');?>"/>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="<?php echo ($Theme['P']['root']); ?>/matrix/css/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo ($Theme['P']['root']); ?>/matrix/css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="<?php echo ($Theme['P']['root']); ?>/matrix/css/matrix-reg.css" />
<link href="<?php echo ($Theme['P']['root']); ?>/matrix/font-awesome/css/font-awesome.css" rel="stylesheet" />
<link href='http://fonts.useso.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
</head>
  <body>
    <div id="loginbox">     
      <form id="loginform" class="form-vertical" >
        <div class="control-group normal_text"> 
          <h3><img src="<?php echo ($Theme['P']['img']); ?>/wifilogo-mini.png" /></h3>
        </div>
       	<div class="alert hide" id="msgbox">
          <div id="alertmsg"></div>
        </div>
        <div class="control-group">
          <div class="controls">
            <div class="main_input_box">
              <!--[if IE]>
				        <div><span style=" padding:9px 9px;*line-height:31px; font-size:14px;font-weight:bold;color:#fff; width:80px; display:inline-block;">登录帐号:</span><span style="width:75%;display:inline-block;">&nbsp;&nbsp;</span></div>
		          <![endif]-->
              <span class="add-on bg_lg"><i class="icon-user"></i></span><input type="text" placeholder="登录帐号"  name="txt_user" id="txt_user" />
            </div>
          </div>
        </div>
        <div class="control-group">
          <div class="controls">
            <div class="main_input_box">
            <!--[if IE]>
					      <div><span style="padding:9px 9px;*line-height:31px; font-size:14px;font-weight:bold;color:#fff; width:80px; display:inline-block;">登录密码:</span><span style="width:75%;display:inline-block;">&nbsp;&nbsp;</span></div>
			      <![endif]-->
              <span class="add-on bg_ly"><i class="icon-lock"></i></span>
              <input type="password" placeholder="密码" name="password" id="password"/>
            </div>
          </div>
        </div>
        <div class="control-group">
          <div class="controls">
              <div class="main_input_box">
                <!--[if IE]>
					        <div><span style="padding:9px 9px;*line-height:31px; font-size:14px;font-weight:bold;color:#fff; width:80px; display:inline-block;">确认密码:</span><span style="width:75%;display:inline-block;">&nbsp;&nbsp;</span></div>
			          <![endif]-->
                <span class="add-on bg_lo"><i class="icon-star"></i></span><input type="password" placeholder="确认密码" name="repassword" id="repassword"/>
              </div>
          </div>
        </div>
        <div class="control-group">
          <div class="controls">
            <div class="main_input_box">
              <!--[if IE]>
					    <div><span style="padding:9px 9px;*line-height:31px; font-size:14px;font-weight:bold;color:#fff; width:80px; display:inline-block;">商户名称:</span><span style="width:75%;display:inline-block;">&nbsp;&nbsp;</span></div>
			        <![endif]-->
                <span class="add-on bg_lb"><i class="icon-group"></i></span><input type="text" placeholder="商户名称" name="shopname" id="shopname"/>
            </div>
          </div>
        </div>
        <div class="control-group">
          <div class="controls">
              <div class="main_input_box">
              <!--[if IE]>
					      <div><span style="padding:9px 9px;*line-height:31px; font-size:14px;font-weight:bold;color:#fff; width:80px; display:inline-block;">联系人:</span><span style="width:75%;display:inline-block;">&nbsp;&nbsp;</span></div>
			        <![endif]-->
                <span class="add-on bg_ls"><i class="icon-user-md"></i></span><input type="text" placeholder="联系人" name="linker" id="linker"/>
              </div>
          </div>
        </div>
        <div class="control-group">
          <div class="controls">
            <div class="main_input_box">
              <!--[if IE]>
				      <div><span style="padding:9px 9px;*line-height:31px; font-size:14px;font-weight:bold;color:#fff; width:80px; display:inline-block;">手机号码:</span><span style="width:75%;display:inline-block;">&nbsp;&nbsp;</span></div>
		          <![endif]-->
              <span class="add-on bg_lr"><i class="icon-phone"></i></span><input type="text" placeholder="手机号码" name="phone" id="phone"/>
            </div>
          </div>
        </div>
        <div class="form-actions">
          <input type="hidden" name="doact" id="doact" value="doreg"/>
          <span class="pull-center"><a href="#" class="flip-link btn btn-info" href="javascript:void(0);" id="btn_reg">返回登录</a></span>
          <span class="pull-right"><a type="submit" href="javascript:void(0);" class="btn btn-success" id="btn_log" /> 注册</a></span>
        </div>      
        <div class="control-group">
          <div class="main_input_box"> Copyright（c）2015-2018 <?php echo C('shortsiteurl');?> AII Rights Reserved</div>
        </div>
      </form>
    </div>
<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/jquery.min.js"></script>  
<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/common.js"></script> 
<script type="text/javascript">
  document.onkeydown=function(event){
    e = event ? event :(window.event ? window.event : null);
    if(e.keyCode==13){
    	//执行的方法
    	$('#btn_log').click();
    }
  }
  $(function(){
    $('#btn_reg').bind('click',function(){
 			location.href='<?php echo U('index/index/log');?>';
 		});
 		$('#btn_log').bind('click',function(){
			var u=$('#txt_user').val();
			var p=$('#password').val();
			var pc=$('#repassword').val();
			var s=$('#shopname').val();
			var link=$('#linker').val();
			var phone=$('#phone').val();
			var act=$('#doact').val();
			if (u == "") {
				AlertTips("请输入帐号", 2000);
			  return false;
			}
			if (p == "") {
				AlertTips("请输入密码", 2000);
			  return false;
			}
			if(p!=pc){
			  AlertTips("确认密码不正确", 2000);
			  return false;
 			}
 			if(!isaccount(u)){
 				AlertTips("帐号由4～20位数字或字母组成", 2000);
 				return false;
 			}
		  if (s == "") {
			  AlertTips("商户名称不能为空", 2000);
		    return false;
		  }
		  if (link == "") {	
			  AlertTips("联系人不能为空", 2000);
		    return false;
		  }
		  if(!isPhone(phone)){
			  AlertTips("请输入11位手机号码", 2000);
		    return false;
		  }
		  $.ajax({
			  url: '<?php echo U('user/doregist');?>',
		    type: "post",
				data:{
					'account':u,
					'password':p,
					'repassword':pc,
					'shopname':s,
					'linker':link,
					'phone':phone,
					'doact':act,
					'__hash__':$('input[name="__hash__"]').val()
					},
				dataType:'json',
				error:function(){
					Tips("服务器忙，请稍候再试", true, 1500);
				},
				success:function(data){
					if(data.error==0){
						location.href=data.url;
					}else{
						Tips(data.msg, true, 1500);
					}
				}
 			});
 		});
});
</script>
</body>
</html>