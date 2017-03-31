<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html class=" js backgroundsize"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo ($shopinfo[0]['shopname']); ?></title>
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
<link rel="apple-touch-icon-precomposed" href="">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<link href="<?php echo ($Theme['T']['css']); ?>/model1/common.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo ($Theme['T']['css']); ?>/model1/component.css">
<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/jquery.min.js"> type="text/javascript"></script>
<script src="<?php echo ($Theme['P']['js']); ?>/model1/modernizr.custom.js"></script>
</head>

<body>
<input type="hidden" id="ad_show_time" value="<?php echo ($shopinfo[0]['ad_show_time']); ?>"/>
<section id="wrapper" class="wrapper">
   
  <!-- 图片轮播 -->
   <section class="slide-photos">
      <ul class="slides">
       <?php if(!empty($ad)): if(is_array($ad)): $i = 0; $__LIST__ = $ad;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div  class="swiper-slide">
	     		<?php if(($vo['mode']) == "1"): ?><li style="display: list-item; background-image: url(<?php echo ($vo["ad_thumb"]); ?>);"><a href="<?php echo U('userauth/showad',array('id'=>$vo['id']));?>">
		 <img src="<?php echo ($vo["ad_thumb"]); ?>">
		 </a></li>
	     		<?php else: ?>
	     			<li style="display: list-item; background-image: url(<?php echo ($vo["ad_thumb"]); ?>);">
		 <img src="<?php echo ($vo["ad_thumb"]); ?>"></li><?php endif; ?>
	     		</div><?php endforeach; endif; else: echo "" ;endif; ?>   		
	     		<?php else: ?>
	     		<li style="display: list-item; background-image: url(<?php echo ($Theme['P']['root']); ?>/images/ad/noad01.jpg);"></li>
	     		<li style="display: list-item; background-image: url(<?php echo ($Theme['P']['root']); ?>/images/ad/noad02.jpg);"></li><?php endif; ?>	
      
      
      
      
	 </ul>
   </section> <!-- 主体 -->
   <section class="index-wrap">

  <!--自定认证-->
		
		<!--如果有倒计时-->
		<script language="javascript">	
		
			function rptk_oneclick(){
				showPop();
			
			}
		
		
			var speed = 1000; // 速度 一般不用修改		
			var djwait = document.getElementById("ad_show_time").value;; // 停留时间 单位：秒				
			function updateinfo(){					
				if(djwait == 0){
					$("#foot_daojisi").html("<font color='red'>点击免费上网</font>");	
					$("#foot_daojisi").attr("href",'javascript:rptk_oneclick()').removeClass("btn-phone").addClass("btn-go");
				}else{
					$("#foot_daojisi").html ( "<font color='red'>还有"+djwait+"秒</font>");
					djwait--;
					window.setTimeout("updateinfo()",speed);
				}
			}
			updateinfo();
		</script>		<!--倒计时结束-->	<!--按钮判断结束-->


   </section> 
   
   
   <!-- 底部 -->
   <div class="foot-space"></div>
   <footer class="index-foot"><a id="foot_daojisi" class="btn-go"><font color="red">点击免费上网</font></a></footer>
   
   
   <!-- 弹窗 -->
   <section id="popWrap" class="pop-wrap">
      <section id="popMain" class="pop-main">
		  <section class="pop-main-in">
			  <?php if($authmode['open'] == true): if(($authmode['overmax']) == "0"): ?><h4>请认证后上网</h4>
				 <?php if(($authmode['wx_f']) == "1"): ?><!-- <figure class="qr-code-img"><img src="http://wx.xinyiwifi.com/uploads/l/lketel1408253946/0/1/3/7/thumb_53f0420f7f99a.jpg" alt=""></figure> -->
				 
				 <div class="qr-code-txt">关注微信<b><?php echo ($shopinfo[0]['wx']); ?></b>即可上网，打开微信-通讯录-+（右上角）-查找公众账号，输入商家公众账号ID：<?php echo ($shopinfo[0]['wx']); ?>，关注即可上网。</div>
				  <?php if($is_iphone == true): ?><a class="btn-weixin" href="wechat://">关注微信</a><?php endif; endif; ?>
				 <?php if(($authmode['wx']) == "1"): ?><a class="btn-weixin" href="<?php echo U('userauth/wxauth');?>">微信认证</a><?php endif; ?>
				 <?php if(($authmode['allow']) == "1"): ?><a class="btn-phone" href="<?php echo U('userauth/noAuth');?>">一键上网</a><?php endif; ?>
				 <?php if(($authmode['phone']) == "1"): ?><a class="btn-phone" href="<?php echo U('userauth/mobile');?>">手机认证</a><?php endif; ?>
				 <?php if(($authmode['reg']) == "1"): ?><a class="btn-phone" href="<?php echo U('userauth/reg');?>">注册会员</a>
				  <a class="btn-phone" href="<?php echo U('userauth/login');?>">会员登录</a><?php endif; ?>
				 <?php else: ?>
				 <h4>暂不能提供上网服务</h4></br>
			 每日免费上网次数是<?php echo ($shopinfo[0]['countmax']); ?>次<?php endif; ?>
			  <?php else: ?>
			  <h4>当前时间不提供上网服务</h4></br>
			  上网开放时间为每日 <?php echo ($authmode["opensh"]); ?>:00点至<?php echo ($authmode["openeh"]); ?>:00点<?php endif; ?>
		  </section>
		  
      </section>
	  <section id="popMainCode" class="pop-main" style="display:none;">
		<div class="pop-main-in">
			<h4>请认证后上网</h4>
			 <a class="icon-close" href="javascript:" onclick="hidePop()"></a>
		  <div id="sendCode"><input type="text" class="input_phone" id="phone" placeholder="请输入您的手机号码获取认证码！"><br>
				<a href="javascript:sendCode();" class="btn-weixin">提交</a>
			</div>
			<div id="loginform" style="display:none;">
				<input class="input_phone" id="loginkey" type="text" name="token" onfocus="focusStyle()" onblur="blurStyle()" onkeypress="return onKeyPress(event)">
				<button class="btn-weixin" id="loginbutton" onclick="rptk_weixin_submit('loginkey')" style="border:none;">发送验证码</button>
			</div>
			<script>
				function sjrzbox(){
					$("#popMain").hide();
					$("#popMainCode").show();
				}
				function rptk_ios_goto_weixin()(){
					
					
				}
				function sendCode(){
				/**
				$.get("/index.php?g=Home&m=Index&a=sendCode",{id:103,phone:$("#phone").val(),code:7685,rptk_user_mac:'',rptk_key:'',rptk_user_ip:'',wecha_id:'',id:103}, function(result){
					alert(result);					
					$("#sendCode").hide();
					$("#loginform").show();
				 });			*/	
			}
			
			</script>
			</div>
	  </section>
   </section>
   <div id="bodyMask" class="body-mask" onclick="hidePop()"></div>   
   <script>
	  var mask=document.getElementById("bodyMask")
	  var popbox=document.getElementById("popWrap")
	  var popMain=document.getElementById("popMain")
	  //触发弹窗
	  function showPop(){
		  mask.style.display="block"
		  popbox.style.display="block"
		  var boxHeight=popMain.clientHeight
		  var wHeight=window.innerHeight
		  if(wHeight<boxHeight){
			  popbox.style.position="absolute"
		  }
	  }
	  //关闭弹窗
	  function hidePop(){
		  mask.style.display="none";
		  popbox.style.display="none";
		  popbox.style.position="fixed";
		  $("#popMain").show();
			$("#popMainCode").hide();
			$("#sendCode").show();//2014-7-21  手机认证修改
			$("#loginform").hide();//2014-7-21  手机认证修改
      }	
   </script>
   <!--// 弹窗 --> 
</section>

<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo ($Theme['P']['js']); ?>/model1/jquery.flexslider.min.js" type="text/javascript"></script>
<script type="text/javascript">   
//图片轮播
$(window).load(function(){
     $('.slide-photos').flexslider({
        animation: "slide",
		slideshowSpeed:5000,
		animationSpeed:800,
		pauseOnHover:false,
		directionNav: false,
		start: function(slider){
           $('.index-head').removeClass('loading');
        }  
     });
	 
});
</script>
<!--如果有倒计时-->
		<!--倒计时结束-->    
<script type="text/javascript" src="<?php echo ($Theme['P']['js']); ?>/model1/jquery.imagesloaded.min.js"></script>
<script type="text/javascript" src="<?php echo ($Theme['P']['js']); ?>/model1/cbpBGSlideshow.min.js"></script>
<script type="text/javascript">
$(function() {
	cbpBGSlideshow.init();
});
</script>

</body></html>