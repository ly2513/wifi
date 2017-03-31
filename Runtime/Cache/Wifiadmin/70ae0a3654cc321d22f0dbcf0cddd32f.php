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
      <link href='<?php echo ($Theme['P']['root']); ?>/italic.css' rel='stylesheet' type='text/css' />

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

   
	<!-- libraries -->
    <link href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/lib/uniform.default.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/lib/select2.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/lib/bootstrap.datepicker.css" type="text/css" rel="stylesheet" />
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
    <div class="content" style="height: 100%;">
   	<div class="container-fluid">
            <div id="pad-wrapper" class="form-page">
            
                <div class="row-fluid form-wrapper">
                 		<div class="span12">
                            <h4>编辑广告</h4>
                        </div>
                    <!-- left column -->
                    <div class="span8 column">
                        <form name="doad"    method="post" enctype="multipart/form-data" >
                        <div class="alert span10" style="display: none;">
						  <span id="alertmsg"></span>
						</div>
                          <div class="field-box">
                                <label>广告名称:</label>
                                <input class="span8" type="text"  data-toggle="tooltip" data-trigger="focus" title="请输入标题" data-placement="right" name="title" id="title" value="<?php echo ($info['title']); ?>"/>
                            </div>
                            <div class="field-box">
                                <label>广告备注:</label>
                                <textarea rows="" cols="" class="span8" name="info"><?php echo ($info["info"]); ?></textarea>
                            </div>
                           	<div class="field-box">
                                <label>排序:</label>
                                <input class="span8" type="text"  data-toggle="tooltip" data-trigger="focus" title="输入广告投放顺序，数字越大越靠后" data-placement="right" name="sort" id="sort" value="<?php echo ($info['sort']); ?>"/>
                            </div>
                             
                            <div class="field-box">
                                <label>广告图片:</label>
                                
                              <img src="<?php echo ($info['pic']); ?>" style="width:200px;"/></br>
                            </div>
                            	<div class="field-box">
                                <label>选择广告:</label>
                                
                              <input type="file"  name="img">
                            </div>
                              	<div class="field-box">
                                <label>投放时间:</label>
                                 <div class="span4" id="startdt" data-date="<?php echo (date('Y-m-d',$info["startdate"])); ?>" data-date-format="yyyy-mm-dd">
                                 	<input type="text" class="span9" value="<?php echo (date('Y-m-d',$info["startdate"])); ?>" data-date-format="yyyy-mm-dd" name="startdate" id="startdate" > 
                                 </div>
                                  <label>到:</label>
                                <div class="span4" id="enddt" data-date="<?php echo (date('Y-m-d',$info["enddate"])); ?>" data-date-format="yyyy-mm-dd">
                                	<input type="text" class="span9" value="<?php echo (date('Y-m-d',$info["enddate"])); ?>" data-date-format="yyyy-mm-dd" name="enddate" id="enddate" > 
                                </div>
                                </div>
                                 
                               
                          
                              <div class="field-box ">
							<input type="hidden" name="id" value="<?php echo ($info["id"]); ?>">
                                <input type="submit"   class="btn-glow primary " id="btn_save" value="保存信息">
                                &nbsp;
								<a class="btn-glow  " href="<?php echo U('index');?>">返回列表</a>
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
            <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/bootstrap.datepicker.js"></script>
  <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/jquery.uniform.min.js"></script>
  <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/common.js"></script>

  <script type="text/javascript">
                

                        $(function () {
                             var shownow=new Date();
                             shownow=shownow.getTime();
                        	 $("#startdate").datepicker().on('changeDate',function(ev){
                        		  var startTime = ev.date.valueOf();
                        		 
                        		  if(startTime<shownow){
                        		   
                        		   $("#startdate").focus();
                        		  }
                        		});
                        	 $("#enddate").datepicker().on('changeDate',function(ev){
                        		 var et = ev.date.valueOf();
                        		 var t=$("#startdate").val();
                        		 if($("#startdate").val()==""){
                        			 		$("#enddate").val("");
											alert("请先选择投放时间");
											return;
											
                            		 }else{
		                        		 var dt=Date.parse($("#startdate").val());
		                        		 if(et<dt){
		                        			 $("#enddate").val($("#startdate").val());
		                            		 alert("广告结束时间不能小于开始时间");
		                        		
		                            	}
                            		 }
                        		 
                        		 
                             });
                            // add uniform plugin styles to html elements
                            $("input:checkbox, input:radio,input[type=file]").uniform();

                        });
                    </script>
</body>
</html>