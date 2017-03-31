<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">


<html class=" js backgroundsize"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>正在验证</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content=" initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<meta http-equiv="refresh" content="<?php echo ($waitsecond); ?>;URL=<?php echo ($wifiurl); ?>">
<!--<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
<link rel="apple-touch-icon-precomposed" href="">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">-->
<link href="<?php echo ($Theme['T']['css']); ?>/model1/common.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo ($Theme['T']['css']); ?>/model1/component.css">
<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo ($Theme['P']['js']); ?>/model1/modernizr.custom.js"></script>
</head>

<body>

<section id="wrapper" class="wrapper">
   <?php if($info['openpush'] == 1): ?><!-- 图片轮播 -->
   <section class="slide-photos">
      <ul class="slides">
       <?php if(!empty($ad)): if(is_array($ad)): $i = 0; $__LIST__ = $ad;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div  class="swiper-slide">
	     			 <li style="display: list-item; background-image: url(<?php echo ($vo["pic"]); ?>);"><a href="">
		 <img src="<?php echo ($vo["pic"]); ?>">
		 </a></li>
	     		
	     		</div><?php endforeach; endif; else: echo "" ;endif; endif; ?>	
      
      
      
      
	 </ul>
   </section> 
   <!-- 主体 -->
   <section class="index-wrap">

  <!--自定认证-->
		


   </section><?php endif; ?>
   
   <!-- 底部 -->
   <div class="foot-space"></div>
   <footer class="index-foot">正在验证中，请耐心等待...</footer>
   
   
   <div id="bodyMask" class="body-mask" onclick="hidePop()"></div>   
  
</section>

<script src="<?php echo ($Theme['P']['root']); ?>/matrix/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo ($Theme['P']['js']); ?>/model1/jquery.flexslider.min.js" type="text/javascript"></script>
<script type="text/javascript">   
//图片轮播
$(window).load(function(){
     $('.slide-photos').flexslider({
        animation: "slide",
		slideshowSpeed:2000,
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