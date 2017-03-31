<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width; initial-scale=1; maximum-scale=1; minimum-scale=1; user-scalable=no;" />
<title><?php echo ($shopinfo[0]['shopname']); ?></title>
<link href="<?php echo ($Theme['T']['css']); ?>/login.css"  rel="stylesheet"/>

<link href="<?php echo ($Theme['P']['js']); ?>/swiper/swiper.css" rel="stylesheet" />
<link href="<?php echo ($Theme['T']['css']); ?>/media.css"  rel="stylesheet"/>
</head>

<body>


<div class="content">
	<div class="flash">
	
	<div class="swiper-container">
	      <div class="swiper-wrapper">
	     	
	     		 <?php if(!empty($ad)): if(is_array($ad)): $i = 0; $__LIST__ = $ad;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div  class="swiper-slide">
	     		<?php if(($vo['mode']) == "1"): ?><a href="<?php echo U('userauth/showad',array('id'=>$vo['id']));?>"><img src="<?php echo ($vo["ad_thumb"]); ?>" width="100%"></a>
	     		<?php else: ?>
	     		<img src="<?php echo ($vo["ad_thumb"]); ?>" width="100%"><?php endif; ?>
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
	<div class="protocol">
	<div class="protocol-img"></div>
	<div class="protocol-text" style="padding:0.5em;">
	为了提供安全可靠的免费无线服务,您需要注册确认。
	</div>
</div>
<?php if(($show) == "1"): if($authmode['open'] == true): if(($authmode['overmax']) == "0"): if(($authmode['wx_f']) == "1"): ?><div>
						<font size="5"  color="8E2929"><a href="<?php echo ($shopinfo[0]['wxurl']); ?>" style="font-size:20px;color:#8E2929;">点击关注微信号"<?php echo ($shopinfo[0]['wx']); ?>"即可认证上网</a></font>
					</div><?php endif; ?>
				<div class="ugbox">
				<?php if(($authmode['wx']) == "1"): ?><div class="ugbox-son"><a class="btn_base btn_bg_g"  href="<?php echo U('userauth/wxauth');?>">微信认证</a></div><?php endif; ?>
				</div>
				<div class="ugbox">
				<?php if(($authmode['allow']) == "1"): ?><div class="ugbox-son"><a class="btn_base btn_bg_g"  href="<?php echo U('userauth/noAuth');?>">一键上网</a></div><?php endif; ?>
				<?php if(($authmode['phone']) == "1"): ?><div class="ugbox-son"><a class="btn_base btn_bg_g"  href="<?php echo U('userauth/mobile');?>">手机认证</a></div><?php endif; ?>
				</div>
				<?php if(($authmode['reg']) == "1"): ?><div class="ugbox">
				<div class="ugbox-son"><a  class="btn_base btn_bg_b" href="<?php echo U('userauth/reg');?>">注册认证</a></div>
				<div class="ugbox-son"><a class="btn_base btn_bg_b" href="<?php echo U('userauth/login');?>">会员登录</a></div>
				</div><?php endif; ?>
			<?php else: ?>
					<div class="ugbox">
					<div class="ugbox-son">
						
						<h2 style="text-align: center;line-height:40px;">每日免费上网次数是<?php echo ($shopinfo[0]['countmax']); ?>次 </br>
						
						</h2>
						
					</div>
					</div><?php endif; ?>
			
		<?php else: ?>
			<div class="ugbox">
			<div class="ugbox-son">
				
				<h2 style="text-align: center;line-height:40px;">当前时间不提供上网服务.</br>
				<?php if(($authmode['openflag']) == "true"): ?>上网开放时间为每日 <?php echo ($authmode["opensh"]); ?>:00点至<?php echo ($authmode["openeh"]); ?>:00点<?php endif; ?>
				</h2>
				
			</div>
			</div><?php endif; endif; ?>

	
	

	


<div class="ugbox">
<div class="ugbox-son"><a class="btn_base btn_bg_g"  href="<?php echo U('userauth/comments');?>">客户留言</a></div>
</div>
</div>




	

<script type="text/javascript" src="<?php echo ($Theme['P']['js']); ?>/swiper/swiper.js"></script>
<script type="text/javascript" src="<?php echo ($Theme['P']['js']); ?>/jquery.js"></script>
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
  <div style="display: none">
     
      </div>
</body>
</html>