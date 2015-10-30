<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<title><?php echo C('sitename');?>-代理商平台</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	 <!-- bootstrap -->
    <link href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/bootstrap/bootstrap.css" rel="stylesheet" />
       <link href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/bootstrap/bootstrap-responsive.css" rel="stylesheet" />
    <link href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/bootstrap/bootstrap-overrides.css" type="text/css" rel="stylesheet" />
    <!-- libraries -->
    <link href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/lib/font-awesome.css" type="text/css" rel="stylesheet" />

    <!-- global styles -->
    <link rel="stylesheet" type="text/css" href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/layout.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/elements.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/icons.css" />


    <!-- open sans font -->
      <link href="<?php echo ($Theme['P']['root']); ?>/font/italic.css" rel='stylesheet' type='text/css' />

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

   
    <!-- libraries -->
    <link href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/lib/uniform.default.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/lib/select2.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/compiled/form-showcase.css" type="text/css" media="screen" />
<body>


    <!-- navbar -->
    <div class="navbar navbar-inverse">
        <div class="navbar-inner">
            <button type="button" class="btn btn-navbar visible-phone" id="menu-toggler">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            
            <a class="brand" href="<?php echo U('index');?>"><img src="<?php echo ($Theme['P']['img']); ?>/wifilogo-mini.png" /></a>
            

            <ul class="nav pull-right">        
            	<li class=" hidden-phone">
                	
                   		<a href="<?php echo U('Index/systemdata');?>">
                   		<?php if($authlist_count > 50000): ?><font color="red">
                   			<script>
                   			alert('警告：授权数据量过大，可能造成系统运行缓慢，请立即点击右上角"系统优化"功能进行优化，保证系统运行流畅');
                   			</script>
                   			
                   			系统优化(需优化)
                   			</font>
                   			<?php else: ?>
                   			系统优化<?php endif; ?>
                   		</a>
                </li>       
               <li class=" hidden-phone">
                    	<a href="<?php echo U('Index/liences');?>">应用授权</a>
                </li>
                
                <li class=" hidden-phone">
                    	<a href="javascript:void(0);">登录帐号:(<?php echo (session('adminmame')); ?>)</a>
                </li>
                 <li class=" hidden-phone">
                    	<a href="<?php echo U('Index/pwd');?>">修改密码</a>
                </li>
                <li class="settings hidden-phone">
                    <a href="<?php echo U('login/loginout');?>" role="button">
                        <i class="icon-share-alt"></i>
                    </a>
                </li>
            </ul>            
        </div>
    </div>
    <!-- end navbar -->
     <!-- sidebar -->
    <div id="sidebar-nav">
        <ul id="dashboard-menu">
        <?php if(is_array($nav)): $i = 0; $__LIST__ = $nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo['pid'] == 1): if(in_array($vo['id'],$navids)): if($vo['single'] == 1): if((strtolower($nownav['m']) == strtolower($vo['m']) ) && strtolower($nownav['a']) == strtolower($vo['a'])): ?><li class="active">
	                <div class="pointer">
                    <div class="arrow"></div>
                    <div class="arrow_border"></div>
	                </div>
	        		<?php else: ?>
	        		    <li><?php endif; ?>
           			    <a href="<?php echo U(''.$vo['m'].'/'.$vo['a'].'');?>"  >
                      <i class="<?php echo ($vo["ico"]); ?>"></i>
                      <span><?php echo ($vo["title"]); ?></span>
                    </a>
            	    </li>
       		  <?php else: ?>
       	
				      <?php if($nownav['a'] == $vo['id']): ?><li class="active">
                  <div class="pointer">
                    <div class="arrow"></div>
                    <div class="arrow_border"></div>
                  </div>
        		  <?php else: ?>
        		    <li><?php endif; ?>
       			  <a class="dropdown-toggle" href="#" >
                <i class="<?php echo ($vo["ico"]); ?>"></i>
                <span><?php echo ($vo["title"]); ?></span>
                <i class="icon-chevron-down"></i>
              </a>
              <?php if($nownav['a'] == $vo['id']): ?><ul class="active submenu">
        		  <?php else: ?>
        		    <ul class="submenu"><?php endif; ?>
         
        			<?php if(is_array($nav)): $i = 0; $__LIST__ = $nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sonnode): $mod = ($i % 2 );++$i; if($sonnode['pid'] == $vo['id']): if(in_array($sonnode['id'],$navids)): ?><li>
		                    <a href="<?php echo U(''.$sonnode['m'].'/'.$sonnode['a'].'');?>"><?php echo ($sonnode['title']); ?></a>
                        
		                  </li><?php endif; endif; endforeach; endif; else: echo "" ;endif; ?>
            </ul>
           </li><?php endif; endif; endif; endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>

    <!-- end sidebar -->


	<!-- main container -->
    <div class="content">
    	 <div class="container-fluid">
            <div id="pad-wrapper" class="form-page">
            	
                <div class="row-fluid form-wrapper">
                 		<div class="span12">
                            <h4>添加代理商</h4>
                        </div>
                    <!-- left column -->
                    <div class="span8 column">
                        <form name="">
                        <div class="alert span10" style="display: none;">
						  <span id="alertmsg"></span>
						</div>
						<div class="field-box">
                                <label>代理商级别:</label>
                                 <div class="ui-select">
                                    <select name="level" id="level"><option value="-1">选择代理商等级</option>
                                    	<?php if(is_array($lvlist)): $i = 0; $__LIST__ = $lvlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </div>
                            </div>
                         <div class="field-box">
                                <label>登录帐号:</label>
                                <input class="span8" type="text"  data-toggle="tooltip" data-trigger="focus" title="4-20个字母，数字组成" data-placement="right" name="user" id="user"/>
                            </div>
                             <div class="field-box">
                                <label>登录密码:</label>
                                <input class="span8" type="password"  data-toggle="tooltip" data-trigger="focus" title="4-20个字母，数字" data-placement="right" name="password" id="password"/>
                            </div>
                            
                           <div class="field-box">
                                <label>代理商名称:</label>
                                <input class="span8" type="text"  data-toggle="tooltip" data-trigger="focus" title="代理商名称不能超过20个字" data-placement="right" name="name" id="name" value=""/>
                            	
                            </div>
                             <div class="field-box">
                                <label>联系人:</label>
                                <input class="span8" type="text" name="linker" id="linker" value="<?php echo ($shop["linker"]); ?>"/>
                            </div>
                              <div class="field-box">
                                <label>手机号码:</label>
                                <input class="span8" type="text" name="phone" id="phone" value="<?php echo ($shop["phone"]); ?>" />
                            </div>
                                 <div class="field-box">
                                <label>加盟费用:</label>
                                <input class="span8" type="text"  name="fee" id="fee" value=""/>
                            </div>
                           
                         
                               <div class="field-box">
                                <label>所属商圈:</label>
                                <div class="ui-select">
                                    <select name="province" id="province"><option value="北京市">北京市</option><option value="天津市">天津市</option><option value="河北省">河北省</option><option value="山西省">山西省</option><option value="内蒙古自治区">内蒙古自治区</option><option value="辽宁省">辽宁省</option><option value="吉林省">吉林省</option><option value="黑龙江省">黑龙江省</option><option value="上海市">上海市</option><option value="江苏省">江苏省</option><option value="浙江省">浙江省</option><option value="安徽省">安徽省</option><option value="福建省">福建省</option><option value="江西省">江西省</option><option value="山东省">山东省</option><option value="河南省">河南省</option><option value="湖北省">湖北省</option><option value="湖南省">湖南省</option><option value="广东省">广东省</option><option value="广西壮族自治区">广西壮族自治区</option><option value="海南省">海南省</option><option value="重庆市">重庆市</option><option value="四川省">四川省</option><option value="贵州省">贵州省</option><option value="云南省">云南省</option><option value="西藏自治区">西藏自治区</option><option value="陕西省">陕西省</option><option value="甘肃省">甘肃省</option><option value="青海省">青海省</option><option value="宁夏回族自治区">宁夏回族自治区</option><option value="新疆维吾尔自治区">新疆维吾尔自治区</option><option value="香港特别行政区">香港特别行政区</option><option value="澳门特别行政区">澳门特别行政区</option><option value="台湾省">台湾省</option><option value="其它">其它</option></select>
                                </div>
                                <div class="ui-select">
                                   <select name="city" id="city" ><option value="市辖区">市辖区</option><option value="市辖县">市辖县</option></select>
                                </div>
                                <div class="ui-select">
                                    <select name="area" id="area" ><option value="东城区">东城区</option><option value="西城区">西城区</option><option value="崇文区">崇文区</option><option value="宣武区">宣武区</option><option value="朝阳区">朝阳区</option><option value="丰台区">丰台区</option><option value="石景山区">石景山区</option><option value="海淀区">海淀区</option><option value="门头沟区">门头沟区</option><option value="房山区">房山区</option><option value="通州区">通州区</option><option value="顺义区">顺义区</option><option value="昌平区">昌平区</option><option value="大兴区">大兴区</option><option value="怀柔区">怀柔区</option><option value="平谷区">平谷区</option></select>
                 
                                </div>
                            </div>
                             <div class="field-box">
                                <label>地址:</label>
                                <input class="span8" type="text"  name="address" id="address" value="<?php echo ($shop["address"]); ?>"/>
                            </div>
                            <div class="field-box">
                                <label>状态:</label>
                             	 <div class="span8">
                                    <label class="radio">
                                        <input type="radio" name="state" id="status1" value="1" checked/>
                                        	正常
                                    </label>
                                    <label class="radio">
                                        <input type="radio" name="state" id="status2" value="0" />
                                        		停用
                                    </label>
                                </div>                   
                            </div>
                              <div class="field-box ">
                              
                              
                               
                                <input type="button"   class="btn-glow primary " id="btn_save" value="确认提交">
                        
                              
                              
                            </div>
                        </form>
                    </div>

                    <!-- right column -->
                    <div class="span4 column pull-right">

                    </div>
                </div>
            </div>
        </div>
    </div>


	<!-- scripts -->
    <script src="<?php echo ($Theme['P']['js']); ?>/jquery.js"></script>
    <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/bootstrap.min.js"></script>
    <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/theme.js"></script>
        <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/jquery.uniform.min.js"></script>
  <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/common.js"></script>
           <script src="<?php echo ($Theme['P']['js']); ?>/region_select.js"></script>
                    <script type="text/javascript">
                        new PCAS('province', 'city', 'area', '', '', '');

                        $(function () {
                        	
                            // add uniform plugin styles to html elements
                            $("input:checkbox, input:radio").uniform();

                            $('#btn_save').bind('click',function(){
                            	var user=$('#user').val();
                            	var psd=$('#password').val();
                				var s=$('#name').val();
                				var link=$('#linker').val();
                				var phone=$('#phone').val();
                				var pro=$('#province').val();
                				var city=$('#city').val();
                				var area=$('#area').val();
                				var ad=$('#address').val();
                				var lv=$('#level').val();
                				var fee=$('#fee').val();
                				var st=$("input[name='state']").val();
                				var linkflag=$("input[name='linkflag']:checked").val();
               
                				if(lv=="-1"){
                					  AlertTips("请选择代理商等级",1500);
                				        return false;
                    				}
                				
                				
                				if (user == "") {
              					  AlertTips("登录帐号不能为空",1500);
              				        return false;
              				  }
                				if (psd == "") {
              					  AlertTips("密码不能为空",1500);
              				        return false;
              				  }

                				  if (s == "") {
                					  AlertTips("商户名称不能为空",1500);
                				        return false;
                				  }
                				  if (link == "") {
                					  
                					  AlertTips("联系人不能为空",1500);
                				        return false;
                				  }
                				  if(fee==""){
                					  AlertTips("代理费不能为空",1500);
              				        return false;
                    				 }
                				if(!isaccount(user)){
                	                	
                					  AlertTips("登录帐号由4-20位数字或字母组成",1500);
                				        return false;
                				 }
                				 if(!isPhone(phone)){
                	
                					  AlertTips("请输入11位手机号码",1500);
                				        return false;
                				 }
                				
                				  $.ajax({
                					  	url: '<?php echo U('add');?>',
                				        type: "post",
                						data:{
                    						'account':user,
                    						'password':psd,
                							'name':s,
                							'linker':link,
                							'phone':phone,
                							'province':pro,
                							'city':city,
                							'area':area,
                							'address':ad,
                							'level':lv,
                							'state':st,
                							'fee':fee,
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
                									AlertTips(data.msg,200000);
                								}
                						}
                					  });
                				});
                        });
                    </script>

</body>
</html>