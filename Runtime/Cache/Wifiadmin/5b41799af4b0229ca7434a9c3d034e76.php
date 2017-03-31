<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<title><?php echo C('sitename');?>-管理后台</title>
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

   
	  <link rel="stylesheet" href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/compiled/index.css" type="text/css" media="screen" />    
	
<body>
<!-- 引用顶部页面 -->
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
<!-- 引用左侧页面 -->
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
    <div class="content" style="height: 100%;">
	    <div class="container-fluid">
	 <!-- upper main stats -->
            <div id="main-stats">
                <div class="row-fluid stats-row">
                    <div class="span3 stat">
                        <div class="data">
                        		认证流量
                            <span class="number" id="sumauth">0</span>
                            		次
                        </div>
                        <span class="date"><?php echo (date('Y-m-d g:i a',time())); ?>
                        </span>
                    </div>
                    <div class="span3 stat">
                        <div class="data">
                       	 新增用户
                            <span class="number" id="sumuser">0</span>
                           	 人
                        </div>
                        <span class="date"><?php echo (date('Y-m-d g:i a',time())); ?></span>
                    </div>
                    <div class="span3 stat">
                        <div class="data">
                       		 商户广告展示
                            <span class="number" id="sumshopad">0</span>
                            		次
                        </div>
                        <span class="date"><?php echo (date('Y-m-d g:i a',time())); ?></span>
                    </div>
                    <div class="span3 stat last">
                        <div class="data">
                        		投放广告
                            <span class="number" id="sumad">0</span>
                            次
                        </div>
                        <span class="date"><?php echo (date('Y-m-d g:i a',time())); ?></span>
                    </div>
                </div>
            </div>
            <!-- end upper main stats -->
    	    <div id="pad-wrapper">
    			<div class="row-fluid">
    				<div class="alert hide fade in span11">
    	            <a class="close" data-dismiss="alert" href="#">×</a>
    	            <div id="vermsg"></div>
    	          </div>
    				
    				
    				

    			</div>
    			
                <!-- statistics chart built with jQuery Flot -->
                <div class="row-fluid chart">
                	<div class="span6">
                		<h4>
                        	认证流量统计
                       	<a id="authbtn" class="hidden"></a>
                        </h4>
                        <div class="span12">
                            <div id="statsChart"></div>
                        </div>
                	</div>
                	<div class="span6">
                		<h4>
                        	注册用户统计
                       	<a id="authbtn" class="hidden"></a>
                        </h4>
                        <div class="span12">
                            <div id="userchart"></div>
                        </div>
                	</div>
                    
                </div>
                                <div class="row-fluid section">
                	<div class="span6">
                		<h4>
                        	商户广告统计
                       	<a id="authbtn" class="hidden"></a>
                        </h4>
                        <div class="span12">
                            <div id="shopadchart"></div>
                        </div>
                	</div>
                	<div class="span6">
                		<h4>
                        	投放广告统计
                       	<a id="authbtn" class="hidden"></a>
                        </h4>
                        <div class="span12">
                            <div id="adchart"></div>
                        </div>
                	</div>
                    
                </div>
            </div>
        </div>
    
	<!-- scripts -->
 <script src="<?php echo ($Theme['P']['js']); ?>/jquery.js"></script>
    <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/bootstrap.min.js"></script>
    <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/theme.js"></script>
<script src="<?php echo ($Theme['P']['js']); ?>/flot/jquery.flot.js"></script> 
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="<?php echo ($Theme['P']['js']); ?>/flot/excanvas.min.js"></script><![endif]-->  
<script src="<?php echo ($Theme['T']['js']); ?>/index.js"></script> 
<script>

$(function(){
	authchart('<?php echo U('pub/getauthrpt');?>');
	mchart('<?php echo U('pub/getuserchart');?>');
	shopad('<?php echo U('pub/getadrpt');?>');
	adchar('<?php echo U('pub/getpubadrpt');?>');
	getversion(<?php echo getvsersion();?>);

});
</script>
</body>
</html>