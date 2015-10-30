<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>跳转提示</title>
<!-- <base target=""> -->
<style type="text/css">
*{ padding: 0; margin: 0; }
body{ background: #EEEEEE; font-family: '微软雅黑'; color: #333; font-size: 16px; }

#errorbox{
width: 300px;
height:200px;
background: #2E363F;
border-radius: 10px;
border:3px solid #676764;
text-align: center;
color: #fff;
left:50%;
top:50%;
margin-left:-150px!important;/*FF IE7 该值为本身宽的一半 */
margin-top:-60px!important;/*FF IE7 该值为本身高的一半*/
margin-top:0px;
position:fixed!important;/* FF IE7*/
position:absolute;/*IE6*/
_top:   expression(eval(document.compatMode &&
        document.compatMode=='CSS1Compat') ?
        documentElement.scrollTop + (document.documentElement.clientHeight-this.offsetHeight)/2 :/*IE6*/
        document.body.scrollTop + (document.body.clientHeight - this.clientHeight)/2);/*IE5 IE5.5*/

	}
#errorbox a{
	text-decoration: none;
	color: #fff;
	font-size: 20px;
}
#errorbox p.success{
	padding-top:60px;
	color: #0F93F5;
}
#errorbox p.error{
	padding-top:60px;
	color: #c40000;
}
</style>
</head>

<body>
<div id="errorbox">
    <?php if(isset($message)): ?><p class="success"><?php echo($message); ?></p>
	<?php else: ?>
		<p class="error"><?php echo($error); ?></p><?php endif; ?>
	<p class="detail"></p>
	<p class="jump">页面自动 
		<a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间：<b id="wait"><?php echo($waitSecond); ?></b>
	</p>
	</div>
<script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time <= 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();
</script>
</body>
</html>