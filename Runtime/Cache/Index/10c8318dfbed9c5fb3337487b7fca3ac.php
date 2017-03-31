<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html class="login-bg">
<head>
	<title><?php echo C('sitename');?>--代理商登录</title>
	<meta name="keywords" content="<?php echo C('keyword');?>"/>
	<meta name="description" content="<?php echo C('content');?>"/>
    
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
    <link href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/lib/font-awesome.css" type="text/css" rel="stylesheet" />

    
    <!-- this page specific styles -->
    <link rel="stylesheet" href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/compiled/signin.css" type="text/css" media="screen" />

    
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<body>


    

    <div class="row-fluid login-wrapper">
        <a href="http://www.mbmkt.com" target="_blank">
            <img class="logo" src="<?php echo ($Theme['P']['img']); ?>/wifilogo-mini.png" />
        </a>

        <div class="span4 box">
            <div class="content-wrap">
                <h6>代理商登录</h6>
                 <div class="alert " style="display: none;">
						  <span id="alertmsg"></span>
					</div>
                <input class="span12" type="text" name="txt_user" id="txt_user" placeholder="登录帐号" />
                <input class="span12" type="password" name="password" id="password" placeholder="密码" />
                
                
                <a class="btn-glow primary login" href="javascript:void(0);" id="btn_log">登录</a>
            </div>
        </div>

        <div class="span4 no-account">

        </div>
    </div>
	<!-- scripts -->
    <script src="<?php echo ($Theme['P']['js']); ?>/jquery.js"></script>
    <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/bootstrap.min.js"></script>
    <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/theme.js"></script>
        <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/common.js"></script>
    <!-- pre load bg imgs -->
    <script type="text/javascript">
    document.onkeydown=function(event){
  	  e = event ? event :(window.event ? window.event : null);
  	  if(e.keyCode==13){
  	   //执行的方法
  	  	$('#btn_log').click();
  	  }
  	 }
        $(function () {
            // bg switcher
            var $btns = $(".bg-switch .bg");
            $btns.click(function (e) {
                e.preventDefault();
                $btns.removeClass("active");
                $(this).addClass("active");
                var bg = $(this).data("img");

                $("html").css("background-image", "url('<?php echo ($Theme['P']['root']); ?>/bootadmin/img/bgs/" + bg + "')");
            });


            $('#btn_log').bind('click',function(){
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
					  	url: '<?php echo U('index/index/doagentlog');?>',
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