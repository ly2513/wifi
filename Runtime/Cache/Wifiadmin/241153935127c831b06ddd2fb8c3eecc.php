<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<title><?php echo C('sitename');?>-管理平台</title>
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
      <link href='<?php echo ($Theme['P']['root']); ?>/italic.css' rel='stylesheet' type='text/css' />

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
                        <!-- <?php print_r($sonnode);?> -->
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
                            <h4>修改密码</h4>
                        </div>
                    <!-- left column -->
                    <div class="span8 column">
                        <form name="">
                        <div class="alert span9" style="display: none;">
						  <span id="alertmsg"></span>
						</div>
                              <div class="field-box">
                                <label>旧密码:</label>
                                <input class="span8" type="password" name="oldpwd" id="oldpwd" value="<?php echo ($info["money"]); ?>" data-toggle="tooltip" data-trigger="focus" title="4-20个字符 ，数字,字母或下划线组成" data-placement="right" />
                            </div>
                             <div class="field-box">
                                <label>新密码:</label>
                                <input class="span8" type="password" name="password" id="password" value="<?php echo ($info["money"]); ?>" data-toggle="tooltip" data-trigger="focus" title="4-20个字符 ，数字,字母或下划线组成" data-placement="right" />
                            </div>
							 <div class="field-box">
                                <label>重复新密码:</label>
                                <input class="span8" type="password" name="password2" id="password2" value="<?php echo ($info["money"]); ?>" data-toggle="tooltip" data-trigger="focus" title="4-20个字符 ，数字,字母或下划线组成" data-placement="right" />
                            </div>
                              <div class="field-box ">
                              <input type="hidden" name="sid" id="sid" value="<?php echo ($info["id"]); ?>">
                                <input type="button"   class="btn-glow primary "  id="btn_save" value="修改密码">

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

	<div class="modal  hide fade" id="myModal">
	  <div class="modal-header">
	    <a class="close" data-dismiss="modal">×</a>
	    <h3>提示信息</h3>
	  </div>
	  <div class="modal-body">
	    <p>内容</p>
	  </div>
	  <div class="modal-footer">
	    <a href="#" class="btn"  data-dismiss="modal">关闭</a>
	  </div>
	</div>
	<!-- scripts -->
    <script src="<?php echo ($Theme['P']['js']); ?>/jquery.js"></script>
    <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/bootstrap.min.js"></script>
    <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/theme.js"></script>

   <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/common.js"></script>
          
<!-- call this page plugins -->
    <script type="text/javascript">
    	
    	 
        $(function () {
        	
            // add uniform plugin styles to html elements
           
            
            //$(".alert").alert('close');
			$('#btn_save').bind('click',function(){

				var s=$('#oldpwd').val();
				var link=$('#password').val();
				var link2=$('#password2').val();
				

				  if (s == "") {
					  AlertTips("旧密码不能为空",1500);
					
				      return false;
				  }
				  if (link == "") {
					  
					  AlertTips("新密码不能为空",1500);
				        return false;
				  }
				if (link2 == "") {
					  
					  AlertTips("重复新密码不能为空",1500);
				        return false;
				  }
				  if(link != link2){
					 AlertTips("两次新密码输入不一致",1500);
				        return false;
				  }
				  $.ajax({
					  	url: '<?php echo U('pwd');?>',
				        type: "post",
						data:{
							'oldpwd':s,
							'password':link,
							'__hash__':$('input[name="__hash__"]').val()
							},
						dataType:'json',
						error:function(){
			
								AlertTips("服务器忙，请稍候再试",1500);
								},
						success:function(data){
								if(data.error==0){
									$('#oldpwd').val('');
									$('#password').val('');
									$(".modal-body").html("<p>保存成功</p>");
									$('#myModal').modal('show');
								}else{
									AlertTips(data.msg,1500);
								}
						}
					  });
				});
            
        });
    </script>
</body>
</html>