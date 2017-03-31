<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width; initial-scale=1; maximum-scale=1; minimum-scale=1; user-scalable=no;" />

<title><?php echo ($shopinfo[0]['shopname']); ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo ($Theme['P']['root']); ?>/tmpl/wifiadv/css/css.css"><!--风格-->
<link rel="stylesheet" type="text/css" href="<?php echo ($Theme['P']['root']); ?>/tmpl/wifiadv/css/media.css"><!--自适应-->
<link href="<?php echo ($Theme['P']['js']); ?>/swiper/swiper.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo ($Theme['P']['js']); ?>/jquery.js"></script>
<style type="text/css">
	.content{
		width: 100%;
		position: absolute;
		bottom: 0px;
		background: none repeat scroll 0% 0% #999999;
		box-shadow: 4px 4px 10px 2px;
		color: #4D4E4D;
		font-size: 22px;
		line-height: 22px;
		text-align: center;
		padding: 0.5em 0em;
		border-radius: 10px;
	}
</style>
</head>
<body>
	<!-- 头部 BOF-->
	
	<!-- 头部 EOF-->
<div class="mainbox bgindex clearfix">
	<!-- 轮播广告 BOF-->
	<div class="focus">
		<div class="swiper-container">
	      <div class="swiper-wrapper">
	       <?php if(!empty($ad)): ?><!-- <?php print_r($ad);?> -->
			<?php if(is_array($ad)): $i = 0; $__LIST__ = $ad;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div  class="swiper-slide">
	     		<?php if(($vo['mode']) == "1"): ?><a href="<?php echo U('userauth/showad',array('id'=>$vo['id']));?>"><img src="<?php echo ($vo["ad_thumb"]); ?>" width="100%"></a>
	     		<?php else: ?>
	     		<img src="<?php echo ($vo["ad_thumb"]); ?>" width="80%"><?php endif; ?>
	     		</div><?php endforeach; endif; else: echo "" ;endif; ?>
			<?php else: ?>

			<div  class="swiper-slide">
	     		<img src="<?php echo ($Theme['P']['root']); ?>/images/ad/noad01.jpg" width="100%">
	     		</div>
				<div  class="swiper-slide">
	     		<img src="<?php echo ($Theme['P']['root']); ?>/images/ad/noad02.jpg" width="100%">
	     		</div><?php endif; ?>   		
	      </div>
    	</div>
	</div>
	<!-- 轮播广告 EOF -->
	<div class="wifinote">
		<a href="<?php echo ($shopinfo[0]['wxurl']); ?>" class="wifinote" style="display:block;" target="_blank">请关注微信号 " <?php echo ($shopinfo[0]['wx']); ?> " 即可免费上网</a>
	</div>
	<!-- 功能菜单 BOF-->
	<div class="bbox" style="position:relative">
		<div class="boxcontent" style="min-height:400px;">
			<div class="btnbox" style=" margin:0 auto;text-align:center;vertical-align:middle;width:350px;height:350px">
				<img src="<?php echo ($shopinfo[0]['codeimg']); ?>" style="min-width:300px;margin-top:0px; margin:0 auto;text-align:center;"/>
				<div class="btntitle">扫微信二维码</div>
			</div>
			<?php if(($show) == "1"): if($authmode['open'] == true): if(($authmode['overmax']) == "0"): ?><!-- <a href=""> -->
						
					<!-- </a> -->
					<!-- <a href="<?php echo ($shopinfo[0]['wxurl']); ?>">
						<div class="btnbox">
							<img src="<?php echo ($Theme['P']['root']); ?>/tmpl/wifiadv/img/01.png"/>
							<div class="btntitle">微信关注</div>
						</div>
					</a>	
					<a href="<?php echo U('userauth/comments');?>">
						<div class="btnbox">
							<img src="<?php echo ($Theme['P']['root']); ?>/tmpl/wifiadv/img/06.png"/>
							<div class="btntitle">客户留言</div>
						</div>
					</a> -->
					<div class="clear"></div>
				<?php else: ?>
					<h2 style="text-align: center;line-height:40px;">每日免费上网次数是<?php echo ($shopinfo[0]['countmax']); ?>次 </br><?php endif; ?>
			<?php else: ?>
				<h2 style="text-align: center;line-height:40px;">当前时间不提供免费上网服务.</br>
				<?php if(($authmode['openflag']) == "true"): ?>上网开放时间为每日 <?php echo ($authmode["opensh"]); ?>:00点至<?php echo ($authmode["openeh"]); ?>:00点<?php endif; ?>
				</h2><?php endif; endif; ?>
		</div>
		<div class="content">
			<a href="<?php echo U('userauth/comments');?>">意见反馈</a>
		</div>
	</div>
	<!-- 功能菜单 BOF-->
		<!--  -->

</div>
<script type="text/javascript" src="<?php echo ($Theme['P']['js']); ?>/swiper/swiper.js"></script>
<script>
           var mySwiper = new Swiper('.swiper-container',{
        	    loop:true,
        	    grabCursor: true,
        	    paginationClickable: true,
        	    calculateHeight:true,
        		autoplay:3000,
        	  });
           $(function(){
	           	$.ajax({
				  	url: '<?php echo U('login/countad');?>',
			        type: "post",
					data:{
						'ids':"<?php echo ($adid); ?>",
						},
					dataType:'json',
					error:function(){},
					success:function(data){}
				  });
           	});
 </script>
 <script type="text/javascript">
//     // 对浏览器的UserAgent进行正则匹配，不含有微信独有标识的则为其他浏览器
//     var useragent = navigator.userAgent;
//     if (useragent.match(/MicroMessenger/i) != 'MicroMessenger') {
//         // 这里警告框会阻塞当前页面继续加载
//         // alert('已禁止本次访问：您必须使用微信内置浏览器访问本页面！');
//         alert('建议使用微信内置浏览器器访问本页面！');
//         // 以下代码是用javascript强行关闭当前页面
//         // var opened = window.open('about:blank', '_self');
//         // opened.opener = null;
//         // opened.close();
//     }
</script>
 
</body>
</html>