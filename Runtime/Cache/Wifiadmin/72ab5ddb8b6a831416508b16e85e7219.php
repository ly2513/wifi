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

   
	<!-- libraries -->
    <link href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/lib/uniform.default.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/lib/select2.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/compiled/form-showcase.css" type="text/css" media="screen" />
	<style>
		.trees{}
		.trees ul li{list-style: none;}
	</style>
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
    <div class="content" style="height: 100%;">
   	<div class="container-fluid">
            <div id="pad-wrapper" class="form-page">
            	<form name="" action="" method="post">
                <div class="row-fluid form-wrapper">
                 		<div class="span12">
                            <h4>角色权限</h4>
                        </div>
                    <!-- left column -->
                    <div class="span8 column">
                        <form name="" method="post">
                        <div class="alert span10" style="display: none;">
						  <span id="alertmsg"></span>
						</div>
                         	<div class="trees">
                         		<ul>
                         			<li>
                         				<?php if(is_array($trees)): $i = 0; $__LIST__ = $trees;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo['pid'] == 0): ?><input type="checkbox" name="all" id="ckall" onclick="checkAll();" value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?>
                         						<ul>
                         							<?php if(is_array($trees)): $i = 0; $__LIST__ = $trees;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i; if($menu['pid'] == $vo['id']): ?><li>
                         								<input type="checkbox" name="nodeid[]" id="<?php echo ($menu["id"]); ?>" value="<?php echo ($menu["id"]); ?>" <?php if(strpos($acc,"#".$menu['id']."#")>-1){echo "checked";} ?>><?php echo ($menu["title"]); ?>--<?php if(($menu['menuflag']) == "1"): ?>(菜单选项)<?php else: ?>(功能选项)<?php endif; ?>
                         								<ul>
                         									<?php if(is_array($trees)): $i = 0; $__LIST__ = $trees;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sons): $mod = ($i % 2 );++$i; if($sons['pid'] == $menu['id']): ?><li><input type="checkbox" name="nodeid[]" id="<?php echo ($sons["id"]); ?>" value="<?php echo ($sons["id"]); ?>" <?php if(strpos($acc,"#".$sons['id']."#")>-1){echo "checked";} ?>><?php echo ($sons["title"]); ?>--<?php if(($sons['menuflag']) == "1"): ?>(菜单选项)<?php else: ?>(功能选项)<?php endif; ?></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                         								</ul>
                         								</li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                         						</ul><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                         			</li>
                         		</ul>
                         	
                         		
                         	</div>
                           
                              <div class="field-box ">
								<input type="hidden" name="roleid" value="<?php echo ($role["id"]); ?>" id="roleid">
                                <input type="submit"   class="btn-glow primary " id="btn_save" value="保存信息">
                        
                              
                              
                            </div>
                        </form>
                    </div>

                    <!-- right column -->
                    <div class="span4 column pull-right">

                    </div>
                </div>
            	</form>
            </div>
        </div>
    </div>


	<!-- scripts -->
 <script src="<?php echo ($Theme['P']['js']); ?>/jquery.js"></script>
    <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/bootstrap.min.js"></script>
    <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/theme.js"></script>
  <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/jquery.uniform.min.js"></script>
  <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/common.js"></script>
  <script type="text/javascript">
                     

                        $(function () {
                        	
                            // add uniform plugin styles to html elements
                           $("input:checkbox, input:radio").uniform();

							
                           
                        });

                      

                        function checkAll(){

							if($('#ckall').prop('checked')){
								//全选
								$(':checkbox').prop('checked',true);
								$(':checkbox').each(function(){
										$(this).parent().addClass('checked');
									})
							}else{
								//反选
								$(':checkbox').prop('checked',false);
								$(':checkbox').each(function(){
									$(this).parent().removeClass('checked');
								})
								
							}
                        }
                        
                    </script>
</body>
</html>