<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html class="login-bg">
<head>
	<title><?php echo C('sitename');?>--管理平台</title>
    
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	
    <!-- bootstrap -->
    <link href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/bootstrap/bootstrap.css" rel="stylesheet" />
    <link href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/bootstrap/bootstrap-responsive.css" rel="stylesheet" />
    <link href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/bootstrap/bootstrap-overrides.css" type="text/css" rel="stylesheet" />

    <!-- global styles -->
    <link rel="stylesheet" type="text/css" href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/layout.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/elements.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/icons.css" />

    <!-- libraries -->
    <link rel="stylesheet" type="text/css" href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/lib/font-awesome.css" />
    
    <!-- this page specific styles -->
    <link rel="stylesheet" href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/compiled/signup.css" type="text/css" media="screen" />

    <!-- open sans font -->
    <link href='http://fonts.useso.com/css?family=Open+Sans:300italic,400italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css' />

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<body>
    <div class="header">
        <a href="index.html">
            <img src="<?php echo ($Theme['P']['img']); ?>wifilogo-mini.png" class="logo" />
        </a>
    </div>
    <div class="row-fluid login-wrapper">
        <div class="box">
            <div class="content-wrap">
                <h6>管理平台</h6>
                 <div class="alert " style="display: none;">
						  <span id="alertmsg"></span>
					</div>
                   <input class="span12" type="text" name="txt_user" id="txt_user" placeholder="登录帐号" />
                <input class="span12" type="password" name="password" id="password" placeholder="密码" />
              
                <div class="action" >
                    <a class="btn-glow primary signup" id="btn_login" href="javascript:void(0);">登录</a>
                </div>                
            </div>
        </div>

        <div class="span4 already">
            
        </div>
    </div>
	<!-- scripts -->
    <script src="<?php echo ($Theme['P']['js']); ?>/jquery.js"></script>
    <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/bootstrap.min.js"></script>
    <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/theme.js"></script>
            <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/common.js"></script>
    <script>
    function refresh(){
		$('#imgCheckB').attr("src","<?php echo U('login/verify');?>");
		
	}
    document.onkeydown=function(event){
  	  e = event ? event :(window.event ? window.event : null);
  	  if(e.keyCode==13){
  	   //执行的方法
  	  	$('#btn_login').click();
  	  }
  	 }
    $(function () {
        // bg switcher
 

        $('#btn_login').bind('click',function(){
			var u=$('#txt_user').val();
			var p=$('#password').val();
			
			 if (u == "") {
				 AlertTips( "请输入帐号",  1000);
			        return false;
			 }
			  if (p == "") {
				
				  AlertTips( "请输入密码",1000);
			        return false;
			  }
			
			  $.ajax({
				  	url: '<?php echo U('login/dologin');?>',
			        type: "post",
					data:{
						'user':u,
						'password':p,
						
						'__hash__':$('input[name="__hash__"]').val()
						},
					dataType:'json',
					error:function(){
							AlertTips("服务器忙，请稍候再试",1500);
							},
					success:function(data){
							
							if(data.error==0){
								location.href=data.url;
								
							}else{
								AlertTips(data.msg, 1500);
							}
					}
				  });
			});
    });
    </script>
</body>
</html>